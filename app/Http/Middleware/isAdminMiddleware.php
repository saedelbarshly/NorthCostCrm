<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class isAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    protected $openRoutes = [
        'AdminPanel/auth/*',
    ];
    public function handle($request, Closure $next)
    {
        if(!in_array($request->path(), $this->openRoutes)){
            // dd($request);
            if ($request->user() != null) {
                if ($request->user()->status == 'Archive') {
                    $request->session()->flush();
                    $request->session()->put('faild',trans('common.yourAccountDeativated'));
                    Auth::logout();
                    return redirect('login');
                }
                // if ($request->user()->role == '1') {
                    $locale = $request->user()->language;
                    if ($locale !== null && in_array($locale, config('app.locales'))) {
                        App::setLocale($locale);
                        $request->session()->put('Lang',$locale);
                    }
            
                    $theme_mode = $request->user()->theme_mode;
                    $request->session()->put('theme_mode',$theme_mode);

                    return $next($request);
                // }
            }
        }
        return redirect()->route('admin.login');
    }
}
