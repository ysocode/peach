<?php

namespace YSOCode\Peach\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peach:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish the YSOCode Peach Docker files';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'peach-docker']);
        $this->call('vendor:publish', ['--tag' => 'peach-database']);

        file_put_contents(
            $this->laravel->basePath('docker-compose.yml'),
            str_replace(
                [
                    './vendor/ysocode/peach/runtimes/8.3',
                    './vendor/ysocode/peach/runtimes/8.2',
                    './vendor/ysocode/peach/runtimes/8.1',
                    './vendor/ysocode/peach/runtimes/8.0',
                    './vendor/ysocode/peach/database/mysql',
                    './vendor/ysocode/peach/database/pgsql'
                ],
                [
                    './docker/8.3',
                    './docker/8.2',
                    './docker/8.1',
                    './docker/8.0',
                    './docker/mysql',
                    './docker/pgsql'
                ],
                file_get_contents($this->laravel->basePath('docker-compose.yml'))
            )
        );
    }
}
