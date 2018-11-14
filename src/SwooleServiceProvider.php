<?php

/**
 * @author simon <simon@crcms.cn>
 * @datetime 2018-11-12 20:26
 * @link http://crcms.cn/
 * @copyright Copyright &copy; 2018 Rights Reserved CRCMS
 */

namespace CrCms\Server;

use CrCms\Microservice\Server\Events\ServiceHandled;
use CrCms\Server\Listeners\RequestHandledListener;
use CrCms\Server\Listeners\CrCmsRequestHandledListener;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Http\Events\RequestHandled;
use CrCms\Microservice\Server\Events\RequestHandled as CrCmsRequestHandled;

/**
 * Class ServerServiceProvider
 * @package CrCms\Server
 */
class SwooleServiceProvider extends ServiceProvider
{
    /**
     * @var string
     */
    protected $packagePath = __DIR__ . '/../';

    /**
     * @var string
     */
    protected $name = 'swoole';

    /**
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([
            $this->packagePath . 'config/config.php' => config_path($this->name . '.php'),
        ]);

        $this->registerEventListener();
    }

    /**
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            $this->packagePath . "config/config.php", $this->name
        );
    }

    /**
     * @return void
     */
    protected function registerEventListener(): void
    {
        if (class_exists(CrCmsRequestHandled::class)) {
            $this->app['events']->listen(CrCmsRequestHandled::class, CrCmsRequestHandledListener::class);
        }
        if (class_exists(RequestHandled::class)) {
            $this->app['events']->listen(RequestHandled::class, RequestHandledListener::class);
        }
    }
}