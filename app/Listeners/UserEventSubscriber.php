<?php

namespace App\Listeners;

use Carbon\Carbon;
use Log;

class UserEventSubscriber
{
    /**
     * Handle user login events.
     */
    public function handleUserLogin($event)
    {
<<<<<<< HEAD
        try {
            $user = $event->user;
            $user_profile = $user->userprofile;

            /*
             * Updating user profile data after successful login
             */
            $user_profile->last_login = Carbon::now();
            $user_profile->last_ip = request()->getClientIp();
            $user_profile->login_count = $user_profile->login_count + 1;
            $user_profile->save();
        } catch (\Exception $e) {
            Log::error($e);
        }

        Log::debug('Login Success: '.$user->name.', IP:'.request()->getClientIp());
=======
        if(\Auth::guard('donator')->check()){
            $user = $event->user;

            Log::debug('Login Success: '.$user->user->name.', IP:'.request()->getClientIp());
        }else{
            try {
                $user = $event->user;
                $user_profile = $user->userprofile;
    
                /*
                 * Updating user profile data after successful login
                 */
                $user_profile->last_login = Carbon::now();
                $user_profile->last_ip = request()->getClientIp();
                $user_profile->login_count = $user_profile->login_count + 1;
                $user_profile->save();
            } catch (\Exception $e) {
                Log::error($e);
            }
    
            Log::debug('Login Success: '.$user->name.', IP:'.request()->getClientIp());
        }
>>>>>>> 5ab062aeaaff04c04490f2e0e739d29f72016a12
    }

    /**
     * Handle user logout events.
     */
    public function handleUserLogout($event)
    {
<<<<<<< HEAD
        $user = $event->user;

        Log::debug('Logout Success. '.$user->name);
=======
        if(\Auth::guard('donator')->check()){
            $user = $event->user;

            Log::warning('Logout Success: '.$user->donator_name.', IP:'.request()->getClientIp());
        }else{
            $user = $event->user;
    
            Log::warning('Logout Success. '.$user->name.', IP:'.request()->getClientIp());
        }
>>>>>>> 5ab062aeaaff04c04490f2e0e739d29f72016a12
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@handleUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@handleUserLogout'
        );
    }
}
