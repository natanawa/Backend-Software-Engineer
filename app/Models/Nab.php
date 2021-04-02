<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nab extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var string[]
    */

    protected function serializeDate(\DateTimeInterface $date) {
        return $date->format('Y-m-d H:i:s');
    }

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'id',
        'updated_at',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nab',
        'nab_amount',
    ];


}