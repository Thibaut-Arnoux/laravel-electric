<?php

namespace App\Console\Commands;

use App\Data\Scraping\ItemData;
use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\LazyCollection;
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

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $url = config('services.flyff.url').config('services.flyff.item_endpoint');
        /** @var string[] */
        $ids = Http::timeout(config('services.flyff.timeout'))
            ->get($url)
            ->throw()
            ->json();

        collect($ids)
            ->lazy()
            ->chunk(config('services.flyff.chunk'))
            ->each(function (LazyCollection $c) use ($url) {
                $items = Http::timeout(config('services.flyff.timeout'))
                    ->get(sprintf("$url/%s", implode(',', $c->all())))
                    ->throw()
                    ->json();

                $validatedItems = ItemData::collect($items, DataCollection::class);
                foreach ($validatedItems as $validatedItem) {
                    Item::create($validatedItem->toArray());
                }
            });
    }
}
