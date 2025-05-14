<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Template;
use App\Models\Order;
use App\Models\OrderItem;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the Orders And Related Data.
     */
    public function index()
    {
        /**
         *  
         *  -> user -> orders -> customer
         * 
         *  -> order items  
         * 
         **/

        $user = Auth::user();
        $orders = [];

        if ($user) {

            $orders = $user->load('orders.customer');

            return Inertia::render('orders/Index', ["orders" => $orders]);
        }

        //-- if User Not Found
        abort(404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /**
         * 
         * -> user -> customers 
         * 
         */

        $user = Auth::user();

        // Fetch all customers of Users 
        $user->load('customers');

        //-- item which have a global scope or created by Authentic user
        $itemType = Template::where('user_id', $user->id)->orWhere('user_id', null)->get();

        // $designDetails = DesignDetails
        // $measurements = Measurement
        return Inertia::render('orders/Create', [
            'customers' => $user->customers,
            'itemType' => $itemType
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $storeOrderRequest)
    {
        $validatedOrderData = $storeOrderRequest->validated();

        $validatedOrderData['status'] = 'created';

        $validatedOrderItemsData = $validatedOrderData['order_items'];

        try {

            DB::beginTransaction();

            //- Create Order

            $Order = Order::create($validatedOrderData);

            $validatedOrderData['order_id'] = $Order->id;

            //- Add Order Items of Created Order

            OrderItem::insert($validatedOrderItemsData);

            DB::commit();
            ToastMagic::success('Order created successfully!');
            return redirect()->route('orders.index')->with('success', 'Order created successfully.');
        } catch (Exception $exception) {

            DB::rollBack();
            ToastMagic::success('Customer created successfully!');
            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return Inertia::render('orders/Show', [
            'order' => $order,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return Inertia::render('orders/Edit', [
            'order' => $order,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        try {
            $validate = $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'customer_id' => ['required', 'exists:customers,id'],
                'order_number' => ['required', 'string', Rule::unique('orders', 'order_number')->ignore($order->id)],
                'status' => ['required', 'in:created,in process,processed,delivered,completed,cancelled'],
                'total_amount' => ['required', 'numeric', 'min:0'],
                'advance_paid' => ['required', 'numeric', 'min:0', 'lte:total_amount'],
                'delivery_date' => ['required', 'date', 'after_or_equal:today'],
                'close_date' => ['nullable', 'date', 'after_or_equal:delivery_date'],
                'notes' => ['nullable', 'array'],
            ], [
                'user_id.required' => 'User ID is required.',
                'customer_id.required' => 'Customer ID is required.',
                'order_number.required' => 'Order number is required.',
                'status.required' => 'Status is required.',
                'total_amount.required' => 'Total amount is required.',
                'advance_paid.required' => 'Advance paid is required.',
                'delivery_date.required' => 'Delivery date is required.',
                'close_date.after_or_equal' => 'Close date must be after or equal to delivery date.',
            ]);

            $order->update($validate);
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
        } catch (Exception $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
        } catch (Exception $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
        }
    }
}
