<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Person;
use App\Models\Enrollment;

class UpdateEnrollmentRequest extends FormRequest
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
        $enrollmentId = $this->route('enrollment');
        return [
            'doi' => [
                'required',
                'string',
                'size:8',
                'exists:people,doi',
                function ($attribute, $value, $fail) use ($enrollmentId) {
                    $person = Person::where('doi', $value)->first();

                    if ($person && Enrollment::where('person_id', $person->id)
                        ->where('academic_term_id', $this->academic_term_id)
                        ->where('id', '!=', $enrollmentId)
                        ->exists()) {
                        $fail('El alumno ya está matriculado en este ciclo académico.');
                    }
                }
            ],
            'academic_term_id' => 'required|exists:academic_terms,id',
            'study_area' => 'required|string|max:15',
            'enrollment_date' => 'required|date|before_or_equal: today',
            'start_date' => 'required|date|after_or_equal:enrollment_date',
            'end_date' => 'required|date|after:start_date',
            'due_date' => 'required|date|after:start_date',
            'total_payment' => 'required|numeric|min:0',
            'debt_status' => 'required|in:Paid,Pending,Overdue',
            'shift' => 'required|in:morning,afternoon,both',
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
            'doi.exists' => 'El DNI no está registrado.',
            'enrollment_date.before_or_equal' => 'La fecha de matrícula no puede ser futura.',
            'start_date.after_or_equal' => 'La fecha de inicio debe ser posterior a la fecha de matrícula.',
            'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'due_date.after' => 'La fecha de vencimiento debe ser posterior a la fecha de inicio.',
            'total_payment.min' => 'El pago total debe ser mayor o igual a 0.',
        ];
    }
}
