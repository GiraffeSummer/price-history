<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'supplier_id',
        'price',
        'stock',
    ];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => $value
        );
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function scopeOrderByRecommended($query)
    {
        // TODO : implement verified
        return $query->orderByRaw('CASE WHEN stock > 0 THEN 1 ELSE 2 END, price');
    }
}
