@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Kiralama Detayı</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Kiralama Bilgileri</h3>
            <dl class="grid grid-cols-1 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $rental->category->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Başlangıç Tarihi</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $rental->start_date->format('d.m.Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Bitiş Tarihi</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $rental->end_date->format('d.m.Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Toplam Tutar</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($rental->total_amount, 2) }} ₺</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Durum</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($rental->status === 'active') bg-green-100 text-green-800
                            @elseif($rental->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($rental->status === 'completed') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($rental->status) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>

        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Kullanıcı ve Fatura Bilgileri</h3>
            <dl class="grid grid-cols-1 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Kullanıcı</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $rental->user->name }}</dd>
                </div>
                @if($rental->invoice)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fatura No</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $rental->invoice->invoice_number }}</dd>
                    </div>
                    @if($rental->invoice->company_name)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Firma Adı</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $rental->invoice->company_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Vergi No / Vergi Dairesi</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $rental->invoice->tax_number }} / {{ $rental->invoice->tax_office }}
                            </dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Adres</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $rental->invoice->address }}<br>
                            {{ $rental->invoice->district }}/{{ $rental->invoice->city }}
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    @if($rental->payment && $rental->payment->status === 'pending')
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h3 class="text-lg font-medium text-yellow-900 mb-2">Bekleyen Ödeme Onayı</h3>
            <p class="text-sm text-yellow-700 mb-4">
                {{ $rental->payment->payment_method === 'credit_card' ? 'Kredi Kartı' : 'Havale/EFT' }} 
                ödemesi için onay bekliyor.
            </p>
            <div class="flex space-x-4">
                <form action="{{ route('admin.rentals.approve-payment', $rental) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Ödemeyi Onayla
                    </button>
                </form>
                <form action="{{ route('admin.rentals.reject-payment', $rental) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Ödemeyi Reddet
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection