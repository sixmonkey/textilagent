<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class IssueToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'issue:token
        {id : The id of the user.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Issue a bearer token for a user';

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
        $id = ucfirst($this->argument('id'));
        echo User::find($id)->createToken('AuthToken')->plainTextToken;
        return 0;
    }
}
