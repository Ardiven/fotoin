<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->hasRole('photographer');
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'required|integer|min:1|max:24',
            'max_photos' => 'required|integer|min:1',
            'max_edited_photos' => 'required|integer|min:1',
            'includes_raw_files' => 'boolean',
            'category' => 'required|string|in:wedding,portrait,event,commercial,fashion',
            'is_active' => 'boolean',
            'addons' => 'nullable|array',
            'addons.*.name' => 'required_with:addons|string|max:255',
            'addons.*.price' => 'required_with:addons|numeric|min:0',
            'addons.*.description' => 'nullable|string|max:500'
        ];
    }
}