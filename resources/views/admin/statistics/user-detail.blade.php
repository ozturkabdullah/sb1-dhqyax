@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">{{ $user->name }} - Kullanıcı İstatistikleri</h2>
        <p class="text-gray-600">{{ $user->email }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900">Toplam Giriş</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $stats['total_logins'] }}</p>
        </div>

        <div class="bg-green-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-green-900">Toplam Kiralama</h3>
            <p class="text-3xl font-bold text-green-600">{{ $stats['total_rentals'] }}</p>
        </div>

        <div class="bg-purple-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-purple-900">Kiralama Başarı Oranı</h3>
            <p class="text-3xl font-bold text-purple-600">%{{ $stats['rental_success_rate'] }}</p>
        </div>

        <div class="bg-yellow-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-yellow-900">En Çok Kullanılan Cihaz</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ ucfirst($stats['most_used_device']) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">En Çok Ziyaret Edilen Sayfalar</h3>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sayfa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ziyaret</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($stats['most_visited_pages'] as $page)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $page['page_url'] }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $page['count'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Son Aktiviteler</h3>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarih</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivite</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cihaz</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentActivity as $activity)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $activity->created_at->format('d.m.Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ucfirst(str_replace('_', ' ', $activity->action_type)) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ucfirst($activity->device_type) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Saatlik Aktivite Dağılımı</h3>
        <div class="bg-white rounded-lg shadow p-4" style="height: 300px;">
            <canvas id="hourlyChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('hourlyChart').getContext('2d');
const data = @json($stats['activity_by_hour']);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: Object.keys(data).map(hour => `${hour}:00`),
        datasets: [{
            label: 'Aktivite Sayısı',
            data: Object.values(data),
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>
@endpush
@endsection