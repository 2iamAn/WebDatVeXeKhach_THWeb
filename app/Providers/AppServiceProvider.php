<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // Share partner name to partner layout
        View::composer('partner.layout', function ($view) {
            $tenNhaXe = null;
            if (session('role') === 'partner' && session('user')) {
                $user = DB::table('NguoiDung')->where('MaNguoiDung', session('user')->MaNguoiDung)->first();
                if ($user) {
                    $nhaxe = DB::table('NhaXe')->where('MaNguoiDung', $user->MaNguoiDung)->first();
                    if ($nhaxe) {
                        $tenNhaXe = $nhaxe->TenNhaXe;
                    }
                }
            }
            $view->with('tenNhaXe', $tenNhaXe);
        });
    }
}
