<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return \Illuminate\Support\Facades\Auth::check();
    }

    public function withValidator($validator)
    {
        $orderItems = $this->input('order_items', []);

        foreach ($orderItems as $index => $item) {
            // Apply rules only if work_type is 'New from Material'
            if (isset($item['work_type']) && $item['work_type'] === 'New from Material') {
                $validator->addRules([
                    "order_items.$index.material_type" => ['required', 'string', 'max:256'],
                    "order_items.$index.material_code" => ['required', 'string', 'max:256'],
                ]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            // 'order_number' => ['required', 'string',  Rule::unique('orders')->where(function ($query) {
            //     return $query->where('user_id', $this->user_id);
            // })],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'advance_paid' => ['nullable', 'numeric', 'min:0', 'lte:total_amount'],
            'delivery_date' => ['required', 'date', 'after_or_equal:today'],
            'close_date' => ['nullable', 'date', 'after_or_equal:delivery_date'],
            'notes' => ['nullable', 'array'],

            //- This Rules Is Defined For Order Items
            'order_items' => ['required', 'array'],
            // 'order_items.*.order_id' => ['required', 'string'],
            'order_items.*.template_id' => ['required', 'numeric'],
            'order_items.*.measurements' => ['required'],
            'order_items.*.design_detail' => ['required', 'array'],
            'order_items.*.colors' => ['required', 'string', 'max:256'],
            'order_items.*.notes' => ['required', 'array'],
            'order_items.*.delivery_date' => ['required', 'date'],
            'order_items.*.trial_dates' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:delivery_date'],
            'order_items.*.work_type' => ['required', 'string', 'in:New from Material,Only Stitching,Only Altering'],
            'order_items.*.material_code' => ['nullable', 'string', 'max:256'],
            'order_items.*.material_type' => ['nullable', 'string', 'max:256'],
            'order_items.*.refrence_dress' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'order_items.*.is_urgent' => ['nullable', 'boolean'],
            'order_items.*.material_cost' => ['nullable', 'numeric', 'min:0'],
            'order_items.*.stiching_cost' => ['nullable', 'numeric', 'min:0'],
            'order_items.*.item_cost' => ['required', 'numeric', 'min:0'],
            'order_items.*.cloth_img1' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'order_items.*.cloth_img2' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'order_items.*.Pattern_img1' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
            'order_items.*.Pattern_img2' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048']
        ];
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

            'delivery_date.required' => 'The delivery date is required.',
            'delivery_date.date' => 'The delivery date must be a valid date.',
            'delivery_date.after_or_equal' => 'The delivery date cannot be in the past.',

            'close_date.date' => 'The close date must be a valid date.',
            'close_date.after_or_equal' => 'The close date cannot be earlier than the delivery date.',

            'notes.array' => 'Notes must be an array.',

            'order_items.required' => 'At least one order item is required.',
        ];

        foreach ($this->input('order_items', []) as $index => $item) {
            $itemKey = "order_items.$index";

            $messages["$itemKey.template_id.required"] = "Template ID is required for item #" . ($index + 1) . ".";
            $messages["$itemKey.template_id.numeric"] = "Template ID must be a number for item #$index.";

            $messages["$itemKey.measurements.required"] = "Measurements are required for item #" . ($index + 1) . ".";

            $messages["$itemKey.design_detail.required"] = "Design detail is required for item #" . ($index + 1) . ".";
            $messages["$itemKey.design_detail.array"] = "Design detail must be an array for item #$index.";

            $messages["$itemKey.colors.required"] = "Colors are required for item #" . ($index + 1) . ".";
            $messages["$itemKey.colors.max"] = "Colors must not exceed 256 characters for item #$index.";

            $messages["$itemKey.notes.required"] = "Notes are required for item #$index.";

            $messages["$itemKey.delivery_date.required"] = "Delivery date is required for item #" . ($index + 1) . ".";
            $messages["$itemKey.delivery_date.date"] = "Delivery date must be a valid date for item #$index.";

            $messages["$itemKey.trial_dates.required"] = "Trial date is required for item #" . ($index + 1) . ".";
            $messages["$itemKey.trial_dates.date"] = "Trial date must be a valid date for item #$index.";
            $messages["$itemKey.trial_dates.after_or_equal"] = "Trial date must not be in the past for item #" . ($index + 1) . ".";
            $messages["$itemKey.trial_dates.before_or_equal"] = "Trial date must be before or equal to the delivery date for item #$index.";

            $messages["$itemKey.work_type.required"] = "Work type is required for item #" . ($index + 1) . ".";
            $messages["$itemKey.work_type.in"] = "Work type must be one of: New from Material, Only Stitching, Only Altering for item #" . ($index + 1) . ".";

            $messages["$itemKey.material_code.string"] = "Material code must be a string for item #" . ($index + 1) . ".";
            $messages["$itemKey.material_code.max"] = "Material code may not exceed 256 characters for item #$index.";

            $messages["$itemKey.material_type.string"] = "Material type must be a string for item #" . ($index + 1) . ".";
            $messages["$itemKey.material_type.max"] = "Material type may not exceed 256 characters for item #$index.";

            $messages["$itemKey.material_cost.numeric"] = "Material cost must be a number for item #" . ($index + 1) . ".";
            $messages["$itemKey.material_cost.min"] = "Material cost must be at least 0 for item #$index.";

            $messages["$itemKey.stiching_cost.numeric"] = "Stitching cost must be a number for item #" . ($index + 1) . ".";
            $messages["$itemKey.stiching_cost.min"] = "Stitching cost must be at least 0 for item #$index.";

            $messages["$itemKey.item_cost.required"] = "Item cost is required for item #" . ($index + 1) . ".";
            $messages["$itemKey.item_cost.numeric"] = "Item cost must be a number for item #$index.";
            $messages["$itemKey.item_cost.min"] = "Item cost must be at least 0 for item #$index.";

            // Handle all image fields
            foreach (
                [
                    'refrence_dress',
                    'cloth_img1',
                    'cloth_img2',
                    'Pattern_img1',
                    'Pattern_img2',
                    'material_code',
                    'material_type'
                ] as $imageField
            ) {
                $label = ucfirst(str_replace('_', ' ', $imageField));
                $messages["$itemKey.$imageField.image"] = "$label must be an image for item #$index.";
                $messages["$itemKey.$imageField.mimes"] = "$label must be of type jpeg, png, or webp for item #$index.";
                $messages["$itemKey.$imageField.max"] = "$label must not exceed 2MB for item #$index.";
                $messages["$itemKey.$imageField.mimes"] = "$label must be of type jpeg, png, or webp for item #$index.";
                $messages["$itemKey.material_type.required"] = "Material type is required for item #" . ($index + 1) . " because work type is 'New from Material'.";
                $messages["$itemKey.material_code.required"] = "Material code is required for item #" . ($index + 1) . " because work type is 'New from Material'.";
            }
        }

        return $messages;
    }
}
