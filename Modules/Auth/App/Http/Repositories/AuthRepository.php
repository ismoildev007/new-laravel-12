<?php

namespace Modules\Auth\App\Http\Repositories;

use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\App\Http\Resources\UserResource;
use Modules\Auth\Interfaces\AuthInterface;

class AuthRepository implements AuthInterface
{
    use ApiResponseTrait;

    public function __construct(protected User $db)
    {
    }


    public function login(array $credentials): false|array
    {
        $user = $this->db->query()->where('email', $credentials['email'])->first();
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        $token = $user->createToken($user->email)->accessToken;

        return [
            'success' => true,
            'user' => new UserResource($user),
            'token' => $token,
        ];
    }

    public function profile()
    {
        return $this->db::query()->where('id', auth()->id())->firstOrFail();
    }

    public function updateProfile($id, array $data): Model|string
    {
        $user = $this->db::query()->where('id', $id)->firstOrFail();

        $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'password' => !empty($data['password']) ? bcrypt($data['password']) : $user->password,
        ]);


        return $user;
    }

    public function logout($request)
    {
        $request->user()->token()->revoke();
        return $this->respondSuccessMessage('Logged out');
    }


}
