<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'doi' => 'required|string|size:8|unique:people,doi',
            'first_names' => 'required|string|max:50',
            'last_names' => 'required|string|max:50',
            'phone_number' => 'nullable|string|size:9',
            'birth_date' => 'required|date|before:3 years ago',
            'guardian_phone' => 'required|string|size:9',
            'high_school_name' => 'nullable|string|max:100',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ];
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'doi.unique' => 'El DNI ya está registrado.',
            
            'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
            'birth_date.before' => 'La fecha de nacimiento debe ser antes de 3 años atrás.',
        
            'photo_path.image' => 'La foto debe ser una imagen.',
            'photo_path.mimes' => 'La foto debe ser de tipo: jpeg, png, jpg o gif.',
            'photo_path.max' => 'La foto no debe superar las 5MB de tamaño.',
        ];
    }


}
