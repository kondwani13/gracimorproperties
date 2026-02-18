<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Bill;
use App\Models\Booking;
use App\Models\MaintenanceCost;
use App\Models\Payment;
use App\Models\User;
use App\Models\RentPayment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Show reports dashboard
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Revenue report
     */
    public function revenue(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subMonths(12)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $bookingRevenueData = Booking::whereHas('payment', function ($query) {
            $query->where('status', 'completed');
        })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($booking) {
                return Carbon::parse($booking->created_at)->format('Y-m');
            })
            ->map(function ($items, $key) {
                return $items->sum('total_amount');
            });

        $rentRevenueData = RentPayment::paid()
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($rentPayment) {
                return Carbon::parse($rentPayment->payment_date)->format('Y-m');
            })
            ->map(function ($items, $key) {
                return $items->sum('amount');
            });

        // Combine booking and rent revenue data
        $combinedRevenue = $bookingRevenueData->mergeRecursive($rentRevenueData);

        $bookingsInRange = Booking::whereBetween('created_at', [$startDate, $endDate])->get();

        $bookingsSum = Booking::whereHas('payment', function ($query) {
            $query->where('status', 'completed');
        })->sum('total_amount');

        $rentPaymentsSum = RentPayment::paid()->sum('amount');
        
        $totalRevenueAllTime = $bookingsSum + $rentPaymentsSum;

        // Stats for summary cards
        $stats = [
            'total_revenue' => $totalRevenueAllTime,
            'paid_bookings' => $bookingsInRange->where('payment_status', 'paid')->count(),
            'average_booking' => $bookingsInRange->where('payment_status', 'paid')->avg('total_amount') ?? 0,
            'total_refunds' => Payment::where('status', 'refunded')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('amount'),
        ];

        // Revenue chart data - array of ['date' => ..., 'amount' => ...]
        $revenueData = $combinedRevenue->map(function ($amounts, $month) {
            return [
                'date' => $month . '-01',
                'amount' => collect($amounts)->sum(),
            ];
        })->sortKeys()->values()->toArray();

        // Revenue by apartment
        $apartmentColumns = [
            'apartments.id', 'apartments.title', 'apartments.slug', 'apartments.description', 'apartments.address', 'apartments.city', 
            'apartments.state', 'apartments.country', 'apartments.postal_code', 'apartments.latitude', 'apartments.longitude', 
            'apartments.price_per_night', 'apartments.cleaning_fee', 'apartments.service_fee', 'apartments.bedrooms', 
            'apartments.bathrooms', 'apartments.max_guests', 'apartments.size_sqft', 'apartments.property_type', 
            'apartments.amenities', 'apartments.house_rules', 'apartments.main_image', 'apartments.minimum_stay', 
            'apartments.maximum_stay', 'apartments.check_in_time', 'apartments.check_out_time', 'apartments.is_available', 
            'apartments.is_featured', 'apartments.rating', 'apartments.total_reviews', 'apartments.views_count', 
            'apartments.booking_count', 'apartments.created_at', 'apartments.updated_at', 'apartments.deleted_at'
        ];
        $subQuery = Apartment::select($apartmentColumns)
            ->withCount(['bookings' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('payment', function ($subQuery) {
                    $subQuery->where('payments.status', 'completed');
                })->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['bookings' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('payment', function ($subQuery) {
                    $subQuery->where('payments.status', 'completed');
                })->whereBetween('created_at', [$startDate, $endDate]);
            }], 'total_amount')
            ->withSum(['rentPayments' => function ($query) use ($startDate, $endDate) {
                $query->where('rent_payments.status', 'paid')->whereBetween('payment_date', [$startDate, $endDate]);
            }], 'amount');

        $apartmentRevenue = DB::query()->fromSub($subQuery, 'apartments_with_revenue')
            ->select(
                'apartments_with_revenue.*',
                DB::raw('(apartments_with_revenue.bookings_sum_total_amount + apartments_with_revenue.rent_payments_sum_amount) as combined_revenue')
            )
            ->where('combined_revenue', '>', 0)
            ->orderByDesc('combined_revenue')
            ->limit(10);
        
        $apartmentRevenue = $apartmentRevenue->get()
            ->map(function ($apartment) {
                $bookingRevenue = $apartment->bookings_sum_total_amount ?? 0;
                $rentRevenue = $apartment->rent_payments_sum_amount ?? 0;
                $totalApartmentRevenue = $bookingRevenue + $rentRevenue;

                return [
                    'apartment_name' => $apartment->title,
                    'bookings_count' => $apartment->bookings_count,
                    'total_revenue' => $totalApartmentRevenue,
                    'avg_revenue' => $apartment->bookings_count > 0
                        ? ($bookingRevenue / $apartment->bookings_count) : 0,
                ];
            })->toArray();

        return view('admin.reports.revenue', compact('stats', 'revenueData', 'apartmentRevenue', 'startDate', 'endDate'));
    }

    /**
     * Booking report
     */
    public function bookings(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subMonths(12)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('payment', function ($query) {
                $query->where('status', 'completed');
            })
            ->get();

        $stats = [
            'total_bookings' => $bookings->count(),
            'confirmed_bookings' => $bookings->where('status', 'confirmed')->count(),
            'pending_bookings' => $bookings->where('status', 'pending')->count(),
            'cancelled_bookings' => $bookings->where('status', 'cancelled')->count(),
            'completed_bookings' => $bookings->where('status', 'completed')->count(),
            'avg_booking_value' => $bookings->avg('total_amount') ?? 0,
            'avg_nights' => $bookings->avg('number_of_nights') ?? 0,
            'avg_guests' => $bookings->avg('number_of_guests') ?? 0,
            'total_nights' => $bookings->sum('number_of_nights'),
        ];

        // All-time stats for comparison with Dashboard
        $allTimeStats = [
            'total_bookings' => Booking::count(),
            'confirmed_bookings' => Booking::confirmed()->count(),
            'pending_bookings' => Booking::pending()->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'completed_bookings' => Booking::completed()->count(),
        ];

        // Bookings by month
        $bookingsByMonth = $bookings->groupBy(function ($booking) {
            return Carbon::parse($booking->created_at)->format('Y-m');
        })->map(function ($items) {
            return $items->count();
        });

        // Top apartments - with bookings_count, total_nights, and revenue
        $apartmentColumns = [
            'apartments.id', 'apartments.title', 'apartments.slug', 'apartments.description', 'apartments.address', 'apartments.city', 
            'apartments.state', 'apartments.country', 'apartments.postal_code', 'apartments.latitude', 'apartments.longitude', 
            'apartments.price_per_night', 'apartments.cleaning_fee', 'apartments.service_fee', 'apartments.bedrooms', 
            'apartments.bathrooms', 'apartments.max_guests', 'apartments.size_sqft', 'apartments.property_type', 
            'apartments.amenities', 'apartments.house_rules', 'apartments.main_image', 'apartments.minimum_stay', 
            'apartments.maximum_stay', 'apartments.check_in_time', 'apartments.check_out_time', 'apartments.is_available', 
            'apartments.is_featured', 'apartments.rating', 'apartments.total_reviews', 'apartments.views_count', 
            'apartments.booking_count', 'apartments.created_at', 'apartments.updated_at', 'apartments.deleted_at'
        ];
        $topApartments = Apartment::withCount(['bookings' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                  ->whereHas('payment', function ($subQuery) {
                      $subQuery->where('status', 'completed');
                  });
        }])->withSum(['bookings' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate])
                  ->whereHas('payment', function ($subQuery) {
                      $subQuery->where('status', 'completed');
                  });
        }], 'number_of_nights')
            ->withSum(['bookings' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                  ->whereHas('payment', function ($subQuery) {
                      $subQuery->where('status', 'completed');
                  });
            }], 'total_amount')
            ->groupBy($apartmentColumns)
            ->having('bookings_count', '>', 0)
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($apartment) {
                return [
                    'name' => $apartment->title,
                    'bookings_count' => $apartment->bookings_count,
                    'total_nights' => $apartment->bookings_sum_number_of_nights ?? 0,
                    'revenue' => $apartment->bookings_sum_total_amount ?? 0,
                ];
            })->toArray();

        return view('admin.reports.bookings', compact('stats', 'allTimeStats', 'bookingsByMonth', 'topApartments', 'startDate', 'endDate'));
    }

    /**
     * User report
     */
    public function users(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subMonths(12)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'active_users' => User::where('role', 'user')
                ->where('is_active', true)
                ->count(),
            'new_users_month' => User::where('role', 'user')
                ->where('created_at', '>=', Carbon::now()->startOfMonth())
                ->count(),
            'total_admins' => User::where('role', 'admin')->count(),
        ];

        // User growth chart - array of ['month' => ..., 'count' => ...]
        $userGrowth = User::where('role', 'user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($user) {
                return Carbon::parse($user->created_at)->format('Y-m');
            })
            ->map(function ($items, $key) {
                return [
                    'month' => $key . '-01',
                    'count' => $items->count(),
                ];
            })->sortKeys()->values()->toArray();

        // Top users by bookings & spending
        $userColumns = [
            'users.id', 'users.name', 'users.email', 'users.email_verified_at', 'users.password', 'users.google_id', 'users.avatar',
            'users.phone', 'users.address', 'users.city', 'users.state', 'users.country', 'users.postal_code', 'users.role', 'users.is_active',
            'users.remember_token', 'users.created_at', 'users.updated_at', 'users.deleted_at'
        ];
        $topUsers = User::where('role', 'user')
            ->withCount('bookings')
            ->withSum(['bookings' => function ($query) {
                $query->whereHas('payment', function ($subQuery) {
                    $subQuery->where('status', 'completed');
                });
            }], 'total_amount')
            ->groupBy($userColumns)
            ->having('bookings_count', '>', 0)
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'bookings_count' => $user->bookings_count,
                    'total_spent' => $user->bookings_sum_total_amount ?? 0,
                    'joined_date' => $user->created_at->format('Y-m-d'),
                ];
            })->toArray();

        return view('admin.reports.users', compact('stats', 'userGrowth', 'topUsers', 'startDate', 'endDate'));
    }

    /**
     * Occupancy report
     */
    public function occupancy(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subMonths(6)->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $totalDays = $start->diffInDays($end) ?: 1;

        // Get all apartments
        $apartments = Apartment::all();

        // Get confirmed/completed bookings in the date range
        $bookings = Booking::whereIn('status', ['confirmed', 'completed'])
            ->where('check_in', '<=', $endDate)
            ->where('check_out', '>=', $startDate)
            ->get();

        // Calculate per-apartment occupancy
        $totalNightsBooked = 0;
        $totalAvailableNights = $apartments->count() * $totalDays;

        $apartmentOccupancy = $apartments->map(function ($apartment) use ($bookings, $start, $end, $totalDays, &$totalNightsBooked) {
            $aptBookings = $bookings->where('apartment_id', $apartment->id);
            $bookedNights = 0;

            foreach ($aptBookings as $booking) {
                $bookStart = Carbon::parse($booking->check_in)->max($start);
                $bookEnd = Carbon::parse($booking->check_out)->min($end);
                $nights = $bookStart->diffInDays($bookEnd);
                $bookedNights += max(0, $nights);
            }

            $totalNightsBooked += $bookedNights;
            $occupancyRate = $totalDays > 0 ? min(($bookedNights / $totalDays) * 100, 100) : 0;

            return [
                'apartment_name' => $apartment->title,
                'total_nights' => $totalDays,
                'booked_nights' => $bookedNights,
                'occupancy_rate' => round($occupancyRate, 1),
            ];
        })->sortByDesc('occupancy_rate')->values()->toArray();

        $avgOccupancy = $totalAvailableNights > 0
            ? round(($totalNightsBooked / $totalAvailableNights) * 100, 1) : 0;

        // Monthly occupancy trend
        $monthlyOccupancy = [];
        $current = $start->copy()->startOfMonth();
        while ($current->lte($end)) {
            $monthStart = $current->copy()->max($start);
            $monthEnd = $current->copy()->endOfMonth()->min($end);
            $daysInPeriod = $monthStart->diffInDays($monthEnd) ?: 1;

            $monthBookings = $bookings->filter(function ($booking) use ($monthStart, $monthEnd) {
                return Carbon::parse($booking->check_in)->lte($monthEnd)
                    && Carbon::parse($booking->check_out)->gte($monthStart);
            });

            $monthBooked = 0;
            foreach ($monthBookings as $booking) {
                $bookStart = Carbon::parse($booking->check_in)->max($monthStart);
                $bookEnd = Carbon::parse($booking->check_out)->min($monthEnd);
                $monthBooked += max(0, $bookStart->diffInDays($bookEnd));
            }

            $maxPossible = $apartments->count() * $daysInPeriod;
            $rate = $maxPossible > 0 ? round(($monthBooked / $maxPossible) * 100, 1) : 0;

            $monthlyOccupancy[] = [
                'month' => $current->format('Y-m-01'),
                'occupancy_rate' => min($rate, 100),
            ];

            $current->addMonth();
        }

        // Find peak month
        $peakMonth = collect($monthlyOccupancy)->sortByDesc('occupancy_rate')->first();
        $peakSeason = $peakMonth ? Carbon::parse($peakMonth['month'])->format('M Y') : 'N/A';

        $stats = [
            'avg_occupancy' => $avgOccupancy,
            'total_nights_booked' => $totalNightsBooked,
            'total_available_nights' => $totalAvailableNights,
            'peak_season' => $peakSeason,
        ];

        return view('admin.reports.occupancy', compact('stats', 'monthlyOccupancy', 'apartmentOccupancy', 'startDate', 'endDate'));
    }

    /**
     * Maintenance cost report
     */
    public function maintenance(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $costs = MaintenanceCost::with('apartment')->whereBetween('date', [$startDate, $endDate])->get();

        $stats = [
            'total_cost' => $costs->sum('amount'),
            'pending' => $costs->where('status', 'pending')->count(),
            'in_progress' => $costs->where('status', 'in_progress')->count(),
            'completed' => $costs->where('status', 'completed')->count(),
        ];

        $byCategory = $costs->groupBy('category')->map(function ($items) {
            return [
                'count' => $items->count(),
                'total' => $items->sum('amount'),
            ];
        });

        $byMonth = $costs->groupBy(function ($cost) {
            return Carbon::parse($cost->date)->format('Y-m');
        })->map(function ($items) {
            return $items->sum('amount');
        })->sortKeys();

        $byApartment = $costs->groupBy(function ($cost) {
            return $cost->apartment ? $cost->apartment->title : 'General/Building';
        })->map(function ($items) {
            return [
                'count' => $items->count(),
                'total' => $items->sum('amount'),
            ];
        })->sortByDesc('total');

        return view('admin.reports.maintenance', compact('stats', 'byCategory', 'byMonth', 'byApartment', 'startDate', 'endDate'));
    }

    /**
     * Bill deposit report
     */
    public function bills(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $bills = Bill::with('apartment')->whereBetween('due_date', [$startDate, $endDate])->get();

        $stats = [
            'total_amount' => $bills->sum('amount'),
            'total_paid' => $bills->where('status', 'paid')->sum('amount'),
            'total_pending' => $bills->where('status', 'pending')->sum('amount'),
            'total_overdue' => $bills->where('status', 'overdue')->sum('amount'),
        ];

        $byType = $bills->groupBy('type')->map(function ($items) {
            return [
                'count' => $items->count(),
                'total' => $items->sum('amount'),
                'paid' => $items->where('status', 'paid')->sum('amount'),
            ];
        });

        $byMonth = $bills->groupBy(function ($bill) {
            return Carbon::parse($bill->due_date)->format('Y-m');
        })->map(function ($items) {
            return [
                'total' => $items->sum('amount'),
                'paid' => $items->where('status', 'paid')->sum('amount'),
            ];
        })->sortKeys();

        return view('admin.reports.bills', compact('stats', 'byType', 'byMonth', 'startDate', 'endDate'));
    }
}