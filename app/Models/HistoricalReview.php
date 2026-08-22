<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricalReview extends Model
{
    protected $table        = 'historical_reviews';
    protected $primaryKey   = 'id';
    protected $dates        = ['created_at', 'updated_at'];
    protected $fillable     = [
        'title',
        'description',
        'start_year',
        'end_year',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
        'start_year'=> 'integer',
        'end_year'  => 'integer',
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
    ];
}
