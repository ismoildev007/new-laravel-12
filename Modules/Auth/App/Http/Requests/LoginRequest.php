<?php

namespace Modules\Auth\App\Http\Requests;

use App\Http\Requests\ApiBaseRequest;

class LoginRequest extends ApiBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required',
            'password' => 'required|min:8'
        ];
    }

}
