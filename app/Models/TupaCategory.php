<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TupaCategory extends Model
{
    use HasFactory;

    protected $table = 'tupa_categories';

    protected $fillable = [
        'name',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function procedures()
    {
        return $this->hasMany(TupaProcedure::class, 'category_id');
    }
}
