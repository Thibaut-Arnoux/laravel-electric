<?php

namespace App\Console\Commands;

use App\Data\Scraping\ItemData;
use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScrapingItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraping:items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scraping items from Flyff API';

    public function handle(): int
    {
        $this->info('Starting item scraping...');

        $url = config('services.flyff.url').config('services.flyff.item_endpoint');
        $timeout = config('services.flyff.timeout');
        $chunkSize = config('services.flyff.chunk');

        try {
            $response = Http::timeout($timeout)
                ->retry(3, 100)
                ->get($url);

            if ($response->failed()) {
                $this->error('Failed to fetch item IDs from API');

                return Command::FAILURE;
            }

            /** @var array<int, string> $ids */
            $ids = $response->json();
        } catch (\Exception $e) {
            $this->error("API Error: {$e->getMessage()}");
            Log::error('Failed to fetch item IDs', ['error' => $e->getMessage()]);

            return Command::FAILURE;
        }

        $total = count($ids);
        $this->info("Found {$total} items to scrape");

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

                    $items = $response->json();

                    DB::transaction(function () use ($items) {
                        foreach ($items as $item) {
                            $cleanedItem = $this->cleanItemData($item);
                            $validatedItem = ItemData::from($cleanedItem);

                            Item::updateOrCreate(
                                ['item_id' => $validatedItem->id],
                                $validatedItem->toArray()
                            );
                        }
                    });

                    $progressBar->advance($chunk->count());
                } catch (\Exception $e) {
                    $this->newLine();
                    $this->warn("Error processing chunk: {$e->getMessage()}");
                    Log::warning('Error processing item chunk', [
                        'chunk' => $chunk->all(),
                        'error' => $e->getMessage(),
                    ]);
                }
            });

        $progressBar->finish();
        $this->newLine();
        $this->info('Item scraping completed successfully');

        return Command::SUCCESS;
    }

    /**
     * @param  array<string, mixed>  $item
     * @return array<string, mixed>
     */
    protected function cleanItemData(array $item): array
    {
        $item['description'] = $this->cleanLanguageField($item['description'] ?? []);
        $item['name'] = $this->cleanLanguageField($item['name'] ?? []);
        $item['element'] = in_array($item['element'] ?? null, ['none', '', null], true)
            ? null
            : $item['element'];

        if (isset($item['minDefense'], $item['maxDefense']) && $item['minDefense'] > $item['maxDefense']) {
            Log::warning('Fixed inverted item defense values', [
                'item_id' => $item['id'],
                'original_min' => $item['minDefense'],
                'original_max' => $item['maxDefense'],
            ]);

            [$item['minDefense'], $item['maxDefense']] = [$item['maxDefense'], $item['minDefense']];
        }

        return $item;
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
