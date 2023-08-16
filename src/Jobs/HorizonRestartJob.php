<?php

declare(strict_types=1);
/**
 * This file is part of laravel-horizon-restart.
 *
 * @link     https://github.com/huangdijia/laravel-horizon-restart
 * @document https://github.com/huangdijia/laravel-horizon-restart/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */

namespace Huangdijia\Horizon\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class HorizonRestartJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function handle()
    {
        Artisan::call('horizon:terminate');
    }
}
