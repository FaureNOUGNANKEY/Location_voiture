<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePanneRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'priority' => 'required|in:Faible,Moyenne,Urgente',
            'status' => 'nullable|in:En attente,En réparation,Réparée',
            'description' => 'required|string|max:255',
            'panneAmount' => 'required|numeric|min:0',
            'car_id' => 'required|exists:cars,id',
        ];
    }

    public function messages(): array
    {
        return [
            'priority.required'=> 'la priorité est obligatoire',
            'description.required'=> 'la description est obligatoire',
            'status.in' => 'le statut doit être En attente, En réparation, Réparé',
            'priority.in' => 'le statut doit être Faible, Moyenne, Urgente',
            'panneAmount.required'=> 'le prix de la reparation doit etre renséigné',
            'panneAmount.numeric'=> 'le prix dois etre un double',
            'panneAmount.min'=> 'le prix dois etre supérieur à zéro',
            'car_id.required'   => 'La voiture est obligatoire.',
            'car_id.exists'     => 'La voiture sélectionnée n\'existe pas.',

        ];
    }
}
