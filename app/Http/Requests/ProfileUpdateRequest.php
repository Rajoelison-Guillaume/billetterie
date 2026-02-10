<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Autoriser tous les utilisateurs connectés
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],

            // ✅ Accepte 034xxxxxxxx ou +26134xxxxxxxx
            'phone' => [
                'required',
                'string',
                'regex:/^(034|033|032|038|037)\d{7}$|^\+261(34|33|32|38|37)\d{7}$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Le nom est obligatoire.',
            'email.required' => 'L’adresse email est obligatoire.',
            'email.email'    => 'Veuillez saisir une adresse email valide.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
            'phone.regex'    => 'Le numéro doit commencer par 034, 033, 032, 038, 037 ou +261 suivi de 9 chiffres.',
        ];
    }

    /**
     * ✅ Normalise le numéro en +261XXXXXXXXX
     */
    protected function prepareForValidation(): void
    {
        $phone = $this->input('phone');

        // Si le numéro commence par 034, 033, etc. → convertir en +261
        if (preg_match('/^(034|033|032|038|037)(\d{7})$/', $phone, $matches)) {
            $this->merge([
                'phone' => '+261' . $matches[1] . $matches[2],
            ]);
        }
    }
}
