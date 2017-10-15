<?php

namespace App\Console\Commands;

use App\Services\ServerService;
use Illuminate\Console\Command;

class CheckForResultsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'server:results-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for new results';

    /**
     * Execute the console command.
     */
    public function handle(ServerService $serverService)
    {
        $serverService->checkForResults();
    }
}
