<?php

namespace App\Concerns;

use App\Models\CurrencyValue;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class BcvCurrencies
{
    protected string $url;

    protected array $data;

    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.134 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Connection' => 'keep-alive',
            ],
            'verify' => false, //base_path('certs/cacert.pem'), // Asegúrate de tener configurado el certificado o elimina esta línea si es necesario
        ]);
        $this->url = config('bcv.url');
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function scrape(): void
    {
        $response = $this->client->get($this->url);
        if ($response->getStatusCode() === 200) {
            $html = (string) $response->getBody();
            $crawler = new Crawler($html);

            $currenciesData = [];

            // Obtener la fecha
            $fechaTexto = $crawler->filter('div.pull-right.dinpro.center span.date-display-single')->attr('content');

            $fecha = Carbon::parse($fechaTexto);

            // Iterar sobre cada div con un id de divisa (euro, yuan, lira, rublo, dolar)
            $divisas_ids = ['euro', 'yuan', 'lira', 'rublo', 'dolar'];
            foreach ($divisas_ids as $id_divisa) {
                $contenedorDivisa = $crawler->filter("#{$id_divisa}");

                // Verificar si el contenedor de la divisa fue encontrado
                if ($contenedorDivisa->count() === 0) {
                    Log::error("No se encontró el contenedor de la divisa {$id_divisa}.");

                    continue;  // Saltar a la siguiente divisa
                }

                // Obtener el nombre de la divisa a partir del span
                $nombreDivisa = trim($contenedorDivisa->filter('span')->first()->text()); // Obtener el primer span

                // Obtener el valor de la divisa desde el strong
                $valorDivisa = trim($contenedorDivisa->filter('div.centrado strong')->text());
                // Reemplace la coma por un punto para poder convertir a float
                $valorDivisa = (float) str_replace(',', '.', $valorDivisa);

                $currenciesData[$nombreDivisa] = $valorDivisa;

            }

            // Cache de la respuesta por un minuto
            Cache::put('exchange_rates', $currenciesData, now()->addMinutes(1));

            // Guardar los valores en el modelo CurrencyValue
            foreach ($currenciesData as $id => $value) {
                // Mapear los ids de las monedas captura en el div con los ids de Currency
                $currencyId = $this->mapCurrencyId($id);
                if ($currencyId) {
                    CurrencyValue::create([
                        'date' => $fecha, // Usar la fecha obtenida
                        'currency_id' => $currencyId,
                        'value' => $value,
                    ]);
                }
            }
        } else {
            // Manejo de errores aquí
            Log::error('Error al acceder a la página del BCV');
            throw new \RuntimeException('Error al acceder a la página del BCV');
        }
    }

    /**
     * Función para mapear el ID de la divisa a la tabla de currencies.
     */
    protected function mapCurrencyId(string $id): ?int
    {
        $currencyMap = [
            'EUR' => 1, // ID de Currency para EUR
            'CNY' => 2, // ID de Currency para CNY
            'TRY' => 3, // ID de Currency para TRY
            'RUB' => 4, // ID de Currency para RUB
            'USD' => 5, // ID de Currency para USD
        ];

        return $currencyMap[$id] ?? null;
    }
}
