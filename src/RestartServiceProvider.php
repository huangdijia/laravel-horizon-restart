<?php

namespace Huangdijia\Horizon;

use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\MasterSupervisor;

class RestartServiceProvider extends ServiceProvider
{
    // protected $defer = true;

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerCommands();
        }
    }

    public function register()
    {
        $this->configure();
    }

    protected function configure()
    {
        $env          = $this->app['config']['app.env'] ?? 'local';
        $environments = $this->app['config']['horizon.environments.' . $env] ?? [];
        $connection   = current($environments)['connection'] ?? 'redis';
        $queue        = MasterSupervisor::basename();

        $this->app['config']->set('horizon.environments.' . $env . '.supervisor-horizon-restart', [
            'connection' => $connection,
            'queue'      => [$queue],
            'balance'    => 'simple',
            'processes'  => 1,
            'tries'      => 3,
        ]);
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\RestartCommand::class,
            ]);
        }
    }
}
