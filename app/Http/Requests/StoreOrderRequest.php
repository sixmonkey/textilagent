<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'contract' => 'required|max:32',
            'date' => 'required|date',
            'seller.id' => 'exclude_if:seller.id,null|exists:companies,id|different:purchaser.id',
            'seller.name' => 'exclude_unless:seller.id,null|required|min:3|max:255|different:purchaser.name',
            'purchaser.id' => 'exclude_if:purchaser.id,null|exists:companies,id|different:seller.id',
            'purchaser.name' => 'exclude_unless:purchaser.id,null|required|min:3|max:255|different:seller.name',
            'agent.id' => 'sometimes|required|exists:users,id',
            'order_items' => 'required|array|min:1',
            'order_items.*.typology' => 'required',
            'order_items.*.amount' => 'required|numeric',
            'order_items.*.price' => 'required|numeric',
            'order_items.*.provision' => 'required|numeric',
            'order_items.*.unit.id' => 'required|exists:units,id',
            'order_items.*.etd' => 'required|date',
            'sub_agents' => 'sometimes|array',
            'sub_agents.*.cut' => 'required|numeric',
            'sub_agents.*.user' => 'required',
            'sub_agents.*.user.id' => 'exclude_unless:sub_agents.*.user.name,null|required|exists:users,id|different:agent.id',
            'sub_agents.*.user.name' => 'exclude_unless:sub_agents.*.user.id,null|required',
        ];
    }
}
