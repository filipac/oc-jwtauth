<?php namespace Vdomah\JWTAuth;

use RainLab\User\Classes\AuthManager;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use System\Classes\PluginBase;
use App;
use Illuminate\Foundation\AliasLoader;
use Tymon\JWTAuth\Http\Middleware\AuthenticateAndRenew;
use Vdomah\JWTAuth\Classes\Guard;

class Plugin extends PluginBase
{
    /**
     * @var array   Require the RainLab.Blog plugin
     */
    public $require = ['RainLab.User'];

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        App::error(function($exception) {
            if($exception instanceof UnauthorizedHttpException) {
                return ['error' => 'unauthorised'];
            }
        });
        App::register(\Vdomah\JWTAuth\Classes\JWTAuthServiceProvider::class);

        $this->app->bind(\Illuminate\Contracts\Auth\Guard::class, 'user.auth');

        $facade = AliasLoader::getInstance();
        $facade->alias('JWTAuth', '\Tymon\JWTAuth\Facades\JWTAuth');
        $facade->alias('JWTFactory', '\Tymon\JWTAuth\Facades\JWTFactory');

        App::singleton(Guard::class, function() {
            return \Vdomah\JWTAuth\Classes\AuthManager::instance();
        });

        App::singleton('auth', function ($app) {
            return app(Guard::class);
        });



        $this->app['router']->middleware('jwt.auth', \Tymon\JWTAuth\Http\Middleware\Authenticate::class);
        $this->app['router']->middleware('jwt.refresh', \Tymon\JWTAuth\Http\Middleware\RefreshToken::class);
        $this->app['router']->middleware('jwt.refresh.auth', AuthenticateAndRenew::class);
    }
}
