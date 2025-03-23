<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Citizen extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function attributes() {
        return $this->hasMany(AttributeCitizen::class);
    }

    public function predict() {
        return $this->hasOne(Predict::class);
    }
}
