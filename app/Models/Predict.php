<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Predict extends Model
{
    protected $guarded = [];

    public function citizen()
    {
        return $this->belongsTo(Citizen::class);
    }

    public function modelEvaluation()
    {
        return $this->belongsTo(ModelEvaluation::class);
    }
}
