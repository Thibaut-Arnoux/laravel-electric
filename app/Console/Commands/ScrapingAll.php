<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScrapingAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraping:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run all scraping commands in the correct order';

    public function handle(): int
    {
        $this->info('Starting full data scraping...');
        $this->newLine();

        // Player classes must be scraped first (items reference them)
        if ($this->call('scraping:player-classes') !== Command::SUCCESS) {
            $this->error('Player classes scraping failed. Aborting.');

            return Command::FAILURE;
        }

        $this->newLine();

        if ($this->call('scraping:items') !== Command::SUCCESS) {
            $this->error('Items scraping failed.');

            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('Full data scraping completed successfully');

        return Command::SUCCESS;
    }
}
