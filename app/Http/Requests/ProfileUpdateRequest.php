<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            
            // --- USERNAME ---
            'username' => [
                'required', 
                'string', 
                'max:255', 
                'alpha_dash', 
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            // --- EMAIL ---
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            
            'bio' => ['nullable', 'string', 'max:1000'],

            // --- 🟢 YANGI QO'SHILGAN QISMLAR (RASMLAR UCHUN) ---
            
            'avatar' => [
                'nullable', 
                'image', // Fayl rasm bo'lishi shart
                'mimes:jpeg,png,jpg,gif,webp', // Ruxsat etilgan formatlar (WEBP ham qo'shildi)
                'max:10240', // Maksimum 10 MB (10240 KB)
            ],

            'banner' => [
                'nullable', 
                'image', 
                'mimes:jpeg,png,jpg,gif,webp', 
                'max:10240', // Maksimum 10 MB
            ],
        ];
    }
}