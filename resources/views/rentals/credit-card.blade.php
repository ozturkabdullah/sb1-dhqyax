@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Kredi Kartı ile Ödeme</h1>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Sipariş Özeti</h3>
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-600">Kategori:</dt>
                        <dd class="text-sm font-medium">{{ $rental->category->name }}</dd>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <dt class="text-base font-medium">Toplam Tutar:</dt>
                        <dd class="text-base font-bold">{{ number_format($rental->total_amount, 2) }} ₺</dd>
                    </div>
                </dl>
            </div>

            <div id="payment-form">
                <iframe src="https://www.paytr.com/odeme/guvenli/{{ $token }}" 
                        frameborder="0" 
                        scrolling="no" 
                        style="width: 100%; height: 600px;">
                </iframe>
            </div>
        </div>
    </div>
</div>
@endsection