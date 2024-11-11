@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">{{ $category->name }} - Kiralama</h1>

            <form action="{{ route('rentals.store', $category) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Başlangıç Tarihi</label>
                    <input type="date" name="start_date" id="start_date" 
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           value="{{ old('start_date') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Bitiş Tarihi</label>
                    <input type="date" name="end_date" id="end_date"
                           min="{{ date('Y-m-d', strtotime('+8 days')) }}"
                           value="{{ old('end_date') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Ücret Hesaplama</h3>
                    <p class="text-sm text-gray-600 mb-2">Günlük ücret: {{ number_format($category->daily_rate, 2) }} ₺</p>
                    <p class="text-sm text-gray-600">Minimum kiralama süresi: 7 gün</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Devam Et
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const dailyRate = {{ $category->daily_rate }};

    function updateEndDateMin() {
        if (startDate.value) {
            const minEndDate = new Date(startDate.value);
            minEndDate.setDate(minEndDate.getDate() + 7);
            endDate.min = minEndDate.toISOString().split('T')[0];
        }
    }

    startDate.addEventListener('change', updateEndDateMin);
});
</script>
@endpush