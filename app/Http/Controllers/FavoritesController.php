<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use \DB;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{

    /**
     * FavoritesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->only('store');
    }

    public function store(Reply $reply)
    {
        return $reply->favorite();
    }
}
