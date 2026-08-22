<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $table = 'charges';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'higher_position_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
