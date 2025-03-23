<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $citizens = Citizen::all();
        $attributes = Attribute::all();
        return view('dashboard.index', compact('citizens', 'attributes'));
    }

    public function getClassificationData()
{
    $data = DB::table('predicts')
        ->join('citizens', 'predicts.citizen_id', '=', 'citizens.id')
        ->selectRaw("
            CASE
                WHEN income < 2000000 THEN 'Pendapatan Rendah'
                WHEN income BETWEEN 2000000 AND 5000000 THEN 'Pendapatan Menengah'
                ELSE 'Pendapatan Tinggi'
            END AS income_category,
            SUM(CASE WHEN is_eligible = 0 THEN 1 ELSE 0 END) AS not_eligible,
            SUM(CASE WHEN is_eligible = 1 THEN 1 ELSE 0 END) AS eligible
        ")
        ->groupBy('income_category')
        ->get();

    return response()->json($data);
}
}
