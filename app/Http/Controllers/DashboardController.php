<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\UserCustomer;
use Inertia\Inertia;
use Devrabiul\ToastMagic\Facades\ToastMagic;


class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $organization = $user->organization;
        // dd($organization->toArray());
        $organizationName = optional($user->organization)->organization_name;
        $showOrganizationPopup = $user->hash_organization && is_null($organizationName);
        $totalOrders = \App\Models\Order::where('user_id', $user->id)->count();
        return Inertia::render('Dashboard', [
            'organizationName' => $organizationName,
            'organization' => $organization,
            'showOrganizationPopup' => $showOrganizationPopup,
            'totalOrders' => $totalOrders,
        ]);
    }

    public function closedOrdersPage()
    {
        $deliveredOrders = Order::with(['customer'])
            ->where('status', 'delivered')
            ->get();

        return Inertia::render('Closedorders', [
            'deliveredOrders' => $deliveredOrders
        ]);
    }
    public function viewClosedOrders()
    {
        $closedOrders = Order::with('customer')
            ->where('status', 'closed')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'total_amount' => $order->total_amount,
                    'advance_paid' => $order->advance_paid,
                    'delivery_date' => $order->delivery_date,
                    // customer nested info
                    'customer' => [
                        'name' => optional($order->customer)->name,
                        'email' => optional($order->customer)->email,
                        'phone' => optional($order->customer)->phone,
                        'country_code' => optional($order->customer)->country_code,
                        'gender' => optional($order->customer)->gender,
                        'address' => optional($order->customer)->address,
                    ],
                ];
            });

        return Inertia::render('ViewClosedOrder', [
            'closedOrders' => $closedOrders,
        ]);
    }

    public function close(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::findOrFail($request->order_id);
        $order->update([
            'status' => 'closed',
        ]);

        ToastMagic::success('Order closed successfully!');

        return back(); // 👈 redirects to the previous page automatically
    }


    public function getChartData(Request $request)
    {
        $type = $request->get('type', 'order'); // order, customer, revenue
        $range = $request->get('range', 'Yesterday');

        // Define date range based on $range
        switch ($range) {
            case 'Yesterday':
                $startDate = Carbon::yesterday()->startOfDay();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case '7 Days Ago':
                $startDate = Carbon::now()->subDays(7)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case '1 Month Ago':
                $startDate = Carbon::now()->subMonth()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            case '1 Year Ago':
                $startDate = Carbon::now()->subYear()->startOfDay();
                $endDate = Carbon::now()->endOfDay();
                break;
            default:
                $startDate = Carbon::now()->subDays(7)->startOfDay();
                $endDate = Carbon::now()->endOfDay();
        }

        // Initialize labels and data arrays
        $labels = [];
        $data = [];
        $chartRows = [];

        if ($type === 'order') {
            $orders = Order::with('customer')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            $groupedOrders = $orders->groupBy(function ($order) {
                return $order->created_at->toDateString();
            });

            foreach ($groupedOrders as $date => $dailyOrders) {
                $labels[] = $date;
                $data[] = $dailyOrders->count();

                // Add full details for export
                foreach ($dailyOrders as $order) {
                    $chartRows[] = [
                        'date' => $date,
                        'orderNumber' => $order->order_number,
                        'customerName' => optional($order->customer)->name,
                        'status' => $order->status,
                    ];
                }
            }
        } elseif ($type === 'customer') {
            $customers = \App\Models\Customer::whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            $grouped = $customers->groupBy(function ($customer) {
                return $customer->created_at->toDateString();
            });

            foreach ($grouped as $date => $dailyCustomers) {
                $labels[] = $date;
                $data[] = $dailyCustomers->count();

                foreach ($dailyCustomers as $customer) {
                    $chartRows[] = [
                        'date' => $date,
                        'name' => $customer->name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                    ];
                }
            }
        } else if ($type === 'revenue') {
            // Correct: earnings = SUM of advance_paid (received amount)
            $revenues = Order::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total_revenue, SUM(advance_paid) as total_earning')
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            foreach ($revenues as $revenue) {
                $labels[] = $revenue->date;
                $data[] = round($revenue->total_revenue, 2);

                $chartRows[] = [
                    'date' => $revenue->date,
                    'revenue' => round($revenue->total_revenue, 2),
                    'earning' => round($revenue->total_earning, 2),
                    'pending' => round($revenue->total_revenue - $revenue->total_earning, 2),
                ];
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'chartRows' => $chartRows,
        ]);
    }

    public function getCustomerDataLast7Days()
    {
        $start = Carbon::now()->subDays(7)->startOfDay();
        $end = Carbon::now()->endOfDay();

        return Customer::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getRevenueDataLast7Days()
    {
        $start = Carbon::now()->subDays(7)->startOfDay();
        $end = Carbon::now()->endOfDay();

        return Order::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function exportOrdersToCSV(Request $request)
    {
        // Optional: Accept date range from request (e.g., 'start_date' and 'end_date')
        $startDate = $request->input('start_date', Carbon::now()->subYear()->startOfDay());
        $endDate = $request->input('end_date', Carbon::now()->endOfDay());

        $orders = Order::with('customer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('orders.id', 'orders.order_number', 'orders.status', 'orders.created_at', 'orders.total_amount', 'orders.advance_paid')
            ->orderBy('created_at')
            ->get();

        $headers = ['Order Number', 'Customer Name', 'Date', 'Status', 'Total Amount', 'Advance Paid'];
        $csvContent = implode(',', $headers) . "\n";

        foreach ($orders as $order) {
            $csvRow = [
                $order->order_number,
                optional($order->customer)->name,
                Carbon::parse($order->created_at)->toDateTimeString(),
                $order->status,
                $order->total_amount,
                $order->advance_paid,
            ];

            $csvContent .= implode(',', $csvRow) . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="orders_export.csv"');
    }

    public function seed(Request $request)
    {
        $request->validate([
            'customers' => 'nullable|integer|min:0',
            'orders' => 'required|integer|min:1',
            'order_items' => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        // Case 1: Create new customers if provided
        if (!empty($request->customers) && $request->customers > 0) {
            $customers = Customer::factory()->count($request->customers)->create();
        } else {
            // Case 2: Use all existing customers
            $customers = Customer::all();
        }

        foreach ($customers as $customer) {
            UserCustomer::firstOrCreate([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
            ]);

            $orders = Order::factory()->count($request->orders)->create([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
            ]);

            foreach ($orders as $order) {
                OrderItem::factory()->count($request->order_items)->create([
                    'order_id' => $order->id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Factory data seeded successfully.');
    }
}
