<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'invoice' => 'required|max:32',
            'date' => 'required|date',
            'shipment_items' => 'required|array|min:1',
            'shipment_items.*.amount' => 'required|numeric|min:1',
            'shipment_items.*.order_item.id' => 'required|exists:shipment_items,id',
        ];
    }

    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'shipment_items.required' => 'Please give at least one shipped item'
        ];
    }
}
