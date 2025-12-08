<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Model;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'code';
    protected $keyType = 'string';

    protected $fillable = [
        'uraian',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function depreciationGroup()
    {
        return $this->belongsTo(DepreciationGroup::class, 'depreciation_group_code', 'code');
    }
}
