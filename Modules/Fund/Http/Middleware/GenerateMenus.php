<?php

namespace Modules\Fund\Http\Middleware;

use Closure;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Menu::make('admin_sidebar', function ($menu) {
            //fund menu

            $menu->add('<i class="fas fa-user-tie c-sidebar-nav-icon"></i> '.trans('menu.fund.donations'), [
                'route' => 'backend.donations.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 4,
                'activematches' => ['admin/donations*'],
                'permission' => ['view_donations'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
            
        })->sortBy('order');

        return $next($request);
    }
}
