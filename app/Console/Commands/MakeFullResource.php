<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeFullResource extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:full_resource
        {resource : The resource to create files for.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $resource = ucfirst($this->argument('resource'));
        $singular = Str::singular($resource);
        $plural = Str::plural($resource);

        $this->call('make:model', [
            'name' => $singular,
            '--all' => true,
        ]);

        $this->call('make:resource', [
            'name' => $singular,
        ]);

        $this->call('make:resource', [
            'name' => $plural,
            '--collection' => true
        ]);
        return 0;
    }
}
