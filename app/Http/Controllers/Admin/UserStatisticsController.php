<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserStatistic;
use App\Services\UserStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserStatisticsController extends Controller
{
    protected $statisticsService;

    public function __construct(UserStatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function index()
    {
        $totalUsers = User::count();
        $activeUsers = $this->getActiveUsers();
        $deviceStats = $this->getDeviceStats();
        $topUsers = $this->getTopUsers();
        $activityTrend = $this->getActivityTrend();

        return view('admin.statistics.users', compact(
            'totalUsers',
            'activeUsers',
            'deviceStats',
            'topUsers',
            'activityTrend'
        ));
    }

    public function show(User $user)
    {
        $stats = $this->statisticsService->getUserStats($user->id);
        $recentActivity = UserStatistic::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

        return view('admin.statistics.user-detail', compact('user', 'stats', 'recentActivity'));
    }

    protected function getActiveUsers(): array
    {
        return [
            'daily' => $this->getUniqueUsers('day'),
            'weekly' => $this->getUniqueUsers('week'),
            'monthly' => $this->getUniqueUsers('month')
        ];
    }

    protected function getUniqueUsers(string $period): int
    {
        return UserStatistic::where('created_at', '>=', now()->sub($period))
            ->distinct('user_id')
            ->count('user_id');
    }

    protected function getDeviceStats(): array
    {
        return UserStatistic::select('device_type')
            ->selectRaw('COUNT(DISTINCT user_id) as user_count')
            ->groupBy('device_type')
            ->get()
            ->pluck('user_count', 'device_type')
            ->toArray();
    }

    protected function getTopUsers(): array
    {
        return UserStatistic::select('user_id')
            ->selectRaw('COUNT(*) as activity_count')
            ->with('user:id,name,email')
            ->groupBy('user_id')
            ->orderByDesc('activity_count')
            ->limit(10)
            ->get()
            ->toArray();
    }

    protected function getActivityTrend(): array
    {
        return UserStatistic::select(DB::raw('DATE(created_at) as date'))
            ->selectRaw('COUNT(DISTINCT user_id) as user_count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('user_count', 'date')
            ->toArray();
    }
}