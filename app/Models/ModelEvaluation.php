<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelEvaluation extends Model
{
    protected $guarded = [];

    public function predicts()
    {
        return $this->hasMany(Predict::class, 'model_evaluation_id');
    }
}
