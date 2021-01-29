<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Resources\UserResource;

class PageController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $req)
    {
        $user = auth()->user();
        return new UserResource($user);
    }

}
