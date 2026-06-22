<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_sales' => Product::sum('price') ?? 0,
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function stats()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_sales' => Product::sum('price') ?? 0,
            'recent_products' => Product::latest()->take(5)->get([
                'id',
                'name',
                'price',
                'created_at'
            ]),
        ];

        return response()->json($stats);
    }
}
