<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $sheets = Sheet::where('company_id', $user->company_id)->latest()->take(5)->get();
        
        return view('dashboard', compact('user', 'sheets'));
    }
}
