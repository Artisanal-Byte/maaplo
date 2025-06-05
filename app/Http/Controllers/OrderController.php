<?php

namespace App\Http\Controllers;

use App\Helpers\DesignDetilsHelper;
use App\Helpers\GetTemplateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\OrderData;
use App\Helpers\UniqueOrderNumber; // Ensure this class exists in the specified namespace or create it if missing
use App\Http\Requests\StoreOrderRequest;
use App\Models\DesignDetail;
use App\Models\Template;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
        $user = Auth::user();
        $orders = null;

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

        $user = Auth::user();

        // Fetch all customers of Users
        $user->load('customers');

        //-- item which have a global scope or created by Authentic user
        $itemTypes = $templates = Template::where('user_id', Auth::id())
            ->orWhereNull('user_id')
            ->with('measurements')
            ->get()
            ->append('design_details_list'); // Appends the accessor to each model
        return Inertia::render('orders/Create', [
            'customers' => $user->customers,
            'itemTypes' => $itemTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $storeOrderRequest)
    {
        // dd($storeOrderRequest);
        try {
            $validatedOrderData = $storeOrderRequest->validated();
            $username = auth()->user()->name;
            $userId = auth()->id();
            $orderData = OrderData::prepareOrderItemsData($validatedOrderData);

            // Generate a unique order number
            $uniqueOrderNumber = new UniqueOrderNumber();
            $validatedOrderData['order_number'] = $uniqueOrderNumber->make();

            // Set the order status
            $validatedOrderData['status'] = 'created';

            // Process order items
            $validatedOrderItemsData = $validatedOrderData['order_items'];
            $customerId = $validatedOrderData['customer_id'];
            // Begin a database transaction
            DB::beginTransaction();

            // Check if $orderData contains 'order_data' or just directly use the data
            if (isset($orderData['order_data'])) {
                $orderData['order_data']['order_number'] = $validatedOrderData['order_number'];
                // Create the order record
                Order::create($orderData['order_data']);
            } else {
                // Ensure 'order_number' is included in the $orderData array if not already set
                $orderData['order_number'] = $validatedOrderData['order_number'];

                // Create the order record
                $Order = Order::create($orderData);
            }

            // Process each order item
            foreach ($validatedOrderItemsData as &$item) {
                unset($item['template_id']);
                $item['order_id'] = $Order->id;

                $item['is_urgent'] = filter_var($item['is_urgent'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                $item['is_urgent'] = $item['is_urgent'] == 1 ? 'yes' : 'no';

                if (isset($item['measurements']) && is_array($item['measurements'])) {
                    $item['measurements'] = json_encode($item['measurements']);
                }

                if (isset($item['design_detail']) && is_array($item['design_detail'])) {
                    $item['design_detail'] = json_encode($item['design_detail']);
                } elseif (!isset($item['design_detail']) || $item['design_detail'] === null) {
                    $item['design_detail'] = json_encode([]);
                }

                if (isset($item['notes']) && is_array($item['notes'])) {
                    $item['notes'] = json_encode($item['notes']);
                }

                if (!isset($item['delivery_date']) || empty($item['delivery_date'])) {
                    throw new \Exception("Missing required field: delivery_date for one of the order items.");
                }

                // Temporarily store file references, remove them for DB
                $fileUploads = [];
                foreach (['refrence_dress', 'cloth_img1', 'cloth_img2', 'Pattern_img1', 'Pattern_img2'] as $field) {
                    if (isset($item[$field]) && $item[$field] instanceof UploadedFile) {
                        $fileUploads[$field] = $item[$field];
                        unset($item[$field]);
                    }
                }

                // Save item and get ID
                $orderItem = OrderItem::create($item);

                // Process images with real item ID
                foreach ($fileUploads as $field => $uploadedFile) {
                    $storedPath = ImageHelper::storeOrderItemImage(
                        $uploadedFile,
                        $username,
                        $userId,
                        $Order->id,
                        $customerId,
                        $orderItem->id, // ✅ Real integer ID
                        $field
                    );

                    // Save the image path to DB
                    $orderItem->update([$field => $storedPath]);
                }
            }
            unset($item);

            // Insert the transformed order items into the database
            foreach ($validatedOrderItemsData as $item) {
                OrderItem::create($item);
            }
            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order created successfully.');
        } catch (Exception $exception) {
            dd($exception->getMessage()); // For debugging purposes
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $storeorderdata = $order->load('customer', 'orderItems')->toArray();

        $orderItems = $storeorderdata['order_items'];
        $designDetailsData = DesignDetilsHelper::getDesignDetailsData($orderItems);
        return Inertia::render('orders/Show', [
            'order' => $order,
            'designDetails' => $designDetailsData,
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
