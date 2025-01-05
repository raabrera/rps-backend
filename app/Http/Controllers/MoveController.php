<?php

namespace App\Http\Controllers;

use App\Models\Move;
use Illuminate\Http\Request;

class MoveController extends Controller
{
    public function index(request $request)
    {
        $moves = Move::all();
        return response()->json(['moves' => $moves]);
    }
}
