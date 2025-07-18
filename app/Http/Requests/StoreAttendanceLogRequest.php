<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules()
    {
        return [
            'user_id' => 'required|string|max:50',
            'name' => 'required|string|max:100',
            'device_id' => 'sometimes|string|max:100', // tambahan untuk tracking device
            'location' => 'sometimes|string|max:255' // jika perlu lokasi
        ];
    }
}
