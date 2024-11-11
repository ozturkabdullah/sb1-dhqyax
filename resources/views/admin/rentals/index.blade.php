@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Kiralamalar</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlangıç</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bitiş</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ödeme</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durum</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($rentals as $rental)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $rental->user->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $rental->category->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $rental->start_date->format('d.m.Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $rental->end_date->format('d.m.Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ number_format($rental->total_amount, 2) }} ₺
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($rental->payment)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($rental->payment->status === 'completed') bg-green-100 text-green-800
                                @elseif($rental->payment->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $rental->payment->payment_method === 'credit_card' ? 'Kredi Kartı' : 'Havale/EFT' }}
                                ({{ ucfirst($rental->payment->status) }})
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($rental->status === 'active') bg-green-100 text-green-800
                            @elseif($rental->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($rental->status === 'completed') bg-blue-100 text-blue-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($rental->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.rentals.show', $rental) }}" 
                           class="text-blue-600 hover:text-blue-900">
                            Detay
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $rentals->links() }}
    </div>
</div>
@endsection