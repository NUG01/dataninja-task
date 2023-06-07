<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired access tokens from the table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // $expiredTokens = AccessToken::where('expires_at', '<', Carbon::now())->delete();
        // $this->info("Deleted {$expiredTokens} expired tokens.");
    }
}
