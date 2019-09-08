<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $json = json_decode(Storage::disk('public')->get('news2-category-prediction.json'), true);
        $json = collect($json)
            ->unique(function ($noticia) {
                return $noticia['Texto'];
            })
            ->groupBy('Id_Noticia');
        return view('home', [
            'json' => $json
        ]);
    }
}
