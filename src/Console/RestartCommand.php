<?php

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
     *
     * @return void
     */
    public function handle()
    {
        $repository = $this->laravel->make(MasterSupervisorRepository::class);
        $masters    = $repository->all();

        collect($masters)->each(function ($master) {
            $queue = Str::substr($master->name, 0, -5);
            HorizonRestartJob::dispatch()->onQueue($queue);

            $this->info("Server [{$queue}] terminated");
        });
    }
}
