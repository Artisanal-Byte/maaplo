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
                    "order_items.$index.material_type" => ['required', 'string', 'max:256'],
                    "order_items.$index.material_code" => ['required', 'string', 'max:256'],
                ]);
            }
        }
    }

    public function rules(): array
    {
        $rules = [
            'customer_id' => ['required', 'exists:customers,id'],
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
            'order_items.*.notes' => ['required', 'array'],
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
        return [
            'user_id.required' => 'The user is required.',
            'user_id.exists' => 'The selected user does not exist.',

            'customer_id.required' => 'The customer is required.',
            'customer_id.exists' => 'The selected customer does not exist.',

            'total_amount.required' => 'The total amount is required.',
            'total_amount.numeric' => 'The total amount must be a valid number.',
            'total_amount.min' => 'The total amount must be at least 0.',

            'advance_paid.required' => 'The advance paid amount is required.',
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
            'order_items.*.template_id.required' => 'The item template ID is required.',
            'order_items.*.template_id.numeric' => 'The item template ID must be a number.',

            'order_items.*.measurements.required' => 'Measurements are required.',

            'order_items.*.colors.required' => 'Color information is required.',
            'order_items.*.colors.max' => 'Color information may not be greater than 256 characters.',

            'order_items.*.notes.required' => 'Notes are required.',

            'order_items.*.trial_dates.required' => 'Trial date is required.',
            'order_items.*.trial_dates.date' => 'Trial date must be a valid date.',
            'order_items.*.trial_dates.after_or_equal' => 'Trial date cannot be in the past.',
            'order_items.*.trial_dates.before_or_equal' => 'Trial date must be on or before the delivery date.',

            'order_items.*.work_type.required' => 'The work type is required.',
            'order_items.*.work_type.in' => 'The selected work type must be one of: New from Material, Only Stitching, or Only Altering.',

            'order_items.*.material_code.string' => 'The material code must be a string.',
            'order_items.*.material_code.max' => 'The material code may not exceed 256 characters.',

            'order_items.*.material_type.string' => 'The material type must be a string.',
            'order_items.*.material_type.max' => 'The material type may not exceed 256 characters.',

            'order_items.*.material_cost.numeric' => 'Material cost must be a number.',
            'order_items.*.material_cost.min' => 'Material cost must be at least 0.',

            'order_items.*.stiching_cost.numeric' => 'Stitching cost must be a number.',
            'order_items.*.stiching_cost.min' => 'Stitching cost must be at least 0.',

            'order_items.*.item_cost.required' => 'Item cost is required.',
            'order_items.*.item_cost.numeric' => 'Item cost must be a number.',
            'order_items.*.item_cost.min' => 'Item cost must be at least 0.',

            'order_items.*.cloth_img1.image' => 'Cloth Image 1 must be a valid image.',
            'order_items.*.cloth_img1.mimes' => 'Cloth Image 1 must be a file of type: jpeg, png, webp.',
            'order_items.*.cloth_img1.max' => 'Cloth Image 1 must not be greater than 2MB.',

            'order_items.*.cloth_img2.image' => 'Cloth Image 2 must be a valid image.',
            'order_items.*.cloth_img2.mimes' => 'Cloth Image 2 must be a file of type: jpeg, png, webp.',
            'order_items.*.cloth_img2.max' => 'Cloth Image 2 must not be greater than 2MB.',

            'order_items.*.Pattern_img1.image' => 'Pattern Image 1 must be a valid image.',
            'order_items.*.Pattern_img1.mimes' => 'Pattern Image 1 must be a file of type: jpeg, png, webp.',
            'order_items.*.Pattern_img1.max' => 'Pattern Image 1 must not be greater than 2MB.',

            'order_items.*.Pattern_img2.image' => 'Pattern Image 2 must be a valid image.',
            'order_items.*.Pattern_img2.mimes' => 'Pattern Image 2 must be a file of type: jpeg, png, webp.',
            'order_items.*.Pattern_img2.max' => 'Pattern Image 2 must not be greater than 2MB.',
        ];
    }
}
