<?php

namespace Modules\Auth\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\App\Http\Repositories\AuthRepository;
use Modules\Auth\App\Http\Requests\LoginRequest;
use Modules\Auth\App\Http\Requests\UpdateProfileRequest;
use Modules\Auth\App\Http\Resources\UserResource;

class AuthController extends Controller
{
    /**
     * @group Authentication
     * Barcha login, logout va profile bilan bog'liq endpointlar shu yerda
     */
    public function __construct(protected AuthRepository $repository)
    {

    }

    /**
     * Login
     *
     * Foydalanuvchini login qilish uchun endpoint. Email va parol yuboriladi.
     *
     * @bodyParam email string required Foydalanuvchi emaili. Misol: admin@gmail.com
     * @bodyParam password string required Foydalanuvchi paroli. Misol: password
     *
     * @response 200 {
     *   "data": {
     *     "success": true,
     *     "token": "eyJ0eXAiOiJKV1QiLCJh...",
     *     "user": {
     *       "id": 1,
     *       "name": "Mehriddin",
     *       "email": "admin@gmail.com"
     *     }
     *   }
     * }
     * @response 401 {
     *   "message": "User not found or don't have permission"
     * }
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request = $request->validated();
        try {
            $data = $this->repository->login($request);
            if ($data === false) {
                return $this->respondBadRequest("User not found or don't have permission", 401);
            }
            if ($data) {
                return $this->respond(['data' => $data]);
            }
            return $this->respondBadRequest('Unauthorized', 401);

        } catch (\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }
    /**
     * Profile
     *
     * Tizimga kirgan foydalanuvchining profilini olish.
     *
     * @authenticatedphp artisan vendor:publish --tag=scribe-config
     * @response 200 {
     *   "id": 1,
     *   "name": "Mehriddin",
     *   "email": "admin@gmail.com"
     * }
     */
    public function profile()
    {
        try {
            return $this->repository->profile();
        } catch (\Exception $e) {
            return $this->respondBadRequest($e->getMessage(), 401);
        }
    }
    /**
     * Update Profile
     *
     * Foydalanuvchi o'z profilini yangilaydi (ism, email, parol).
     *
     * @authenticated
     * @urlParam id integer required Foydalanuvchi IDsi. Misol: 1
     * @bodyParam name string Foydalanuvchi ismi. Misol: Mehriddin
     * @bodyParam email string Foydalanuvchi emaili. Misol: admin@gmail.com
     * @bodyParam password string Foydalanuvchi paroli (kamida 6ta belgi).
     * @bodyParam password_confirmation string Parolni tasdiqlash. Misol: password
     *
     * @response 200 {
     *   "data": {
     *     "id": 1,
     *     "name": "Mehriddin",
     *     "email": "admin@gmail.com"
     *   },
     *   "message": "User update success"
     * }
     */

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $user = $this->repository->updateProfile(Auth::id(), $request->validated());
            if (!$user instanceof User) {
                return $this->respondBadRequest('User password is not correct');
            }
            return response()->json(['data' => UserResource::make($user), 'message' => 'User update success']);
        } catch (\Exception $e) {
            return $this->respondBadRequest($e->getMessage());
        }
    }
    /**
     * Logout
     *
     * Foydalanuvchini tizimdan chiqarish. Token bekor qilinadi.
     *
     * @authenticated
     * @response 200 {
     *   "message": "Successfully logged out"
     * }
     */
    public function logout(Request $request)
    {
        try {
            return $this->repository->logout($request);
        }catch (\Exception $e){
            return $this->respondBadRequest($e->getMessage(), 401);
        }

    }


}
