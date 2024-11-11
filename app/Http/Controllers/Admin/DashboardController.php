<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Rental;
use App\Models\Visitor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $categoryCount = Category::count();
        $activeRentals = Rental::where('status', 'active')->count();
        $todayVisitors = Visitor::whereDate('created_at', today())->count();
        
        $recentRentals = Rental::with(['category', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'categoryCount',
            'activeRentals',
            'todayVisitors',
            'recentRentals'
        ));
    }
}