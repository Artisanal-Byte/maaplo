<?php

namespace App\Http\Controllers;

use App\Helpers\DesignDetilsHelper;
use App\Helpers\ImageHelper;
use App\Helpers\OrderData;
use App\Helpers\UniqueOrderNumber; // Ensure this class exists in the specified namespace or create it if missing
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\DesignDetail;
use App\Models\Template;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Devrabiul\ToastMagic\Facades\ToastMagic;

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
            $orders = $user->orders()->with('customer')->paginate(10);
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
        $itemTypes = Template::where('user_id', Auth::id())
            ->orWhereNull('user_id')
            ->with('measurements')
            ->get(); // Appends the accessor to each model
        foreach ($itemTypes as $template) {
            $template->design_detail_models = collect();

            if (is_array($template->design_details)) {
                $ids = extractDesignDetailIds($template->design_details);
                $template->design_detail_models = DesignDetail::whereIn('id', $ids)->get();
            }
        }

        $allDesignDetails = DesignDetail::with('bodyPartValue')->get();
        // dd($itemTypes->toArray()); // For debugging purposes, remove in production
        return Inertia::render('orders/Create', [
            'customers' => $user->customers,
            'itemTypes' => $itemTypes,
            'allDesignDetails' => $allDesignDetails,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $storeOrderRequest)
    {
        // dd($storeOrderRequest->toArray());
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
                $item['order_id'] = $Order->id;
                $item['is_urgent'] = filter_var($item['is_urgent'], FILTER_VALIDATE_BOOLEAN);
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
                    throw new Exception("Missing required field: delivery_date for one of the order items.");
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
                        $orderItem->id,
                        $field
                    );

                    // Save the image path to DB
                    $orderItem->update([$field => $storedPath]);
                }
            }
            unset($item);

            DB::commit();
            ToastMagic::success('Order created successfully!');
            return redirect()->route('orders.index')->with('success', 'Order created successfully.');
        } catch (Exception $exception) {
            // dd($exception->getMessage()); // For debugging purposes
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order, Request $request)
    {
        $storeorderdata = $order->load('customer', 'orderItems')->toArray();

        $orderItems = $storeorderdata['order_items'];
        $designDetailsData = DesignDetilsHelper::getDesignDetailsData($orderItems);

        // Get the source query parameter, default null
        $source = $request->query('source');
        $allDesignDetails = DesignDetail::with('bodyPartValue')->get();
        return Inertia::render('orders/Show', [
            'order' => $order,
            'designDetails' => $designDetailsData,
            'source' => $source,
            'allDesignDetails' => $allDesignDetails,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        // dd($order->toArray());
        $user = Auth::user();
        $user->load('customers');
        $allDesignDetails = DesignDetail::with('bodyPartValue')->get();
        $itemTypes = Template::where('user_id', Auth::id())
            ->orWhereNull('user_id')
            ->with('measurements')
            ->get();
        // dd($itemTypes ->toArray());
        foreach ($itemTypes as $template) {
            $template->design_detail_values = [];

            if (is_array($template->design_details)) {
                $ids = extractDesignDetailIds($template->design_details);
                $details = DesignDetail::whereIn('id', $ids)->get();

                $grouped = [];
                foreach ($template->design_details as $bodyPart => $idList) {
                    $grouped[$bodyPart] = $details->whereIn('id', $idList)->map(function ($dd) {
                        return [
                            'id' => $dd->id,
                            'name' => strtolower(str_replace(' ', '-', $dd->value)),
                            'label' => $dd->value,
                            'img' => $dd->image,
                        ];
                    })->values();
                }

                $template->design_detail_values = $grouped;
            }
        }


        $orderItems = $order->orderItems->map(function ($item) {
            $ids = json_decode($item->design_detail, true) ?? [];
            // dd($ids);

            $templateNames = Template::whereIn('id', $ids)->pluck('name')->toArray();
            // dd($templateNames);
            return [
                'id' => $item->id,
                'template_id' => $item->template_id,
                'design_detail' => $item->design_detail,
                'measurements' => $item->measurements,
                'item_cost' => $item->item_cost,
                'colors' => $item->colors,
                'notes' => $item->notes,
                'delivery_date' => $item->delivery_date,
                'trial_dates' => $item->trial_dates,
                'work_type' => $item->work_type,
                'material_type' => $item->material_type,
                'material_code' => $item->material_code,
                'material_cost' => $item->material_cost,
                'stiching_cost' => $item->stiching_cost,
                'altering_cost' => $item->altering_cost,
                'isUrgent' => $item->is_urgent,
                'template_names' => $templateNames,
                'refrence_dress' => $item->refrence_dress ? asset('/' . $item->refrence_dress) : null,
                'cloth_img1_url' => $item->cloth_img1 ? asset('/' . $item->cloth_img1) : null,
                'cloth_img2_url' => $item->cloth_img2 ? asset('/' . $item->cloth_img2) : null,
                'Pattern_img1_url' => $item->Pattern_img1 ? asset('/' . $item->Pattern_img1) : null,
                'Pattern_img2_url' => $item->Pattern_img2 ? asset('/' . $item->Pattern_img2) : null,

            ];
        });
        return Inertia::render('orders/Edit', [
            'order' => $order,
            'itemTypes' => $itemTypes,
            'customers' => $user->customers,
            'orderItems' => $orderItems,
            'allDesignDetails' => $allDesignDetails,
        ]);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        // dd($request->toArray());
        ini_set('max_execution_time', 60);

        try {
            $validatedData = $request->validated();
            // dd($validatedData);
            $userId = Auth::user()->id;
            $username = Auth::user()->name;
            $customerId = $validatedData['customer_id'];

            $validatedOrderItems = $validatedData['order_items'];
            unset($validatedData['order_items']); // Remove items from order update

            DB::beginTransaction();

            // ✅ Update the main order
            $order->update($validatedData);

            // ✅ Track existing item IDs to determine which to update or delete
            $existingItemIds = $order->orderItems()->pluck('id')->toArray();
            $incomingItemIds = [];

            foreach ($validatedOrderItems as $item) {
                $fileUploads = [];

                foreach (['refrence_dress', 'cloth_img1', 'cloth_img2', 'Pattern_img1', 'Pattern_img2'] as $field) {
                    if (isset($item[$field]) && $item[$field] instanceof UploadedFile) {
                        $fileUploads[$field] = $item[$field];
                        unset($item[$field]);
                    }
                }

                // Normalize boolean
                $item['is_urgent'] = filter_var($item['is_urgent'] ?? false, FILTER_VALIDATE_BOOLEAN);

                // dd($item['is_urgent'] );
                // JSON encode fields
                foreach (['measurements', 'design_detail', 'notes'] as $field) {
                    if (isset($item[$field]) && is_array($item[$field])) {
                        $item[$field] = json_encode($item[$field]);
                    } elseif (!isset($item[$field]) || $item[$field] === null) {
                        $item[$field] = json_encode([]);
                    }
                }

                if (!isset($item['delivery_date']) || empty($item['delivery_date'])) {
                    throw new Exception("Missing required field: delivery_date for one of the order items.");
                }

                // Create or update order item
                if (isset($item['id'])) {
                    $orderItem = OrderItem::findOrFail($item['id']);
                    $orderItem->update($item);
                    $incomingItemIds[] = $orderItem->id;
                } else {
                    $item['order_id'] = $order->id;
                    $orderItem = OrderItem::create($item);
                    $incomingItemIds[] = $orderItem->id;
                }

                // ✅ Delete old image and upload new image
                foreach ($fileUploads as $field => $uploadedFile) {
                    // Delete old image if it exists
                    if (!empty($orderItem->$field) && file_exists(public_path($orderItem->$field))) {
                        @unlink(public_path($orderItem->$field));
                    }

                    // Store new image
                    $storedPath = ImageHelper::storeOrderItemImage(
                        $uploadedFile,
                        $username,
                        $userId,
                        $order->id,
                        $customerId,
                        $orderItem->id,
                        $field
                    );

                    // Update DB path
                    $orderItem->update([$field => $storedPath]);
                }
            }

            // ✅ Delete removed order items
            $itemsToDelete = array_diff($existingItemIds, $incomingItemIds);
            OrderItem::whereIn('id', $itemsToDelete)->each(function ($item) {
                foreach (['refrence_dress', 'cloth_img1', 'cloth_img2', 'Pattern_img1', 'Pattern_img2'] as $field) {
                    if (!empty($item->$field) && file_exists(public_path($item->$field))) {
                        @unlink(public_path($item->$field));
                    }
                }
                $item->delete();
            });

            DB::commit();

            ToastMagic::success('Order updated successfully!');
            return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // dd($order);
        try {
            $order->delete();
            ToastMagic::success('Order Deleted successfully!');
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
        } catch (Exception $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
        }
    }
}
