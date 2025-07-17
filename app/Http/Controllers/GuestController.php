<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class GuestController extends Controller
{
    public function dashboard()
    {
        if (session('authenticated') && session('role')) {
            return redirect('/dashboard/' . session('role'));
        }
        $products = Product::active()->inStock()->latest()->take(6)->get();
        return view('guest.dashboard', compact('products'));
    }
} 