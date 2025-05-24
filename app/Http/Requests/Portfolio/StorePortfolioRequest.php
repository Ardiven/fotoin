<?php

namespace App\Http\Requests\Portfolio;

use Illuminate\Foundation\Http\FormRequest;

class StorePortfolioRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('photographer');
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'category' => 'required|string|in:wedding,portrait,event,commercial,fashion',
            'location' => 'required|string|max:255',
            'is_featured' => 'boolean',
            'images' => 'required|array|min:1|max:20',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120', // 5MB per image
            'tags' => 'nullable|string|max:500'
        ];
    }

    public function messages()
    {
        return [
            'images.required' => 'Minimal 1 gambar harus diupload.',
            'images.max' => 'Maksimal 20 gambar dapat diupload.',
            'images.*.max' => 'Ukuran gambar maksimal 5MB.',
            'category.in' => 'Kategori harus salah satu dari: wedding, portrait, event, commercial, fashion.'
        ];
    }
}