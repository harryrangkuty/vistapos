<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Model;
use App\Models\Asset\AssetProfile;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'aset_ruang';

    protected $casts = [
        'is_lab' => 'boolean',
    ];

    public function profil()
    {
        return $this->hasMany(AssetProfile::class, 'ruang_id');
    }

    public function getNamaAttribute()
    {
        return str_replace("'", "`", $this->attributes['nama']);
    }
}
