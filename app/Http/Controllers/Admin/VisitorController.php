<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitorController extends Controller
{
    public function index()
    {
        $today = Visitor::whereDate('created_at', today())->count();
        $yesterday = Visitor::whereDate('created_at', today()->subDay())->count();
        $thisWeek = Visitor::whereBetween('created_at', [now()->startOfWeek(), now()])->count();
        $thisMonth = Visitor::whereMonth('created_at', now()->month)->count();

        $dailyStats = Visitor::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        $popularPages = Visitor::select('page_url', DB::raw('COUNT(*) as total'))
            ->groupBy('page_url')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        return view('admin.visitors.index', compact(
            'today',
            'yesterday',
            'thisWeek',
            'thisMonth',
            'dailyStats',
            'popularPages'
        ));
    }
}