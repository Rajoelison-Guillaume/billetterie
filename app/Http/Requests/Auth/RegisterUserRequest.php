<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Autoriser tous les utilisateurs à s'inscrire
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',

            // ✅ Le client saisit uniquement les 9 chiffres après +261
            // Exemple : 341795207 → sera transformé en +261341795207
            'phone'    => [
                'required',
                'string',
                'regex:/^(32|33|34|37|38)\d{7}$/'
            ],

            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Le nom est obligatoire.',
            'email.required'    => 'L’adresse email est obligatoire.',
            'email.email'       => 'Veuillez saisir une adresse email valide.',
            'email.unique'      => 'Cette adresse email est déjà utilisée.',
            'phone.required'    => 'Le numéro de téléphone est obligatoire.',
            'phone.regex'       => 'Le numéro doit commencer par 32, 33, 34, 37 ou 38 et contenir 9 chiffres.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min'      => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed'=> 'La confirmation du mot de passe ne correspond pas.',
        ];
    }

    /**
     * ✅ Normalise le numéro en +261XXXXXXXXX
     */
    protected function prepareForValidation(): void
    {
        $phone = $this->input('phone');

        // Si l'utilisateur saisit 9 chiffres commençant par 32, 33, 34, 37 ou 38
        if (preg_match('/^(32|33|34|37|38)\d{7}$/', $phone)) {
            $this->merge([
                'phone' => '+261' . $phone,
            ]);
        }
    }
}
