<?php

namespace Modules\Auth\Interfaces;

interface AuthInterface
{
    public function login(array $credentials);
    public function profile();

    public function updateProfile($id, array $data);

    public function logout($request);

}
