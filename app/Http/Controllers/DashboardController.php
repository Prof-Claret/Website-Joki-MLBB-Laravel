<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $role = $user->role?->slug;

        return match ($role) {
            'developer' => $this->developerDashboard($user),
            'admin' => $this->adminDashboard($user),
            'worker' => $this->workerDashboard($user),
            'user' => $this->userDashboard($user),
            default => view('dashboard'),
        };
    }

    private function developerDashboard($user): View
    {
        $stats = [
            'total_games' => \App\Models\Game::count(),
            'total_ranks' => \App\Models\Rank::count(),
            'total_services' => \App\Models\Service::count(),
            'total_orders' => \App\Models\Order::count(),
        ];

        return view('dashboards.developer', compact('user', 'stats'));
    }

    private function adminDashboard($user): View
    {
        $stats = [
            'pending_orders' => \App\Models\Order::where('status', 'pending')->count(),
            'active_orders' => \App\Models\Order::where('status', 'in_progress')->count(),
            'completed_orders' => \App\Models\Order::where('status', 'completed')->count(),
            'total_revenue' => \App\Models\Transaction::where('status', 'completed')->sum('amount'),
        ];

        $recentOrders = \App\Models\Order::with(['user', 'worker', 'game'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboards.admin', compact('user', 'stats', 'recentOrders'));
    }

    private function workerDashboard($user): View
    {
        $stats = [
            'assigned_orders' => $user->assignedOrders()->where('status', 'in_progress')->count(),
            'completed_orders' => $user->assignedOrders()->where('status', 'completed')->count(),
            'total_earnings' => $user->workerTransactions()->where('status', 'completed')->sum('amount'),
            'pending_withdrawal' => $user->withdrawals()->where('status', 'pending')->sum('amount'),
        ];

        $activeOrders = $user->assignedOrders()
            ->where('status', 'in_progress')
            ->with(['game', 'service', 'user'])
            ->latest()
            ->get();

        return view('dashboards.worker', compact('user', 'stats', 'activeOrders'));
    }

    private function userDashboard($user): View
    {
        $stats = [
            'total_orders' => $user->orders()->count(),
            'pending_orders' => $user->orders()->where('status', 'pending')->count(),
            'in_progress' => $user->orders()->where('status', 'in_progress')->count(),
            'completed_orders' => $user->orders()->where('status', 'completed')->count(),
        ];

        $recentOrders = $user->orders()
            ->with(['game', 'service', 'worker'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboards.user', compact('user', 'stats', 'recentOrders'));
    }
}
