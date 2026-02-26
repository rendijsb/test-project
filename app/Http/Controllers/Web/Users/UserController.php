<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Users;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index');
    }

    public function show(int $user): View
    {
        return view('users.show', [
            'userId' => $user,
        ]);
    }
}
