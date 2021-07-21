<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Activity extends Model
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'activity';

    public function pet() {
        return $this->belongsTo(Pet::class);
    }
}
