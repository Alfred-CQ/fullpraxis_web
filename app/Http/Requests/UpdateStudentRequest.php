<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Student;

class UpdateStudentRequest extends FormRequest
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
        $personId = $this->getPersonId();

        return [
            'doi' => 'required|string|size:8|unique:people,doi, '. $personId,
            'first_names' => 'required|string|max:50',
            'last_names' => 'required|string|max:50',
            'phone_number' => 'nullable|string|size:9',
            'birth_date' => 'required|date',
            'guardian_phone' => 'required|string|size:9',
            'high_school_name' => 'nullable|string|max:100',
            'photo_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
        ];
    }

    protected function getPersonId()
    {
        $studentId = $this->route('id');
        $student = Student::findOrFail($studentId);
        return $student->person->id;
    }

    /**
     * Get the custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'doi.required' => 'El campo DNI es obligatorio.',
            'doi.size' => 'El DNI debe tener exactamente 8 caracteres.',
            'doi.unique' => 'El DNI ya está registrado.',
            
            'first_names.required' => 'El campo nombres es obligatorio.',
            'last_names.required' => 'El campo apellidos es obligatorio.',
        
            'phone_number.size' => 'El número de celular debe tener exactamente 9 dígitos.',
        
            'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
        
            'guardian_phone.required' => 'El número del apoderado es obligatorio.',
            'guardian_phone.size' => 'El número del apoderado debe tener exactamente 9 dígitos.',
        
            'photo_path.image' => 'La foto debe ser una imagen.',
            'photo_path.mimes' => 'La foto debe ser de tipo: jpeg, png, jpg o gif.',
            'photo_path.max' => 'La foto no debe superar las 5MB de tamaño.',
        ];
    }
}
