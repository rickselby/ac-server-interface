<?php

/**
 * Override exec() for the Services namespace - we don't want to *really* try to run things
 * while testing...
 */
namespace App\Services {
    function exec($command, array &$output = null, &$return_var = null)
    {
        $output = $command;
    }
}

namespace RickSelby\Tests {

    abstract class TestCase extends \Laravel\Lumen\Testing\TestCase
    {
        /**
         * Creates the application.
         *
         * @return \Laravel\Lumen\Application
         */
        public function createApplication()
        {
            return require __DIR__ . '/../bootstrap/app.php';
        }
    }
}
