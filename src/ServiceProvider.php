<?php
declare (strict_types=1);

namespace LanDao\LaravelCore;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use LanDao\LaravelCore\Annotation\InteractsWithInject;
use LanDao\LaravelCore\Annotation\InteractsWithRoute;
use LanDao\LaravelCore\Contracts\ModuleRepositoryInterface;
use LanDao\LaravelCore\Module\Module;
use LanDao\LaravelCore\Providers\ConsoleServiceProvider;
use LanDao\LaravelCore\Sanctum\PersonalAccessToken;
use LanDao\LaravelCore\Services\Captcha\Image\Captcha;
use LanDao\LaravelCore\Services\Captcha\Contracts\Captcha as CaptchaContract;
use Laravel\Sanctum\Sanctum;

class ServiceProvider extends LaravelServiceProvider
{
    use InteractsWithInject, InteractsWithRoute;

    public function boot(): void
    {
        $this->setupConfig();
        //sanctum 令牌缓存
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        //自动注入
        $this->autoInject();
        //路由注解
        $this->registerRoutes();
    }

    protected function setupConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('landao.php'),
            ], 'config');
        }
    }

    public function register(): void
    {
        $this->registerBindings();
        $this->registerProviders();
    }

    protected function registerBindings(): void
    {
        /**
         * 绑定图形验证码
         */
        $this->app->bind(CaptchaContract::class, function () {
            $captcha = new Captcha();
            $config = collect(config('landao.captcha'))->filter(function ($value) {
                return !empty($value);
            })->toArray();
            $captcha->withConfig($config);
            return $captcha;
        });
        $this->app->singleton(ModuleRepositoryInterface::class, function ($app) {
            return new Module($app, base_path('module'));
        });
        $this->app->alias(ModuleRepositoryInterface::class, 'modules');
    }

    /**
     * 注册服务
     * @return void
     */
    protected function registerProviders(): void
    {
        $this->app->register(ConsoleServiceProvider::class);
    }
}
