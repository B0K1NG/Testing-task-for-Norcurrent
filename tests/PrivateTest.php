<?php
declare(strict_types=1);

use Example\Api\Api;
use Example\Utility\Random;
use PHPUnit\Framework\TestCase;

class PrivateTest extends TestCase
{
    
    public function testOpenSessionWithUnknownPlatform()
    {
        $api = new Api();
        $name =  "Device". Random::generateCharacters(10);
        $response = $api->openSession(array(
            "name" => $name,
            "platform" => 9,
            "version" => "1.0",
            "region" => "lt"
        ));
        $response = json_decode($response, true);
        $this->assertEquals("fail", $response['status'], "Successfuly opened session for player, when it shouldn't!".PHP_EOL. json_encode($response, JSON_PRETTY_PRINT));
    }

    public function testOpenSessionFail()
    {
        $api = new Api();
        $name =  "Device". Random::generateCharacters(10);
        $response = $api->openSession(array());
        $response = json_decode($response, true);
        $this->assertEquals("fail", $response['status'], "Successfuly opened session for player, when it shouldn't!".PHP_EOL. json_encode($response, JSON_PRETTY_PRINT));
        $this->assertFalse(isset($response['data'][0]['player-id']), "Player id is set!");
    }

    public function testPlayerExistsInAllPlayersList()
    {
        $api = new Api();
        $name =  "Device". Random::generateCharacters(10);
        $response = $api->openSession(array(
            "name" => $name,
            "platform" => 1,
            "version" => "1.0",
            "region" => "lt"
        ));
        $response = json_decode($response, true);
        $this->assertEquals("ok", $response['status'], "Failed to open session for player!".PHP_EOL. json_encode($response, JSON_PRETTY_PRINT));
        $this->assertTrue(isset($response['data'][0]['player-id']), "Player id is not set!");
        $playerId = $response['data'][0]['player-id'];
        $responseGetAll = json_decode($api->getAllPlayer(),true);
        $key = array_search($playerId, array_column($responseGetAll['data'][0]['players'], "player-id"));
        $this->assertNotEquals(false, $key, "Failed to found player on list!");
        
    }
}