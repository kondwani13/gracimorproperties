<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Bill;
use App\Models\Booking;
use App\Models\Complaint;
use App\Models\Employee;
use App\Models\EmployeeLeaveRequest;
use App\Models\MaintenanceCost;
use App\Models\Payment;
use App\Models\RentPayment;
use App\Models\Review;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year

        // Calculate date range
        $startDate = match($period) {
            'day' => Carbon::today(),
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };

        // Statistics
        $stats = [
            'total_apartments' => Apartment::count(),
            'available_apartments' => Apartment::available()->count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::pending()->count(),
            'confirmed_bookings' => Booking::confirmed()->count(),
            'completed_bookings' => Booking::completed()->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
        ];

        $dashboardTotalRevenue = Booking::whereHas('payment', function ($query) {
            $query->where('status', 'completed');
        })->sum('total_amount') + RentPayment::paid()->sum('amount');

        // Statistics
        $stats = [
            'total_apartments' => Apartment::count(),
            'available_apartments' => Apartment::available()->count(),
            'total_users' => User::where('role', 'user')->count(),
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::pending()->count(),
            'confirmed_bookings' => Booking::confirmed()->count(),
            'completed_bookings' => Booking::completed()->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'total_revenue' => $dashboardTotalRevenue,
            'period_revenue' => Booking::whereHas('payment', function ($query) {
                $query->where('status', 'completed');
            })
                ->where('created_at', '>=', $startDate)
                ->sum('total_amount')
                + RentPayment::paid()
                    ->where('payment_date', '>=', $startDate)
                    ->sum('amount'),
            'pending_reviews' => Review::pending()->count(),

            // New KPIs
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('status', 'active')->count(),
            'monthly_rent_collected' => RentPayment::where('month', now()->format('Y-m'))
                ->where('status', 'paid')->sum('amount'),
            'pending_rent' => max(0, Tenant::where('status', 'active')->sum('rent_amount')
                - RentPayment::where('month', now()->format('Y-m'))->where('status', 'paid')->sum('amount')),
            'maintenance_costs_month' => MaintenanceCost::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)->sum('amount'),
            'open_complaints' => Complaint::whereIn('status', ['open', 'in_progress'])->count(),
            'employee_count' => Employee::where('status', 'active')->count(),
            'pending_leaves' => EmployeeLeaveRequest::where('status', 'pending')->count(),
            'overdue_bills' => Bill::where('status', 'overdue')->count(),
            'total_bills_month' => Bill::whereMonth('due_date', now()->month)
                ->whereYear('due_date', now()->year)->sum('amount'),
        ];

        // Recent bookings
        $recentBookings = Booking::with(['user', 'apartment', 'payment'])
            ->latest()
            ->limit(10)
            ->get();

        // Revenue chart data (last 12 months)
        $revenueData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $bookingRevenue = Booking::whereHas('payment', function ($query) {
                $query->where('status', 'completed');
            })
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount');

            $rentRevenue = RentPayment::paid()
                ->whereYear('payment_date', $month->year)
                ->whereMonth('payment_date', $month->month)
                ->sum('amount');
            
            $revenueData[] = [
                'month' => $month->format('M Y'),
                'revenue' => $bookingRevenue + $rentRevenue,
            ];
        }

        // Bookings by status
        $bookingsByStatus = [
            'pending' => Booking::pending()->count(),
            'confirmed' => Booking::confirmed()->count(),
            'completed' => Booking::completed()->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        // Top performing apartments
        $topApartments = Apartment::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentBookings',
            'revenueData',
            'bookingsByStatus',
            'topApartments',
            'period'
        ));
    }
}
