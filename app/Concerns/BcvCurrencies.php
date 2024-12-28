<?php

namespace App\Concerns;

use App\Models\CurrencyValue;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;
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
                'verify' => false,
            ],
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

            $crawler->filter('div[id]')->each(function (Crawler $node) use (&$currenciesData) {
                $id = $node->attr('id');
                $value = trim($node->filter('div.centrado strong')->text());

                // Reemplace la coma por un punto para poder convertir a float
                $value = (float) str_replace(',', '.', $value);

                // Almacena el valor en el arreglo de divisas
                $currenciesData[$id] = $value;
            });

            // Cache de la respuesta por un minuto
            Cache::put('exchange_rates', $currenciesData, now()->addMinutes(1));

            // Guardar los valores en el modelo CurrencyValue
            foreach ($currenciesData as $id => $value) {
                // Mapear los ids de las monedas captura en el div con los ids de Currency
                $currencyId = $this->mapCurrencyId($id);
                if ($currencyId) {
                    CurrencyValue::create([
                        'date' => now(),
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
     *
     * @param string $id
     * @return int|null
     */
    protected function mapCurrencyId(string $id): ?int
    {
        $currencyMap = [
            'euro' => 1, // ID de Currency para EUR
            'yuan' => 2, // ID de Currency para CNY
            'lira' => 3, // ID de Currency para TRY
            'rublo' => 4, // ID de Currency para RUB
            'dolar' => 5, // ID de Currency para USD
        ];

        return $currencyMap[$id] ?? null;
    }
}
