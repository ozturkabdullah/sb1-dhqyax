@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Güvenlik Log Detayı</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Temel Bilgiler</h3>
            
            <dl class="grid grid-cols-1 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Olay Tipi</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ ucfirst(str_replace('_', ' ', $log->event_type)) }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Önem Derecesi</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($log->severity === 'danger') bg-red-100 text-red-800
                            @elseif($log->severity === 'warning') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($log->severity) }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Tarih</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $log->created_at->format('d.m.Y H:i:s') }}
                    </dd>
                </div>
            </dl>
        </div>

        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Teknik Detaylar</h3>
            
            <dl class="grid grid-cols-1 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">IP Adresi</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $log->ip_address }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Kullanıcı</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $log->user ? $log->user->name : '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $log->user_agent }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if($log->details)
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Olay Detayları</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <pre class="text-sm text-gray-900 whitespace-pre-wrap">{{ json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    @endif
</div>
@endsection