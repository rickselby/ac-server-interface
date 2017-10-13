<?php

namespace App\Http\Controllers;

use App\Services\ServerService;
use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Laravel\Lumen\Routing\Controller as BaseController;

class ServerController extends BaseController
{
    /** @var ServerService */
    protected $server;

    public function __construct(ServerService $serverService)
    {
        $this->server = $serverService;
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
     * Accept a new config file for the server
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function config(Request $request)
    {
        $success = $this->server->updateServerConfig($request->get('content'));
        return response()->json(['updated' => $success]);
    }

    /**
     * Accept a new entry list file for the server
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function entryList(Request $request)
    {
        $success = $this->server->updateEntryList($request->get('content'));
        return response()->json(['updated' => $success]);
    }

    /**
     * Start the server
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function start()
    {
        $this->server->start();
        return response()->json(['success' => $this->server->isRunning()]);
    }

    /**
     * Stop the server
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stop()
    {
        $this->server->stop();
        return response()->json(['success' => $this->server->isStopped()]);
    }

    /**
     * Find out if the server is running
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function running()
    {
        return response()->json(['running' => $this->server->isRunning()]);
    }

    /**
     * Get the last results file from the server
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function results()
    {
        return response()->json(['results' => $this->server->getLatestResults()]);
    }

    /**
     * Get all results files from the server
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allResults()
    {
        return response()->json($this->server->getAllResults());
    }

    /**
     * Get the AC server log
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function serverLog()
    {
        return response()->json(['log' => $this->server->getLogFile()]);
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