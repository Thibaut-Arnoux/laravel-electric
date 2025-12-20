<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\ItemUser;

class StoreItemUserAction
{
    /**
     * Create a new item-user relationship.
     *
     * @param array<string, mixed> $data
     */
    public function __invoke(array $data): ItemUser
    {
        return ItemUser::create([
            'user_id' => auth()->id(),
            'item_id' => $data['item_id'],
            'favorite' => $data['favorite'] ?? false,
            'note' => $data['note'] ?? null,
        ]);
    }
}
