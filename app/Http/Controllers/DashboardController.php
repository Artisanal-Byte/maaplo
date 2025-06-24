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

        $organizationName = optional($user->organization)->organization_name;
        $showOrganizationPopup = $user->hash_organization && is_null($organizationName);

        return Inertia::render('Dashboard', [
            'organizationName' => $organizationName,
            'showOrganizationPopup' => $showOrganizationPopup,
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
