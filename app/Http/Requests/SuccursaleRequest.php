<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuccursaleRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "libelle"=>"required|min:3",
            "adresse"=>"required|min:3",
            "telephone"=>"required|unique:succursales|regex:/^(7[76508]{1})(\\d{7})$/",
            "reduction"=>"min:0"
        ];
    }

    public function messages(){
        return[
            "libelle.required"=>"le libelle est requis",
            "libelle.min"=>"le libelle doit avoir minimum 3 caracrteres",
            "adresse.required"=>"l'adresse est requis",
            "adresse.min"=>"le libelle doit avoir minimum 3 caracrteres",
            "telephone.required"=>"le telephone requis",
            "telephone.unique"=>"le numero de telephone est unique mec !",
            "telephone.regex"=>"le format du numero de telephone est erroné",
        ];
    }
}
