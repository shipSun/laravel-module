<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function($query) {
    		$data['sql'] = $query->sql;
    		$data['param'] = $query->bindings;
    		$data['time'] = $query->time;
    		if($query->time > 10){
    			Log::warning('待优化sql语句 ', $data);
    		}
    		if(config('app.debug')){
    			Log::debug('sql语句', $data);
    		}
    	});
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
