<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Rental;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\PaytrService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RentalController extends Controller
{
    protected $paytrService;

    public function __construct(PaytrService $paytrService)
    {
        $this->paytrService = $paytrService;
        $this->middleware('auth');
    }

    public function create(Category $category)
    {
        if ($category->isCurrentlyRented()) {
            return back()->with('error', 'Bu kategori şu anda kiralanmış durumda.');
        }

        return view('rentals.create', compact('category'));
    }

    public function store(Request $request, Category $category)
    {
        $validated = $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date|after:+6 days',
        ]);

        $existingRental = $category->rentals()
            ->where('status', 'active')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']]);
            })->exists();

        if ($existingRental) {
            return back()->with('error', 'Seçilen tarih aralığında bu kategori zaten kiralanmış.');
        }

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $days = $startDate->diffInDays($endDate);
        $totalAmount = $category->daily_rate * $days;

        $rental = Rental::create([
            'user_id' => auth()->id(),
            'category_id' => $category->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_amount' => $totalAmount,
            'status' => 'pending'
        ]);

        return redirect()->route('rentals.invoice', $rental);
    }

    public function invoice(Rental $rental)
    {
        if ($rental->invoice) {
            return redirect()->route('rentals.payment', $rental);
        }

        return view('rentals.invoice', compact('rental'));
    }

    public function storeInvoice(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'company_name' => 'nullable|required_with:tax_number,tax_office|string|max:255',
            'tax_number' => 'nullable|required_with:company_name|string|max:50',
            'tax_office' => 'nullable|required_with:company_name|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'phone' => 'required|string|max:20'
        ]);

        $rental->invoice()->create($validated);

        return redirect()->route('rentals.payment', $rental);
    }

    public function payment(Rental $rental)
    {
        if (!$rental->invoice) {
            return redirect()->route('rentals.invoice', $rental);
        }

        return view('rentals.payment', compact('rental'));
    }

    public function processPayment(Request $request, Rental $rental)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:credit_card,bank_transfer',
        ]);

        if ($validated['payment_method'] === 'credit_card') {
            try {
                $token = $this->paytrService->createPaymentForm($rental);
                return view('rentals.credit-card', compact('rental', 'token'));
            } catch (\Exception $e) {
                return back()->with('error', 'Ödeme işlemi başlatılamadı: ' . $e->getMessage());
            }
        }

        $rental->payment()->create([
            'payment_method' => 'bank_transfer',
            'amount' => $rental->total_amount,
            'status' => 'pending'
        ]);

        return redirect()->route('rentals.bank-transfer-info', $rental);
    }

    public function paymentCallback(Request $request)
    {
        try {
            $this->paytrService->verifyCallback($request);
            return response('OK');
        } catch (\Exception $e) {
            return response($e->getMessage(), 400);
        }
    }

    public function paymentSuccess(Request $request, Rental $rental)
    {
        return redirect()->route('rentals.my-rentals')
            ->with('success', 'Ödemeniz başarıyla tamamlandı.');
    }

    public function paymentFailed(Request $request, Rental $rental)
    {
        return redirect()->route('rentals.my-rentals')
            ->with('error', 'Ödeme işlemi başarısız oldu.');
    }

    public function bankTransferInfo(Rental $rental)
    {
        if (!$rental->payment || $rental->payment->payment_method !== 'bank_transfer') {
            return redirect()->route('rentals.payment', $rental);
        }

        return view('rentals.bank-transfer-info', compact('rental'));
    }

    public function myRentals()
    {
        $rentals = auth()->user()->rentals()
            ->with(['category', 'payment'])
            ->latest()
            ->paginate(10);

        return view('rentals.my-rentals', compact('rentals'));
    }
}