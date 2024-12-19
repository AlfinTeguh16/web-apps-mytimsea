<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articles;

class UsersController extends Controller
{
    public function index(){
        $getArticles = Articles::with('user')->latest()->get();

        return view('pages.dashboard.users.index', compact('getArticles'));
    }
}
