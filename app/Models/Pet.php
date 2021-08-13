<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Pet extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'pet';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
