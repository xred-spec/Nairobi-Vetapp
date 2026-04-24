<?php

namespace App\Http\Requests;

use App\Models\TrabajadorHorario;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Illuminate\Support\Carbon;

class CitaRequest extends FormRequest
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
            'fecha' => 'required|date',
            'tipo' => 'required|in:medico,estetico,mixto',
            'descripcion' => 'nullable|string',
            'mascota_id' => 'required|exists:mascotas,id',
            'horario_trabajador_id' => 'required|exists:horario_trabajador,id'
        ];
    }

    public function messages():array{
        return [
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.date' => 'La fecha debe ser una fecha válida.',
            'tipo.required' => 'El tipo de cita es obligatorio.',
            'tipo.in' => 'El tipo de cita debe ser médico, estético o mixto.',
            'descripcion.string' => 'La descripción debe ser un texto válido.',
            'mascota_id.required' => 'El ID de la mascota es obligatorio.',
            'mascota_id.exists' => 'La mascota seleccionada no existe.',
            'horario_trabajador_id.required' => 'El ID del horario del trabajador es obligatorio.',
            'horario_trabajador_id.exists' => 'El horario del trabajador seleccionado no existe.'
        ];
    }
    
    public function withValidator($validator){
        if($validator->errors()->isNotEmpty()){
            return;
        }
        $validator->after(function(Validator $validator){
        $horario = TrabajadorHorario::findOrFail($this->horario_trabajador_id);
        $fecha = Carbon::parse($this->fecha.' '.$horario->horario->inicio_hora);
            if($fecha->isPast()){
                $validator->errors()->add('fecha','no se puede agendar o mover una cita a una fecha y hora pasada');
            }

            if($fecha->isSunday()){
                $validator->errors()->add('dia','no se puede agendar en los domingos');
            }
            if($fecha->isSaturday() &&  ($horario->horario->inicio_hora >'14:00:00' || $horario->horario->final_hora > '14:00:00')){
                $validator->errors()->add('hora','no se puede agendar en los sabados despues de las 2pm');

            }
        });
    }
    
}
