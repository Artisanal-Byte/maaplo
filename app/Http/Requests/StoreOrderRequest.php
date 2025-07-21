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
        // dd('store_order_request_authorize', $this->all());
        return \Illuminate\Support\Facades\Auth::check();
    }

    public function withValidator($validator)
    {
        $orderItems = $this->input('order_items', []);

        foreach ($orderItems as $index => $item) {
            // Apply rules only if work_type is 'New from Material'
            if (isset($item['work_type']) && $item['work_type'] === 'New from Material') {
                $validator->addRules([
                    "order_items.$index.material_type" => ['nullable', 'string', 'max:256'],
                    "order_items.$index.material_code" => ['nullable', 'string', 'max:256'],
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
            'user_id.required' => 'Please selecte user — it\'s needed to complete your order.',
            'user_id.exists' => 'The selected user does not exist.',

            'customer_id.required' => 'Please selecte customer — it\'s needed to complete your order.',
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

            'notes.array' => 'Notes must be an array.',

            'order_items.required' => 'At least one order item is required.',
        ];

        foreach ($this->input('order_items', []) as $index => $item) {
            $displayIndex = $index + 1;
            $itemKey = "order_items.$index";

            $messages["$itemKey.template_id.required"] = "Please select a template for item #$displayIndex.";
            $messages["$itemKey.template_id.numeric"] = "The selected template for item #$displayIndex must be a valid number.";

            $messages["$itemKey.measurements.required"] = "Please enter measurements for item #$displayIndex.";

            $messages["$itemKey.design_detail.required"] = "Please select a design detail for item #$displayIndex.";
            $messages["$itemKey.design_detail.array"] = "Design details must be in the correct format for item #$displayIndex.";

            $messages["$itemKey.colors.required"] = "Please enter the colors for item #$displayIndex.";
            $messages["$itemKey.colors.max"] = "Colors for item #$displayIndex must not exceed 256 characters.";

            $messages["$itemKey.notes.required"] = "Please add notes for item #$displayIndex.";

            $messages["$itemKey.delivery_date.required"] = "Please Choose a delivery date for item #$displayIndex.";
            $messages["$itemKey.delivery_date.date"] = "The delivery date must be a valid date for item #$displayIndex.";

            $messages["$itemKey.trial_dates.required"] = "Please Choose a trial date for item #$displayIndex.";
            $messages["$itemKey.trial_dates.date"] = "The trial date must be valid for item #$displayIndex.";
            $messages["$itemKey.trial_dates.after_or_equal"] = "The trial date must not be in the past for item #$displayIndex.";
            $messages["$itemKey.trial_dates.before_or_equal"] = "The trial date must be before or equal to the delivery date for item #$displayIndex.";

            $messages["$itemKey.work_type.required"] = "Please select the work type for item #$displayIndex.";
            $messages["$itemKey.work_type.in"] = "The work type must be one of: New from Material, Only Stitching, or Only Altering for item #$displayIndex.";

            $messages["$itemKey.material_code.string"] = "Material code for item #$displayIndex must be a valid text.";
            $messages["$itemKey.material_code.max"] = "Material code must not exceed 256 characters for item #$displayIndex.";

            $messages["$itemKey.material_type.string"] = "Material type for item #$displayIndex must be valid text.";
            $messages["$itemKey.material_type.max"] = "Material type must not exceed 256 characters for item #$displayIndex.";

            $messages["$itemKey.material_cost.numeric"] = "Material cost must be a valid number for item #$displayIndex.";
            $messages["$itemKey.material_cost.min"] = "Material cost must be at least 0 for item #$displayIndex.";

            $messages["$itemKey.stiching_cost.numeric"] = "Stitching cost must be a valid number for item #$displayIndex.";
            $messages["$itemKey.stiching_cost.min"] = "Stitching cost must be at least 0 for item #$displayIndex.";

            $messages["$itemKey.item_cost.required"] = "Please enter the item cost for item #$displayIndex.";
            $messages["$itemKey.item_cost.numeric"] = "Item cost must be a valid number for item #$displayIndex.";
            $messages["$itemKey.item_cost.min"] = "Item cost must be at least 0 for item #$displayIndex.";

            // Optional image-related fields
            foreach (
                [
                    'refrence_dress',
                    'cloth_img1',
                    'cloth_img2',
                    'Pattern_img1',
                    'Pattern_img2',
                ] as $imageField
            ) {
                $label = ucwords(str_replace('_', ' ', $imageField));
                $messages["$itemKey.$imageField.image"] = "$label must be a valid image for item #$displayIndex.";
                $messages["$itemKey.$imageField.mimes"] = "$label must be a jpeg, png, or webp file for item #$displayIndex.";
                $messages["$itemKey.$imageField.max"] = "$label must not exceed 2MB in size for item #$displayIndex.";
            }

            // Conditional required messages
            if (isset($item['work_type']) && $item['work_type'] === 'New from Material') {
                $messages["$itemKey.material_type.required"] = "Please select a material type for item #$displayIndex, since the work type is 'New from Material'.";
                $messages["$itemKey.material_code.required"] = "Please enter a material code for item #$displayIndex, since the work type is 'New from Material'.";
            }
        }
        return $messages;
    }
}
