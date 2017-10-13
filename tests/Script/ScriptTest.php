<?php

namespace RickSelby\Tests\Script;

use App\Services\ScriptService;
use RickSelby\Tests\TestCase;

class ScriptTest extends TestCase
{
    /** @var ScriptService */
    protected $scriptService;

    const command = '/path/to/cmd';

    public function setUp()
    {
        parent::setUp();
        putenv('AC_SERVER_SCRIPT='.self::command);
        $this->scriptService = new ScriptService();
    }

    /**
     * Test that run() runs the AC_SERVER_SCRIPT command correctly.
     */
    public function testExec()
    {
        $command = 'foo bar';
        $this->assertEquals(
            self::command.' '.$command,
            $this->scriptService->run($command)
        );
    }
}
