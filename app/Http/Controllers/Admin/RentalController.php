<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with(['user', 'category', 'payment'])
            ->latest()
            ->paginate(15);
        return view('admin.rentals.index', compact('rentals'));
    }

    public function show(Rental $rental)
    {
        $rental->load(['user', 'category', 'invoice', 'payment']);
        return view('admin.rentals.show', compact('rental'));
    }

    public function approvePayment(Rental $rental)
    {
        if ($rental->payment && $rental->payment->status === 'pending') {
            $rental->payment->update(['status' => 'completed']);
            $rental->update(['status' => 'active']);
        }

        return redirect()->route('admin.rentals.show', $rental)
            ->with('success', 'Ödeme onaylandı ve kiralama aktifleştirildi.');
    }

    public function rejectPayment(Rental $rental)
    {
        if ($rental->payment && $rental->payment->status === 'pending') {
            $rental->payment->update(['status' => 'failed']);
            $rental->update(['status' => 'cancelled']);
        }

        return redirect()->route('admin.rentals.show', $rental)
            ->with('success', 'Ödeme reddedildi ve kiralama iptal edildi.');
    }

    public function reports()
    {
        // Günlük ve aylık kiralama sayıları
        $dailyRentals = Rental::whereDate('created_at', today())->count();
        $monthlyRentals = Rental::whereMonth('created_at', now()->month)->count();

        // Toplam gelir
        $revenue = Rental::whereHas('payment', function ($query) {
            $query->where('status', 'completed');
        })->sum('total_amount');

        // Bekleyen ödemeler
        $pendingPayments = Payment::where('status', 'pending')->count();

        // Kategori bazlı istatistikler
        $categoryStats = DB::table('rentals')
            ->join('categories', 'rentals.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('COUNT(CASE WHEN rentals.status = "active" THEN 1 END) as active_count'),
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(rentals.total_amount) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Ödeme yöntemi istatistikleri
        $paymentStats = DB::table('payments')
            ->select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get();

        return view('admin.rentals.reports', compact(
            'dailyRentals',
            'monthlyRentals',
            'revenue',
            'pendingPayments',
            'categoryStats',
            'paymentStats'
        ));
    }
}