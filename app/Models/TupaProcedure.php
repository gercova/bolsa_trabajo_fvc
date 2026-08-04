<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TupaProcedure extends Model
{
    use HasFactory;

    protected $table        = 'tupa_procedures';
    protected $primaryKey   = 'id';

    protected $fillable     = [
        'tupa_id',
        'category_id',
        'code',
        'name',
        'description',
        'requirements',
        'cost',
        'uit_percent',
        'qualification',
        'duration',
        'office',
        'is_active',
    ];

    protected $casts = [
        'requirements'  => 'array',
        'is_active'     => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(TupaCategory::class, 'category_id');
    }

    public function tupa()
    {
        return $this->belongsTo(Tupa::class, 'tupa_id');
    }
}
