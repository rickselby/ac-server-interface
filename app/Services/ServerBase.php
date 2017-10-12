<?php

namespace App\Services;

use Psr\Log\LoggerInterface;
use Illuminate\Filesystem\Filesystem;

abstract class ServerBase
{
    /** @var LoggerInterface */
    protected $log;

    /** @var Filesystem */
    protected $file;

    /**
     * ServerService constructor. Initialise the requirements
     *
     * @param LoggerInterface $log
     * @param Filesystem $file
     */
    public function __construct(LoggerInterface $log, Filesystem $file)
    {
        $this->log = $log;
        $this->file = $file;
    }

    /**
     * Check the trailing slash exists on a path
     *
     * @param $path
     *
     * @return string
     */
    protected function fixPath($path)
    {
        return (substr($path,-1) != DIRECTORY_SEPARATOR) ? $path.DIRECTORY_SEPARATOR : $path;
    }
}
