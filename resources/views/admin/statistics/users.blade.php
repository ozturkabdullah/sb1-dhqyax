@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Kullanıcı İstatistikleri</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-blue-900">Toplam Kullanıcı</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $totalUsers }}</p>
        </div>

        <div class="bg-green-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-green-900">Günlük Aktif</h3>
            <p class="text-3xl font-bold text-green-600">{{ $activeUsers['daily'] }}</p>
        </div>

        <div class="bg-purple-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-purple-900">Haftalık Aktif</h3>
            <p class="text-3xl font-bold text-purple-600">{{ $activeUsers['weekly'] }}</p>
        </div>

        <div class="bg-yellow-50 rounded-lg p-6">
            <h3 class="text-lg font-medium text-yellow-900">Aylık Aktif</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $activeUsers['monthly'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cihaz Dağılımı</h3>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cihaz</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($deviceStats as $device => $count)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ucfirst($device) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $count }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h3 class="text-lg font-medium text-gray-900 mb-4">En Aktif Kullanıcılar</h3>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivite</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlem</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($topUsers as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $user['user']['name'] }}</div>
                                    <div class="text-sm text-gray-500">{{ $user['user']['email'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user['activity_count'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.statistics.users.show', $user['user_id']) }}" 
                                       class="text-blue-600 hover:text-blue-900">
                                        Detay
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">30 Günlük Aktivite Trendi</h3>
        <div class="bg-white rounded-lg shadow p-4" style="height: 300px;">
            <canvas id="activityChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('activityChart').getContext('2d');
const data = @json($activityTrend);

new Chart(ctx, {
    type: 'line',
    data: {
        labels: Object.keys(data),
        datasets: [{
            label: 'Aktif Kullanıcı',
            data: Object.values(data),
            borderColor: 'rgb(59, 130, 246)',
            tension: 0.1
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