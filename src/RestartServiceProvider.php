<?php

namespace Huangdijia\Horizon;

use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\MasterSupervisor;

class RestartServiceProvider extends ServiceProvider
{
    // protected $defer = true;

    public function boot()
    {
    }

    public function register()
    {
        $this->configure();
        $this->registerCommands();
    }

    protected function configure()
    {
        $environments = config('horizon.environments.' . config('app.env'), []);
        $connection   = current($environments)['connection'] ?? 'redis';
        $queue        = MasterSupervisor::basename();

        app('config')->set('horizon.environments.' . config('app.env') . 'supervisor-horizon-restart', [
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
