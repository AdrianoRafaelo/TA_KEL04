<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kurikulum;

class MatakuliahController extends Controller
{
    public function index()
    {
        $kurikulum = Kurikulum::where('active', 1)->orderBy('semester')->get()->groupBy('semester');
        return view('matakuliah', compact('kurikulum'));
    }
}
