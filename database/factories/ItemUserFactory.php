<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\ItemUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemUser>
 */
class ItemUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'item_id' => $this->getUniqueRandomItemId(),
            'favorite' => $this->faker->boolean(),
            'note' => $this->faker->optional()->sentence(),
        ];
    }

    private function getUniqueRandomItemId()
    {
        // Get item IDs already linked to user 1 via Pivot Model
        $usedItemIds = ItemUser::where('user_id', 1)->pluck('item_id');

        // Select a random item NOT already linked
        return Item::whereNotIn('id', $usedItemIds)
            ->inRandomOrder()
            ->value('id');
    }
}
