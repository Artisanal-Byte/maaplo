<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return \Illuminate\Support\Facades\Auth::check();
    }

    public function withValidator($validator)
    {
        $orderItems = $this->input('order_items', []);

        foreach ($orderItems as $index => $item) {
            if (isset($item['work_type']) && $item['work_type'] === 'New from Material') {
                $validator->addRules([
                    "order_items.$index.material_type" => ['nullable', 'string', 'max:256'],
                    "order_items.$index.material_code" => ['nullable', 'string', 'max:256'],
                ]);
            }
        }
    }

    public function rules(): array
    {
        $rules = [
            'customer_id' => ['required', 'exists:customers,id'],
            'status' => ['required', 'string', Rule::in([
                'created',
                'in_process',
                'processed',
                'delivered',
                'colsed',
                'cancelled',
                'ready_for_delivery',
                'in_alteration',
                'trial_done',

            ])],

            // ✅ Add this new rule for each order item
            'order_items.*.item_status' => [
                'required',
                'string',
                Rule::in([
                    'created',
                    'in_process',
                    'processed',
                    'delivered',
                    'closed',
                    'cancelled',
                    'ready_for_delivery',
                    'in_alteration',
                    'trial_done',
                    'completed',
                ]),
            ],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'advance_paid' => ['nullable', 'numeric', 'min:0', 'lte:total_amount'],
            'delivery_date' => ['required', 'date', 'after_or_equal:today'],
            'close_date' => ['nullable', 'date', 'after_or_equal:delivery_date'],
            'notes' => ['nullable', 'array'],

            'order_items' => ['required', 'array'],
            'order_items.*.id' => ['sometimes', 'exists:order_items,id'],
            'order_items.*.template_id' => ['required', 'numeric'],
            'order_items.*.measurements' => ['required'],
            'order_items.*.design_detail' => ['required', 'array'],
            'order_items.*.colors' => ['required', 'string', 'max:256'],
            'order_items.*.notes' => ['nullable', 'array'],
            'order_items.*.delivery_date' => ['required', 'date'],
            'order_items.*.trial_dates' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:delivery_date'],
            'order_items.*.work_type' => ['required', 'string', Rule::in(['New from Material', 'Only Stitching', 'Only Altering'])],

            'order_items.*.material_code' => [
                'nullable',
                'string',
                'max:256',
                function ($attribute, $value, $fail) {
                    $index = (int) filter_var($attribute, FILTER_SANITIZE_NUMBER_INT);
                    $workType = $this->input("order_items.$index.work_type");
                    if ($workType === 'New from Material' && !$value) {
                        $fail('The material code is required when work type is New from Material.');
                    }
                }
            ],
            'order_items.*.material_type' => [
                'nullable',
                'string',
                'max:256',
                function ($attribute, $value, $fail) {
                    $index = (int) filter_var($attribute, FILTER_SANITIZE_NUMBER_INT);
                    $workType = $this->input("order_items.$index.work_type");
                    if ($workType === 'New from Material' && !$value) {
                        $fail('The material type is required when work type is New from Material.');
                    }
                }
            ],
            'order_items.*.material_cost' => ['nullable', 'numeric', 'min:0'],
            'order_items.*.stiching_cost' => ['nullable', 'numeric', 'min:0'],
            'order_items.*.is_urgent' => ['sometimes', 'in:true,false,yes,no'],
            'order_items.*.item_cost' => ['required', 'numeric', 'min:0'],
            'order_items.*.refrence_dress' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'order_items.*.cloth_img1' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'order_items.*.cloth_img2' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'order_items.*.Pattern_img1' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'order_items.*.Pattern_img2' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ];

        return $rules;
    }


    public function messages(): array
    {
        $messages = [
            'user_id.required' => 'The user is required.',
            'user_id.exists' => 'The selected user does not exist.',

            'customer_id.required' => 'The customer is required.',
            'customer_id.exists' => 'The selected customer does not exist.',

            'total_amount.required' => 'The total amount is required.',
            'total_amount.numeric' => 'The total amount must be a valid number.',
            'total_amount.min' => 'The total amount must be at least 0.',

            'advance_paid.numeric' => 'The advance paid must be a number.',
            'advance_paid.min' => 'The advance paid must be at least 0.',
            'advance_paid.lte' => 'The advance paid cannot be greater than the total amount.',

            'delivery_date.required' => 'Please choose a delivery date — it\'s needed to complete your order.',
            'delivery_date.date' => 'The delivery date must be a valid date.',
            'delivery_date.after_or_equal' => 'The delivery date cannot be in the past.',

            'close_date.date' => 'The close date must be a valid date.',
            'close_date.after_or_equal' => 'The close date cannot be earlier than the delivery date.',

            'status.required' => 'Please select a valid order status.',
            'status.in' => 'Selected order status is invalid.',
            'notes.array' => 'Notes must be an array.',

            'order_items.required' => 'At least one order item is required.',
        ];

        foreach ($this->input('order_items', []) as $index => $item) {
            $displayIndex = $index + 1;
            $key = "order_items.$index";

            $messages["$key.template_id.required"] = "Please select a template for item #$displayIndex.";
            $messages["$key.template_id.numeric"] = "The selected template for item #$displayIndex must be a valid number.";

            $messages["$key.measurements.required"] = "Please enter measurements for item #$displayIndex.";

            $messages["$key.design_detail.required"] = "Please select a design detail for item #$displayIndex.";
            $messages["$key.design_detail.array"] = "Design details must be an array for item #$displayIndex.";

            $messages["$key.colors.required"] = "Please enter colors for item #$displayIndex.";
            $messages["$key.colors.max"] = "Colors for item #$displayIndex must not exceed 256 characters.";

            $messages["$key.notes.required"] = "Please add notes for item #$displayIndex.";

            $messages["$key.delivery_date.required"] = "Please choose a delivery date for item #$displayIndex.";
            $messages["$key.delivery_date.date"] = "The delivery date must be a valid date for item #$displayIndex.";

            $messages["$key.trial_dates.required"] = "Please choose a trial date for item #$displayIndex.";
            $messages["$key.trial_dates.date"] = "The trial date must be valid for item #$displayIndex.";
            $messages["$key.trial_dates.after_or_equal"] = "The trial date must not be in the past for item #$displayIndex.";
            $messages["$key.trial_dates.before_or_equal"] = "The trial date must be before or on the delivery date for item #$displayIndex.";

            $messages["$key.work_type.required"] = "Please select a work type for item #$displayIndex.";
            $messages["$key.work_type.in"] = "The work type must be one of: New from Material, Only Stitching, or Only Altering for item #$displayIndex.";

            $messages["$key.material_code.string"] = "Material code must be a valid string for item #$displayIndex.";
            $messages["$key.material_code.max"] = "Material code must not exceed 256 characters for item #$displayIndex.";

            $messages["$key.material_type.string"] = "Material type must be a valid string for item #$displayIndex.";
            $messages["$key.material_type.max"] = "Material type must not exceed 256 characters for item #$displayIndex.";

            $messages["$key.material_cost.numeric"] = "Material cost must be a number for item #$displayIndex.";
            $messages["$key.material_cost.min"] = "Material cost must be at least 0 for item #$displayIndex.";

            $messages["$key.stiching_cost.numeric"] = "Stitching cost must be a number for item #$displayIndex.";
            $messages["$key.stiching_cost.min"] = "Stitching cost must be at least 0 for item #$displayIndex.";

            $messages["$key.item_cost.required"] = "Item cost is required for item #$displayIndex.";
            $messages["$key.item_cost.numeric"] = "Item cost must be a number for item #$displayIndex.";
            $messages["$key.item_cost.min"] = "Item cost must be at least 0 for item #$displayIndex.";

            $messages["$key.item_status.required"] = "Please select a status for item #$displayIndex.";
            $messages["$key.item_status.in"] = "The selected status for item #$displayIndex is invalid.";


            // Optional image fields
            foreach (['refrence_dress', 'cloth_img1', 'cloth_img2', 'Pattern_img1', 'Pattern_img2'] as $field) {
                $label = ucwords(str_replace('_', ' ', $field));
                $messages["$key.$field.image"] = "$label must be an image for item #$displayIndex.";
                $messages["$key.$field.mimes"] = "$label must be a jpeg, png, or webp file for item #$displayIndex.";
                $messages["$key.$field.max"] = "$label must not exceed 2MB for item #$displayIndex.";
            }

            // Conditional required fields if work_type is 'New from Material'
            if (isset($item['work_type']) && $item['work_type'] === 'New from Material') {
                $messages["$key.material_type.required"] = "Please select a material type for item #$displayIndex, since the work type is 'New from Material'.";
                $messages["$key.material_code.required"] = "Please enter a material code for item #$displayIndex, since the work type is 'New from Material'.";
            }
        }
        return $messages;
    }
}
