<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class Item extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'uom_code',
        'stock_code',
        'category_code',
        'min_stock',
        'max_stock',
        'notes',
        'is_active',
        'editor_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function stockCode()
    {
        return $this->belongsTo(StockCode::class, 'stock_code', 'code');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_code', 'code');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_code', 'code');
    }
}
