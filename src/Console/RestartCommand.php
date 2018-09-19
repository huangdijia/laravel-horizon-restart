<?php

namespace Huangdijia\Horizon\Console;

use Illuminate\Console\Command;
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
     *
     * @return void
     */
    public function handle()
    {
        $repository = app(MasterSupervisorRepository::class);
        $masters    = $repository->all();
        collect($masters)->each(function () {
            $queue = substr($master->name, 0, -5);
            HorizonRestartJob::dispatch()->onQueue($queue);
            $this->info("Restarting machine '{$queue}'");
        });
    }
}
