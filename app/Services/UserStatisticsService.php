<?php

namespace App\Services;

use App\Models\UserStatistic;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Cache;

class UserStatisticsService
{
    protected $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    /**
     * Kullanıcı aktivitesini kaydet
     */
    public function logActivity($user, string $actionType, array $additionalData = []): void
    {
        UserStatistic::create([
            'user_id' => $user->id,
            'action_type' => $actionType,
            'page_url' => request()->fullUrl(),
            'device_type' => $this->getDeviceType(),
            'browser' => $this->agent->browser(),
            'platform' => $this->agent->platform(),
            'ip_address' => request()->ip(),
            'additional_data' => $additionalData
        ]);

        // Önbelleği temizle
        $this->clearCache($user->id);
    }

    /**
     * Kullanıcı istatistiklerini getir
     */
    public function getUserStats($userId): array
    {
        return Cache::remember("user_stats.{$userId}", now()->addHour(), function () use ($userId) {
            $stats = UserStatistic::where('user_id', $userId);

            return [
                'total_logins' => $stats->where('action_type', 'login')->count(),
                'total_rentals' => $stats->where('action_type', 'rental_created')->count(),
                'last_login' => $stats->where('action_type', 'login')->latest()->first()?->created_at,
                'most_used_device' => $this->getMostUsedDevice($userId),
                'most_visited_pages' => $this->getMostVisitedPages($userId),
                'activity_by_hour' => $this->getActivityByHour($userId),
                'rental_success_rate' => $this->getRentalSuccessRate($userId)
            ];
        });
    }

    /**
     * En çok kullanılan cihaz tipini getir
     */
    protected function getMostUsedDevice($userId): string
    {
        return UserStatistic::where('user_id', $userId)
            ->select('device_type')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('device_type')
            ->orderByDesc('count')
            ->first()?->device_type ?? 'unknown';
    }

    /**
     * En çok ziyaret edilen sayfaları getir
     */
    protected function getMostVisitedPages($userId): array
    {
        return UserStatistic::where('user_id', $userId)
            ->select('page_url')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('page_url')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->toArray();
    }

    /**
     * Saatlik aktivite dağılımını getir
     */
    protected function getActivityByHour($userId): array
    {
        return UserStatistic::where('user_id', $userId)
            ->selectRaw('HOUR(created_at) as hour')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();
    }

    /**
     * Kiralama başarı oranını hesapla
     */
    protected function getRentalSuccessRate($userId): float
    {
        $totalRentals = UserStatistic::where('user_id', $userId)
            ->where('action_type', 'rental_created')
            ->count();

        if ($totalRentals === 0) {
            return 0;
        }

        $successfulRentals = UserStatistic::where('user_id', $userId)
            ->where('action_type', 'rental_completed')
            ->count();

        return round(($successfulRentals / $totalRentals) * 100, 2);
    }

    /**
     * Cihaz tipini belirle
     */
    protected function getDeviceType(): string
    {
        if ($this->agent->isTablet()) {
            return 'tablet';
        } elseif ($this->agent->isMobile()) {
            return 'mobile';
        }
        return 'desktop';
    }

    /**
     * Önbelleği temizle
     */
    protected function clearCache($userId): void
    {
        Cache::forget("user_stats.{$userId}");
    }
}