<?php

namespace Modules\Cashflow\Http\Middleware;

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
            //Cashflow menu

            // Separator: Donasi
            $menu->add('Donasi', [
                'class' => 'c-sidebar-nav-title',
            ])
            ->data([
                'order'         => 2,
                'permission'    => ['view_Donations'],
            ]);

            $menu->add('<i class="fas fa-user-tie c-sidebar-nav-icon"></i> '.trans('menu.Cashflow.Donations'), [
                'route' => 'backend.Donations.index',
                'class' => 'c-sidebar-nav-item',
            ])
            ->data([
                'order' => 3,
                'activematches' => ['admin/Donations*'],
                'permission' => ['view_Donations'],
            ])
            ->link->attr([
                'class' => 'c-sidebar-nav-link',
            ]);
            
        })->sortBy('order');

        return $next($request);
    }
}
