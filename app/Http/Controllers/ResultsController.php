<?php

namespace App\Http\Controllers;

use App\Services\ResultsService;
use Laravel\Lumen\Routing\Controller as BaseController;

class ResultsController extends BaseController
{
    /** @var ResultsService */
    private $resultsService;

    public function __construct(ResultsService $resultsService)
    {
        $this->resultsService = $resultsService;
    }

    /**
     * Get the last results file from the server.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function results()
    {
        return response()->json(['results' => $this->resultsService->getLatestResults()]);
    }

    /**
     * Get all results files from the server.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allResults()
    {
        return response()->json($this->resultsService->getAllResults());
    }
}
