<?php

namespace App\Models;

use App\Traits\ElectricAttributes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ItemUser extends Pivot
{
    use ElectricAttributes;
    use HasUuids;

    /** @var list<string> */
    protected $fillable = ['item_id', 'user_id', 'favorite', 'note'];

    /** @var string[] */
    protected static array $shape = ['id', 'item_id', 'favorite', 'note', 'created_at', 'updated_at'];

    /**
     * Retrieve the model for a bound value, scoped to the authenticated user.
     */
    public function resolveRouteBinding($value, $field = null): ?ItemUser
    {
        return $this->where($field ?? $this->getRouteKeyName(), $value)
            ->where('user_id', auth()->id())
            ->first();
    }
}
