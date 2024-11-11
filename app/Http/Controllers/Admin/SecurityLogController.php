<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\Request;

class SecurityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = SecurityLog::with('user')->recent();

        // Filtreleme
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20);
        $eventTypes = SecurityLog::distinct()->pluck('event_type');

        return view('admin.security-logs.index', compact('logs', 'eventTypes'));
    }

    public function show(SecurityLog $log)
    {
        return view('admin.security-logs.show', compact('log'));
    }

    public function export(Request $request)
    {
        $logs = SecurityLog::with('user')
            ->when($request->filled('severity'), function ($query) use ($request) {
                $query->where('severity', $request->severity);
            })
            ->when($request->filled('event_type'), function ($query) use ($request) {
                $query->where('event_type', $request->event_type);
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->get();

        return response()->streamDownload(function () use ($logs) {
            $output = fopen('php://output', 'w');
            
            // CSV başlıkları
            fputcsv($output, [
                'ID', 'Olay Tipi', 'Önem Derecesi', 'IP Adresi',
                'Kullanıcı', 'Detaylar', 'Tarih'
            ]);

            foreach ($logs as $log) {
                fputcsv($output, [
                    $log->id,
                    $log->event_type,
                    $log->severity,
                    $log->ip_address,
                    $log->user ? $log->user->name : '-',
                    json_encode($log->details, JSON_UNESCAPED_UNICODE),
                    $log->created_at->format('d.m.Y H:i:s')
                ]);
            }

            fclose($output);
        }, 'security-logs.csv');
    }
}