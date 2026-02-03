<?php

namespace App\Console\Commands;

use App\Data\Scraping\PlayerClassData;
use App\Models\PlayerClass;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScrapingPlayerClasses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraping:player-classes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scraping player classes from Flyff API';

    public function handle(): int
    {
        $this->info('Starting player classes scraping...');

        $url = config('services.flyff.url').config('services.flyff.class_endpoint');
        $timeout = config('services.flyff.timeout');
        $chunkSize = config('services.flyff.chunk');

        try {
            $response = Http::timeout($timeout)
                ->retry(3, 100)
                ->get($url);

            if ($response->failed()) {
                $this->error('Failed to fetch player class IDs from API');

                return Command::FAILURE;
            }

            /** @var array<int, string> $ids */
            $ids = $response->json();
        } catch (\Exception $e) {
            $this->error("API Error: {$e->getMessage()}");
            Log::error('Failed to fetch player class IDs', ['error' => $e->getMessage()]);

            return Command::FAILURE;
        }

        $total = count($ids);
        $this->info("Found {$total} player classes to scrape");

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        collect($ids)
            ->lazy()
            ->chunk($chunkSize)
            ->each(function ($chunk) use ($url, $timeout, $progressBar) {
                try {
                    $response = Http::timeout($timeout)
                        ->retry(3, 100)
                        ->get(sprintf("$url/%s", implode(',', $chunk->all())));

                    if ($response->failed()) {
                        $this->newLine();
                        $this->warn("Failed to fetch chunk: {$chunk->implode(',')}");

                        return;
                    }

                    $classes = $response->json();

                    DB::transaction(function () use ($classes) {
                        foreach ($classes as $class) {
                            $cleanedClass = $this->cleanClassData($class);
                            $validatedClass = PlayerClassData::from($cleanedClass);

                            PlayerClass::updateOrCreate(
                                ['class_id' => $validatedClass->id],
                                $validatedClass->toArray()
                            );
                        }
                    });

                    $progressBar->advance($chunk->count());
                } catch (\Exception $e) {
                    $this->newLine();
                    $this->warn("Error processing chunk: {$e->getMessage()}");
                    Log::warning('Error processing player class chunk', [
                        'chunk' => $chunk->all(),
                        'error' => $e->getMessage(),
                    ]);
                }
            });

        $progressBar->finish();
        $this->newLine();
        $this->info('Player classes scraping completed successfully');

        return Command::SUCCESS;
    }

    /**
     * @param  array<string, mixed>  $class
     * @return array<string, mixed>
     */
    protected function cleanClassData(array $class): array
    {
        $class['name'] = $this->cleanLanguageField($class['name'] ?? []);

        // Convert empty type string to null (for master classes like Harlequin)
        if (($class['type'] ?? '') === '') {
            $class['type'] = null;
        }

        // Fix inverted min/max level values if needed
        if (isset($class['minLevel'], $class['maxLevel']) && $class['minLevel'] > $class['maxLevel']) {
            Log::warning('Fixed inverted player class level values', [
                'class_id' => $class['id'],
                'original_min' => $class['minLevel'],
                'original_max' => $class['maxLevel'],
            ]);

            [$class['minLevel'], $class['maxLevel']] = [$class['maxLevel'], $class['minLevel']];
        }

        // Ensure parent is null if not set
        if (! isset($class['parent'])) {
            $class['parent'] = null;
        }

        return $class;
    }

    /**
     * @param  array<string, string>  $field
     * @return array<string, string>
     */
    protected function cleanLanguageField(array $field): array
    {
        $cleaned = array_map(
            fn ($value) => $value === 'null' ? '' : $value,
            $field
        );

        // ensure at least one key exists for ElectricSQL (raw database access)
        if (empty($cleaned)) {
            $cleaned['en'] = '';
        }

        return $cleaned;
    }
}
