<?php

namespace App\Http\Controllers;

use App\Services\ServerService;
use Illuminate\Filesystem\Filesystem;
use Laravel\Lumen\Routing\Controller as BaseController;

class ServerController extends BaseController
{
    /** @var ServerService */
    protected $serverService;

    public function __construct(ServerService $serverService)
    {
        $this->serverService = $serverService;
    }

    /**
     * Check that the lumen API is up and running
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ping()
    {
        return response()->json(['success' => true]);
    }

    /**
     * Start the server
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function start()
    {
        $this->serverService->start();
        return response()->json(['success' => $this->serverService->isRunning()]);
    }

    /**
     * Stop the server
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stop()
    {
        $this->serverService->stop();
        return response()->json(['success' => $this->serverService->isStopped()]);
    }

    /**
     * Find out if the server is running
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function running()
    {
        return response()->json(['running' => $this->serverService->isRunning()]);
    }

    /**
     * Get the AC server log
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function serverLog()
    {
        return response()->json(['log' => $this->serverService->getLogFile()]);
    }

    /**
     * Get the client system logs
     *
     * @param Filesystem $filesystem
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function systemLog(Filesystem $filesystem)
    {
        $logs = [];
        foreach($filesystem->files(storage_path('logs')) AS $file) {
            $logs[basename($file)] = $filesystem->get($file);
        }
        return response()->json($logs);
    }
}