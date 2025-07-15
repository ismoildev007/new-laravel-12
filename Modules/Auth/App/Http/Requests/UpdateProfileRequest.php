<?php

namespace Modules\Auth\App\Http\Requests;

use App\Http\Requests\ApiBaseRequest;

class UpdateProfileRequest extends ApiBaseRequest
{

    public function rules(): array
    {
        return [
            'email' => 'nullable|string|email|max:191|unique:users,email,' . auth()->id(),
            'name' => 'nullable|string|max:191|unique:users,name,' . auth()->id(),
            'password' => 'nullable|string|min:6|confirmed',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $this->sendErrors($validator);
    }
}
