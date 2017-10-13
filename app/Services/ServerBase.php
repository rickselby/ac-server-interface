<?php

namespace App\Services;

use Psr\Log\LoggerInterface;

abstract class ServerBase
{
    /** @var LoggerInterface */
    protected $log;

    /**
     * ServerService constructor. Initialise the requirements.
     *
     * @param LoggerInterface $log
     */
    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    /**
     * Check the trailing slash exists on a path.
     *
     * @param $path
     *
     * @return string
     */
    protected function fixPath($path)
    {
        return (substr($path, -1) != DIRECTORY_SEPARATOR) ? $path.DIRECTORY_SEPARATOR : $path;
    }
}
