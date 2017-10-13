<?php

namespace App\Services;

class ScriptService
{
    /**
     * Run a server command
     *
     * @param $cmd
     *
     * @return mixed
     */
    public function run($cmd)
    {
        exec(env('AC_SERVER_SCRIPT').' '.$cmd, $out);
        return $out;
    }
}
