<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articles;

class AdminController extends Controller
{
    public function index(){
        $getArticles = Articles::with('user')->latest()->get();

        return view('pages.dashboard.admin.index', compact('getArticles'));
    }
}
