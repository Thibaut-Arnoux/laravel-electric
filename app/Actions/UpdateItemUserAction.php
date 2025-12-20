<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\ItemUser;

class UpdateItemUserAction
{
    /**
     * Update the item-user relationship with the provided data.
     *
     * @param array<string, mixed> $data
     */
    public function __invoke(ItemUser $itemUser, array $data): void
    {
        $itemUser->update($data);
    }
}
