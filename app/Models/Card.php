<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $dates = ['updated_at', 'created_at'];

    protected $casts = [
        'colors' => 'json',
        'image_uris' => 'json',
        'legalities' => 'json',
    ];

    protected $fillable = [
        'uuid',
        'name',
        'type',
        'mana_cost',
        'image_uris',
        'legalities',
        'released_at',
        'oracle_text',
        'set_name',
        'layout',
    ];

    protected $hidden = [
        'id',
        'uuid',
        'created_at',
        'updated_at'
    ];
}
