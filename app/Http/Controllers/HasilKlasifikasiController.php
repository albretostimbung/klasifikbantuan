<?php

namespace App\Http\Controllers;

use App\Models\Predict;
use Illuminate\Http\Request;

class HasilKlasifikasiController extends Controller
{
    public function index()
    {
        $penerimaBantuan = Predict::with('modelEvaluation', 'citizen')->where('is_eligible', 1)->get();

        return view('hasil-klasifikasi.index', compact('penerimaBantuan'));
    }
}
