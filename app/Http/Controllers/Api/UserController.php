<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $query = User::all();

        $rows = $query->map(fn($result) => [
            'id'    => $result->id,
            'name'  => $result->name,
            'email' => $result->email
        ]);

        return $this->successResponse($rows);
    }
}
