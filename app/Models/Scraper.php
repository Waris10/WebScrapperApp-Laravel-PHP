<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scraper extends Model
{
    protected $table = 'scraper';
    protected $fillable = [
        'user_id',
        'url',
        'result',
        'images',
        'videos',
        'external_links',
    ];

    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
        'external_links' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
