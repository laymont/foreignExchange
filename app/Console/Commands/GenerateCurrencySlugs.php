<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateCurrencySlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currencies:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for currencies';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $currencies = Currency::all();

        $this->output->progressStart($currencies->count());
        foreach ($currencies as $currency) {
            $currency->slug = Str::uuid();
            $currency->save();
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();

        $this->info('Slugs generated for all currencies.');
    }
}
