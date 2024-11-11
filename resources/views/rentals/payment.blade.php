@extends('layouts.app')

@section('content')
<div <boltAction type="file" filePath="resources/views/rentals/payment.blade.php">@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Ödeme</h1>

            <div class="mb-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Sipariş Özeti</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Kategori:</dt>
                            <dd class="text-sm font-medium">{{ $rental->category->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Başlangıç:</dt>
                            <dd class="text-sm font-medium">{{ $rental->start_date->format('d.m.Y') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Bitiş:</dt>
                            <dd class="text-sm font-medium">{{ $rental->end_date->format('d.m.Y') }}</dd>
                        </div>
                        <div class="flex justify-between pt-2 border-t">
                            <dt class="text-base font-medium">Toplam Tutar:</dt>
                            <dd class="text-base font-bold">{{ number_format($rental->total_amount, 2) }} ₺</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <form action="{{ route('rentals.process-payment', $rental) }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-1 border rounded-lg p-4 cursor-pointer" onclick="selectPaymentMethod('credit_card')">
                            <input type="radio" name="payment_method" id="credit_card" value="credit_card" class="hidden">
                            <label for="credit_card" class="flex items-center cursor-pointer">
                                <span class="payment-radio w-4 h-4 border-2 rounded-full mr-2"></span>
                                <span class="font-medium">Kredi Kartı</span>
                            </label>
                        </div>

                        <div class="flex-1 border rounded-lg p-4 cursor-pointer" onclick="selectPaymentMethod('bank_transfer')">
                            <input type="radio" name="payment_method" id="bank_transfer" value="bank_transfer" class="hidden">
                            <label for="bank_transfer" class="flex items-center cursor-pointer">
                                <span class="payment-radio w-4 h-4 border-2 rounded-full mr-2"></span>
                                <span class="font-medium">Havale/EFT</span>
                            </label>
                        </div>
                    </div>

                    <div id="credit_card_form" class="hidden space-y-4">
                        <!-- Kredi kartı formu Iyzico entegrasyonu ile eklenecek -->
                    </div>

                    <div id="bank_transfer_info" class="hidden">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-blue-900 mb-2">Banka Hesap Bilgileri</h4>
                            <p class="text-sm text-blue-700">
                                Havale/EFT seçeneğini tercih ettiğinizde, ödemeniz manuel olarak kontrol edilecek ve onaylanacaktır.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Ödemeyi Tamamla
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function selectPaymentMethod(method) {
    document.getElementById(method).checked = true;
    document.getElementById('credit_card_form').classList.toggle('hidden', method !== 'credit_card');
    document.getElementById('bank_transfer_info').classList.toggle('hidden', method !== 'bank_transfer');
    
    document.querySelectorAll('.payment-radio').forEach(radio => {
        radio.classList.remove('bg-blue-600');
    });
    document.querySelector(`label[for="${method}"] .payment-radio`).classList.add('bg-blue-600');
}
</script>
<style>
.payment-radio {
    transition: all 0.2s;
}
input[type="radio"]:checked + label .payment-radio {
    @apply border-blue-600 bg-blue-600;
}
</style>
@endpush
@endsection