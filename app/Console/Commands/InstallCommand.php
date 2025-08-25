<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'shop:install';

    protected $description = 'Shop Installation';

    public function handle()
    {
        $this->call('storage:link');
        $this->call('migrate');
        return self::SUCCESS;
    }
}
