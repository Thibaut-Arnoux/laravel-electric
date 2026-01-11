<?php

namespace App\Console\Commands;

use App\Data\Scraping\ItemData;
use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Spatie\LaravelData\DataCollection;

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

    public function handle(): void
    {
        $this->info('Starting item scraping...');

        $url = config('services.flyff.url').config('services.flyff.item_endpoint');
        $timeout = config('services.flyff.timeout');
        $chunkSize = config('services.flyff.chunk');

        $ids = Http::timeout($timeout)->get($url)->throw()->json();
        $total = count($ids);

        $this->info("Found {$total} items to scrape");

        collect($ids)
            ->lazy()
            ->chunk($chunkSize)
            ->each(function ($chunk, $index) use ($url, $timeout, $total, $chunkSize) {
                $items = Http::timeout($timeout)
                    ->get(sprintf("$url/%s", implode(',', $chunk->all())))
                    ->throw()
                    ->json();

                $validatedItems = ItemData::collect(
                    array_map($this->cleanItemData(...), $items),
                    DataCollection::class
                );

                foreach ($validatedItems as $validatedItem) {
                    Item::create($validatedItem->toArray());
                }

                $processed = min(($index + 1) * $chunkSize, $total);
                $this->info("Processed {$processed}/{$total} items");
            });

        $this->info('Item scraping completed successfully');
    }

    protected function cleanItemData(array $item): array
    {
        $item['description'] = $this->cleanLanguageField($item['description'] ?? []);
        $item['name'] = $this->cleanLanguageField($item['name'] ?? []);
        $item['element'] = $item['element'] === 'none' ? null : ($item['element'] ?? null);

        if (isset($item['minDefense'], $item['maxDefense']) && $item['minDefense'] > $item['maxDefense']) {
            $this->warn("Item {$item['id']}: Fixed inverted defense values");
            [$item['minDefense'], $item['maxDefense']] = [$item['maxDefense'], $item['minDefense']];
        }

        return $item;
    }

    protected function cleanLanguageField(array $field): array
    {
        $cleaned = array_map(
            fn ($value) => $value === 'null' ? '' : $value,
            $field
        );

        // Ensure at least one key exists for ElectricSQL (raw database access)
        if (empty($cleaned)) {
            $cleaned['en'] = '';
        }

        return $cleaned;
    }
}
