<?php

namespace App\Console\Commands;

use App\Token;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:token  {name : name the token}
                                        {user : the user id of token owner}
                                        {description? : describe the token}
                                        {l? : the apis limit / min}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes a new API Token';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Creating a new Access Token');
        $token = (string)Str::uuid();

        DB::table('user_tokens')->insert([
            'user_id' => $this->argument('user'),
            'access_token' => hash('sha256', $token),
            'expires_at' => now()->subDays(30),
        ]);

        $this->info('The Token has been made');
        $this->line('Token is: ' . $token);
        $this->error('This is the only time you will see this token, so keep it');
    }
}
