<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
<<<<<<< HEAD
=======
            if(\Route::is('frontend.donators.*')){
                return route('auth.donators.login');
            }
>>>>>>> 5ab062aeaaff04c04490f2e0e739d29f72016a12
            return route('login');
        }
    }
}
