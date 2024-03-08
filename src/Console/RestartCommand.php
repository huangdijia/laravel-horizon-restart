<?php

declare(strict_types=1);
/**
 * This file is part of laravel-horizon-restart.
 *
 * @link     https://github.com/huangdijia/laravel-horizon-restart
 * @document https://github.com/huangdijia/laravel-horizon-restart/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */

namespace Huangdijia\Horizon\Console;

use Huangdijia\Horizon\Jobs\HorizonRestartJob;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;

class RestartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'horizon:restart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart the Horizon supervisors';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /** @var MasterSupervisorRepository $repository */
        $repository = $this->laravel->make(MasterSupervisorRepository::class);
        $masters = $repository->all();

        $env = app('config')['app.env'] ?? 'local';
        $connection = app('config')['horizon.environments.' . $env . '.supervisor-horizon-restart.connection'] ?? 'redis';

        collect($masters)->each(function ($master) use ($connection) {
            $queue = Str::substr($master->name, 0, -5);
            HorizonRestartJob::dispatch()->onQueue($queue)->onConnection($connection);

            $this->info("Server [{$queue}] terminated");
        });

        $this->info('Horizon restarted successfully.');
    }
}
