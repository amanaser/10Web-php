<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UserController;
use Closure;
use Illuminate\Http\Request;
use App\Models\User;


class UserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $type)
    {
        //dd($type);

        if (User::TYPE_SLUGS[auth()->user()->type] !== $type) {
            return response()->json([
                'status' => 404,
                'message' => 'Error with user type.'
            ]);
        }


        //dd($request, $next);
        return $next($request);

    }
}
