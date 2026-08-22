<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOffer extends Model
{
    protected $table = 'job_offers';
    protected $fillable = [
        'title',
        'company',
        'location',
        'description',
        'url',
        'source',
        'is_active'
    ];
}
