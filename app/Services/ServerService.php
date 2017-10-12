<?php

namespace App\Services;

use Illuminate\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;

class ServerService extends ServerBase
{
    /** @var ScriptService */
    private $scriptService;

    const logFile = 'acServer.log';

    /*
     * Messages we expect from the script
     */
    protected $running = 'Server is Running';
    protected $notRunning = 'Server is Not Running';

    public function __construct(LoggerInterface $log, Filesystem $file, ScriptService $scriptService)
    {
        parent::__construct($log, $file);
        $this->scriptService = $scriptService;
    }

    /**
     * Start the server
     */
    public function start()
    {
        $this->scriptService->run('start');
        // Log the action
        $this->log->info('Assetto Corsa Server: started');
    }

    /**
     * Stop the server
     */
    public function stop()
    {
        $this->scriptService->run('stop');
        // Log the action
        $this->log->info('Assetto Corsa Server: stopped');
    }

    /**
     * Get the status of the server
     * @return string
     */
    public function status()
    {
        $out = $this->scriptService->run('status');
        return $out[0];
    }

    /**
     * Check if the server is running
     * @return bool
     */
    public function isRunning()
    {
        return $this->status() == $this->running;
    }

    /**
     * Check if the server is stopped
     * @return bool
     */
    public function isStopped()
    {
        return $this->status() == $this->notRunning;
    }

    /**
     * Get the server log file
     *
     * @return string
     */
    public function getLogFile()
    {
        $path = $this->fixPath(env('AC_SERVER_LOG_PATH')).self::logFile;
        if (!$this->file->exists($path)) {
            $path .= '.last';
            if (!$this->file->exists($path)) {
                return '';
            }
        }

        return $this->file->get($path);
    }
}
