<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexTripRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string'],
            'location' => ['sometimes', 'string'],
            'start_date' => ['date'],
            'end_date' => ['date'],
            'min_price' => ['numeric'],
            'max_price' => ['numeric'],
            'order_by' => ['sometimes', 'string', 'in:title,location,price'],
            'order_direction' => ['sometimes', 'string', 'in:asc,desc']
        ];
    }
}
