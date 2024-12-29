<?php

namespace App\Console\Commands;

use App\Models\Currency;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class GetBcvCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bcv:get-currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Obtiene los valores de las divisas del BCV';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $url = 'http://www.bcv.org.ve/';

        try {
            $client = new Client([
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.134 Safari/537.36',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.5',
                    'Connection' => 'keep-alive',
                ],
                'verify' => false, // Agregamos esta línea para deshabilitar la verificación del certificado
            ]);
            $response = $client->get($url);
            $html = $response->getBody()->getContents();

            $crawler = new Crawler($html);

            $divisas = [];

            // Iterar sobre cada div con un id de divisa (euro, yuan, lira, rublo, dolar)
            $divisas_ids = ['euro', 'yuan', 'lira', 'rublo', 'dolar'];
            foreach ($divisas_ids as $id_divisa) {
                $contenedorDivisa = $crawler->filter("#{$id_divisa}");

                // Verificar si el contenedor de la divisa fue encontrado
                if ($contenedorDivisa->count() === 0) {
                    Log::error("No se encontró el contenedor de la divisa {$id_divisa}.");
                    $this->error("No se encontró el contenedor de la divisa {$id_divisa}.");

                    continue;  // Saltar a la siguiente divisa
                }

                // Obtener el nombre de la divisa a partir del span
                $nombreDivisa = trim($contenedorDivisa->filter('span')->first()->text()); // Obtener el primer span

                // Obtener el valor de la divisa desde el strong
                $valorDivisa = trim($contenedorDivisa->filter('div.centrado strong')->text());

                $divisas[$nombreDivisa] = $valorDivisa;
            }

            $this->info('Valores de las divisas del BCV:');
            foreach ($divisas as $divisa => $valor) {
                $this->storeValue($divisa, $valor);
                $this->line("{$divisa}: {$valor}");
            }

        } catch (\Exception $e) {
            Log::error('Error al obtener las divisas del BCV: '.$e->getMessage());
            $this->error('Error al obtener las divisas del BCV: '.$e->getMessage());
        }
    }

    private function storeValue(string $acronym, $value): void
    {
        $currency = Currency::where('acronym', $acronym)->first();

        // 1. Reemplaza la coma por un punto
        $value = str_replace(',', '.', $value);

        // 2. Convierte la cadena a un float
        $value = (float) $value;

        $currency->values()->create([
            'date' => now(),
            'value' => $value,
        ]);
    }
}
