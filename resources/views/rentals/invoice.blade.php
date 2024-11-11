@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Fatura Bilgileri</h1>

            <form action="{{ route('rentals.store-invoice', $rental) }}" method="POST">
                @csrf

                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="is_company" name="is_company" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_company" class="ml-2 block text-sm text-gray-700">
                            Kurumsal fatura istiyorum
                        </label>
                    </div>

                    <div id="company_fields" class="hidden space-y-4">
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700">Firma Adı</label>
                            <input type="text" name="company_name" id="company_name" value="{{ old('company_name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="tax_number" class="block text-sm font-medium text-gray-700">Vergi Numarası</label>
                            <input type="text" name="tax_number" id="tax_number" value="{{ old('tax_number') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="tax_office" class="block text-sm font-medium text-gray-700">Vergi Dairesi</label>
                            <input type="text" name="tax_office" id="tax_office" value="{{ old('tax_office') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Adres</label>
                        <textarea name="address" id="address" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">İl</label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <div>
                            <label for="district" class="block text-sm font-medium text-gray-700">İlçe</label>
                            <input type="text" name="district" id="district" value="{{ old('district') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Telefon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Devam Et
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isCompanyCheckbox = document.getElementById('is_company');
    const companyFields = document.getElementById('company_fields');

    isCompanyCheckbox.addEventListener('change', function() {
        companyFields.classList.toggle('hidden', !this.checked);
    });
});
</script>
@endpush
@endsection