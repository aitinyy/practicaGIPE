<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request){
        return response()->json([
            "name" => $request->input("name"),
            "message" => "hola",
        ]);
    }
}
