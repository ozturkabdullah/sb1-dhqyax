@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Havale/EFT Bilgileri</h1>

            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <h3 class="text-lg font-medium text-blue-900 mb-2">Sipariş Özeti</h3>
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm text-blue-700">Sipariş No:</dt>
                        <dd class="text-sm font-medium text-blue-900">{{ $rental->id }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-blue-700">Toplam Tutar:</dt>
                        <dd class="text-sm font-medium text-blue-900">{{ number_format($rental->total_amount, 2) }} ₺</dd>
                    </div>
                </dl>
            </div>

            <div class="space-y-6">
                <div class="border rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 mb-2">Banka Hesap Bilgileri</h4>
                    <dl class="space-y-2 text-sm">
                        <div>
                            <dt class="text-gray-600">Banka:</dt>
                            <dd class="font-medium">{{ config('bank.name') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-600">Hesap Sahibi:</dt>
                            <dd class="font-medium">{{ config('bank.account_holder') }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-600">IBAN:</dt>
                            <dd class="font-medium">{{ config('bank.iban') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-yellow-50 rounded-lg p-4">
                    <h4 class="font-medium text-yellow-900 mb-2">Önemli Bilgiler</h4>
                    <ul class="list-disc list-inside text-sm text-yellow-700 space-y-1">
                        <li>Havale/EFT açıklamasına sipariş numaranızı ({{ $rental->id }}) yazmayı unutmayın.</li>
                        <li>Ödemeniz kontrol edildikten sonra kiralama işleminiz aktifleştirilecektir.</li>
                        <li>Ödeme durumunu "Kiralamalarım" sayfasından takip edebilirsiniz.</li>
                    </ul>
                </div>
            </div>

            <div class="mt-8">
                <a href="{{ route('rentals.my-rentals') }}" 
                   class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Kiralamalarıma Git
                </a>
            </div>
        </div>
    </div>
</div>
@endsection