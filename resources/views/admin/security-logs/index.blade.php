@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Güvenlik Logları</h2>
        <a href="{{ route('admin.security-logs.export', request()->query()) }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            CSV Olarak İndir
        </a>
    </div>

    <div class="mb-6">
        <form action="{{ route('admin.security-logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Önem Derecesi</label>
                <select name="severity" class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="">Tümü</option>
                    <option value="info" {{ request('severity') === 'info' ? 'selected' : '' }}>Bilgi</option>
                    <option value="warning" {{ request('severity') === 'warning' ? 'selected' : '' }}>Uyarı</option>
                    <option value="danger" {{ request('severity') === 'danger' ? 'selected' : '' }}>Kritik</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Olay Tipi</label>
                <select name="event_type" class="mt-1 block w-full rounded-md border-gray-300">
                    <option value="">Tümü</option>
                    @foreach($eventTypes as $type)
                        <option value="{{ $type }}" {{ request('event_type') === $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Başlangıç Tarihi</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="mt-1 block w-full rounded-md border-gray-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Bitiş Tarihi</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="mt-1 block w-full rounded-md border-gray-300">
            </div>

            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Filtrele
                </button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Olay</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Önem</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($logs as $log)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $log->created_at->format('d.m.Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ ucfirst(str_replace('_', ' ', $log->event_type)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($log->severity === 'danger') bg-red-100 text-red-800
                                @elseif($log->severity === 'warning') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($log->severity) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $log->ip_address }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $log->user ? $log->user->name : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.security-logs.show', $log) }}" 
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
        {{ $logs->links() }}
    </div>
</div>
@endsection