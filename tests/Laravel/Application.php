<?php

namespace CrCms\Server\Tests\Laravel;

use CrCms\Server\Drivers\Laravel\Contracts\ApplicationContract;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Container\Container;
use Illuminate\Events\EventServiceProvider;

class Application extends \Illuminate\Container\Container implements ApplicationContract
{
    public static function app(): Container
    {
        $container = new \Illuminate\Container\Container();
        $container = \Mockery::mock($container);
        $container->singleton('config',function() {
            $config = require __DIR__.'/../../config/config.php';
            $config['laravel']['providers'] = EventServiceProvider::class;
            return new Repository(['swoole' => require __DIR__.'/../../config/config.php']);
        });

        $container->bind('bind_test',function(){
            return new \stdClass();
        });

        $container->singleton('singleton_test',function(){
            return new \stdClass();
        });

        $container->instance('instance_test',new \stdClass());

        return $container;
//        return $container;
//        //$container = \Mockery::mock('overload:'.\Illuminate\Container\Container::class);
//        $container = \Mockery::mock('alias:'.\Illuminate\Container\Container::class);
////        $container->shouldReceive(\Illuminate\Container\Container::class.'[setInstance]');
//        $container->shouldReceive('instance');
//        $container->shouldReceive('setInstance');
//        return $container;
        //return $container;
    }
}