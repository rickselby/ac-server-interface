<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

class ServerService
{
    /** @var LoggerInterface */
    private $log;
    /** @var ScriptService */
    private $scriptService;

    // Possible log files, in the order we wish to check them
    const LOG_FILES = [
        'acServer.log',
        'acServer.log.last',
    ];

    /*
     * Messages we expect from the script
     */
    const MSG_RUNNING = 'Server is Running';
    const MSG_NOT_RUNNING = 'Server is Not Running';

    public function __construct(LoggerInterface $log, ScriptService $scriptService)
    {
        $this->log = $log;
        $this->scriptService = $scriptService;
    }

    /**
     * Start the server.
     */
    public function start()
    {
        $this->scriptService->run('start');
        // Log the action
        $this->log->info('Assetto Corsa Server: started');
    }

    /**
     * Stop the server.
     */
    public function stop()
    {
        $this->scriptService->run('stop');
        // Log the action
        $this->log->info('Assetto Corsa Server: stopped');
    }

    /**
     * Get the status of the server.
     *
     * @return string
     */
    public function status()
    {
        $out = $this->scriptService->run('status');

        return $out[0];
    }

    /**
     * Check if the server is running.
     *
     * @return bool
     */
    public function isRunning()
    {
        return $this->status() == self::MSG_RUNNING;
    }

    /**
     * Check if the server is stopped.
     *
     * @return bool
     */
    public function isStopped()
    {
        return $this->status() == self::MSG_NOT_RUNNING;
    }

    /**
     * Get the server log file.
     *
     * @return string
     */
    public function getLogFile()
    {
        foreach (self::LOG_FILES AS $logFile) {
            if (\Storage::disk('ac_server')->exists($logFile)) {
                return \Storage::disk('ac_server')->get($logFile);
            }
        }

        return '';
    }
}
