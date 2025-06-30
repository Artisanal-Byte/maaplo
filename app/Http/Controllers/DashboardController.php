<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
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
        // dd($request->toArray());
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);
        $order = Order::find($request->order_id);
        $order->update([
            'status' => 'closed',
        ]);
        ToastMagic::success('Order closed successfully!');
        return redirect()->route('orders.closed');
    }
}
