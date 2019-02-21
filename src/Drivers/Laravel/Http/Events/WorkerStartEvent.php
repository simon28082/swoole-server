<?php

namespace CrCms\Server\Drivers\Laravel\Http\Events;

use CrCms\Server\Drivers\Laravel\Http\Server;
use CrCms\Server\Server\Events\WorkerStartEvent as BaseWorkerStartEvent;
use Illuminate\Contracts\Http\Kernel;

class WorkerStartEvent extends BaseWorkerStartEvent
{
    /**
     * @var Server
     */
    protected $server;

    /**
     * handle kernel
     *
     * @return void
     */
    public function handle(): void
    {
        parent::handle();

        $this->clearOpcache();

        $app = $this->server->getApplication();

        $kernel = $app->make(Kernel::class);
        $kernel->bootstrap();

        //preload sharing instance
        $this->server->getLaravel()->preload();

        $this->server->getContainer()->make('events')->dispatch('worker_start', [$this->server, $app]);
    }

    /**
     * Clear APC or OPCache.
     */
    protected function clearOpcache()
    {
        if (extension_loaded('apc')) {
            apc_clear_cache();
        }

        if (extension_loaded('Zend OPcache')) {
            opcache_reset();
        }
    }
}
