<?php

namespace RickSelby\Tests\Api;

use RickSelby\Tests\TestCase;

class ApiTest extends TestCase
{

    public function testAuth()
    {
        putenv('MASTER_IP=foo');
        $response = $this->json('GET', '/ping');
        $response->assertResponseStatus(401);
    }

    public function testPing()
    {
        $this->setIP();
        $this->json('GET', '/ping')
            ->seeStatusCode(200)
            ->seeJson(['success' => true]);
    }
/*
    public function testConfig()
    {
        $this->setIP();
        $response = $this->json('PUT', '/config/server', ['content' => 'foo']);
        $response->assertResponseStatus(200);
        $response->assertJson(json_encode(['updated' => true]));
    }
*/
    public function testStart()
    {
        $this->setIP();
        $this->json('PUT', '/start')
            ->seeStatusCode(200)
            ->seeJson(['success' => false]);
    }

    public function testStop()
    {
        $this->setIP();
        $this->json('PUT', '/stop')
            ->seeStatusCode(200)
            ->seeJson(['success' => false]);
    }

    public function testRunning()
    {
        $this->setIP();
        $this->json('GET', '/running')
            ->seeStatusCode(200)
            ->seeJson(['running' => false]);
    }

    /**********************************************************/

    protected function setIP()
    {
        putenv('MASTER_IP=127.0.0.1');
    }
}