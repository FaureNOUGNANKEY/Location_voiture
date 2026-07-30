<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCarRequest extends FormRequest
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
        $car = $this->route('car');

        if ($car instanceof \Illuminate\Database\Eloquent\Model) {
            $car = $car->getKey();
        }

        return [
            'mark'=>'string|required|max:255',
            'model'=>'string|required|max:255',
            'color'=>'string|required|max:255',
            'photo'=>'nullable|image|mimes:jpg,jpeg,png|max:2048',
            // 'imatriculation'  => 'string|required|max:255|unique:cars,imatriculation,' . $id,
            'imatriculation' => [
                'required',
                Rule::unique('cars')->ignore($car),
            ],
            'description'=>'string|nullable|max:255',
            'kmAmount'=>'numeric|required|min:0',
            'dayAmount'=>'numeric|required|min:0',
            'state'=>'string|required|max:255',
            'place'=>'numeric|required|min:0',
            'door'=>'numeric|required|min:0',
            'kilometrage'=>'numeric|required|min:0',
            'niveauCarburant'=>'numeric|required|min:0',
            'domage'=>'string|max:255',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'mark.required'            => 'La marque est obligatoire.',
            'mark.string'              => 'La marque doit être une chaîne de caractères.',

            'model.required'           => 'Le modèle est obligatoire.',
            'model.string'             => 'Le modèle doit être une chaîne de caractères.',

            'color.required'           => 'La couleur est obligatoire.',
            'color.string'             => 'La couleur doit être une chaîne de caractères.',

            'photo.required'           => 'La photo est obligatoire.',
            'photo.string'             => 'La photo doit être une chaîne de caractères.',

            'imatriculation.required'  => 'L’immatriculation est obligatoire.',
            'imatriculation.string'    => 'L’immatriculation doit être une chaîne de caractères.',
            'imatriculation.unique'    => 'Cette immatriculation existe déjà.',

            'description.string'       => 'La description doit être une chaîne de caractères.',

            'kmAmount.required'         => 'Le prix par kilomètre est obligatoire.',
            'kmAmount.numeric'          => 'Le prix par kilomètre doit être un nombre.',

            'dayAmount.required'         => 'Le prix par jour est obligatoire.',
            'dayAmount.numeric'          => 'Le prix par jour doit être un nombre.',

            'state.required'           => 'L’état du véhicule est obligatoire.',
            'state.string'             => 'L’état doit être une chaîne de caractères.',

            'place.required'           => 'Le nombre de places est obligatoire.',
            'place.numeric'            => 'Le nombre de places doit être un nombre.',

            'door.required'            => 'Le nombre de portes est obligatoire.',
            'door.numeric'             => 'Le nombre de portes doit être un nombre.',

            'kilometrage.required'     => 'Le kilométrage est obligatoire.',
            'kilometrage.numeric'      => 'Le kilométrage doit être un nombre.',

            'niveauCarburant.required' => 'Le niveau de carburant est obligatoire.',
            'niveauCarburant.numeric'  => 'Le niveau de carburant doit être un nombre.',

            'domage.string'            => 'Le champ dommage doit être une chaîne de caractères.',

            'category_id.required' => 'La catégorie est obligatoire.',
            'category_id.exists'   => 'La catégorie sélectionnée n\'existe pas.',

            'status.required'    => 'Le statut est obligatoire.',
            'status.string'      => 'Le statut doit être une chaîne de caractères.',
        ];
    }
}
