<?php

/**
 * Override storage_path() for the Services namespace so we can inject the vfs filesystem
 */
namespace App\Services {
    function storage_path($path = '') {
        return 'vfs://directory'.DIRECTORY_SEPARATOR.$path;
    }
}

namespace RickSelby\Tests {

    use org\bovigo\vfs\vfsStream;
    use org\bovigo\vfs\vfsStreamDirectory;

    abstract class BaseSetup extends TestCase
    {
        /** @var vfsStreamDirectory */
        protected $vfsRoot;

        public function setUp()
        {
            parent::setUp();
            $this->vfsRoot = vfsStream::setup('directory');
        }
    }
}