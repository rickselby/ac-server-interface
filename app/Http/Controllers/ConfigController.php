<?php

namespace App\Http\Controllers;

use App\Services\ConfigService;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ConfigController extends BaseController
{
    /** @var ConfigService */
    private $configService;

    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
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
        $success = $this->configService->updateServerConfig($request->get('content'));
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
        $success = $this->configService->updateEntryList($request->get('content'));
        return response()->json(['updated' => $success]);
    }

}