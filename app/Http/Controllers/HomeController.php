<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class HomeController extends Controller
{
    public function home(){
        $totalRows = File::count();
        return view("Home.layouts.default",compact('totalRows'));
    }
}
