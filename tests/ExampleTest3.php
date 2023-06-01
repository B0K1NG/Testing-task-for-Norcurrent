<?php

declare(strict_types=1);

use Example\Api\Api;
use Example\Utility\Random;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    // Task 1: Check results on changing player's nickname
    public function testChangeNickname()
    {
        $api = new Api();
        $name = "Device" . Random::generateCharacters(10);
        $response = $api->openSession([
            "name" => $name,
            "platform" => 1,
            "version" => "1.0",
            "region" => "us"
        ]);

        $response = json_decode($response, true);
        $this->assertEquals("ok", $response['status'], "Failed to open session for player!" . PHP_EOL . json_encode($response, JSON_PRETTY_PRINT));
        $this->assertTrue(isset($response['data'][0]['player-id']), "Player id is not set!");
        $playerId = $response['data'][0]['player-id'];
        $sessionId = $response['data'][0]['session-id'];

        // Change player's nickname
        $newNickname = "NewNickname" . Random::generateCharacters(5);
        $responseChangeNickname = $api->setNick([
            "player-id" => $playerId,
            "nickname" => $newNickname
        ]);

        $responseChangeNickname = json_decode($responseChangeNickname, true);
        $this->assertEquals("ok", $responseChangeNickname['status'], "Failed to change player's nickname!" . PHP_EOL . json_encode($responseChangeNickname, JSON_PRETTY_PRINT));
        $this->assertEquals($newNickname, $responseChangeNickname['data'][0]['nickname'], "Nickname does not match!");

        // Clean up: Delete the player
        $responseDeletePlayer = json_decode($api->deletePlayer(["player-id" => $playerId]), true);
        $this->assertEquals("ok", $responseDeletePlayer['status'], "Failed to delete player!" . PHP_EOL . json_encode($responseDeletePlayer, JSON_PRETTY_PRINT));
    }

    // Task 2: Check results when the player's nickname is changed while playing a tournament
    public function testChangeNicknameDuringTournament()
    {
        $api = new Api();
        $name = "Device" . Random::generateCharacters(10);
        $response = $api->openSession([
            "name" => $name,
            "platform" => 1,
            "version" => "1.0",
            "region" => "us"
        ]);

        $response = json_decode($response, true);
        $this->assertEquals("ok", $response['status'], "Failed to open session for player!" . PHP_EOL . json_encode($response, JSON_PRETTY_PRINT));
        $this->assertTrue(isset($response['data'][0]['player-id']), "Player id is not set!");
        $playerId = $response['data'][0]['player-id'];
        $sessionId = $response['data'][0]['session-id'];

        // Start a tournament
        $tournamentId = "Tournament" . Random::generateCharacters(5);
        $responseStartTournament = $api->startTournament([
            "player-id" => $playerId,
            "session-id" => $sessionId,
            "tournament-id" => $tournamentId
        ]);

        $responseStartTournament = json_decode($responseStartTournament, true);
        $this->assertEquals("ok", $responseStartTournament['status'], "Failed to start tournament!" . PHP_EOL . json_encode($responseStartTournament, JSON_PRETTY_PRINT));

        // Change player's nickname
        $newNickname = "NewNickname" . Random::generateCharacters(5);
        $responseChangeNickname = $api->setNick([
            "player-id" => $playerId,
            "nickname" => $newNickname
        ]);

        $responseChangeNickname = json_decode($responseChangeNickname, true);
        $this->assertEquals("ok", $responseChangeNickname['status'], "Failed to change player's nickname!" . PHP_EOL . json_encode($responseChangeNickname, JSON_PRETTY_PRINT));
        $this->assertEquals($newNickname, $responseChangeNickname['data'][0]['nickname'], "Nickname does not match!");

        // Clean up: End the tournament and delete the player
        $responseEndTournament = json_decode($api->endTournament(["tournament-id" => $tournamentId]), true);
        $this->assertEquals("ok", $responseEndTournament['status'], "Failed to end tournament!" . PHP_EOL . json_encode($responseEndTournament, JSON_PRETTY_PRINT));

        $responseDeletePlayer = json_decode($api->deletePlayer(["player-id" => $playerId]), true);
        $this->assertEquals("ok", $responseDeletePlayer['status'], "Failed to delete player!" . PHP_EOL . json_encode($responseDeletePlayer, JSON_PRETTY_PRINT));
    }

    // Task 3: Check results when deleting leaderboards while playing a tournament
    public function testDeleteLeaderboardsDuringTournament()
    {
        $api = new Api();
        $name = "Device" . Random::generateCharacters(10);
        $response = $api->openSession([
            "name" => $name,
            "platform" => 1,
            "version" => "1.0",
            "region" => "us"
        ]);

        $response = json_decode($response, true);
        $this->assertEquals("ok", $response['status'], "Failed to open session for player!" . PHP_EOL . json_encode($response, JSON_PRETTY_PRINT));
        $this->assertTrue(isset($response['data'][0]['player-id']), "Player id is not set!");
        $playerId = $response['data'][0]['player-id'];
        $sessionId = $response['data'][0]['session-id'];

        // Start a tournament
        $tournamentId = "Tournament" . Random::generateCharacters(5);
        $responseStartTournament = $api->startTournament([
            "player-id" => $playerId,
            "session-id" => $sessionId,
            "tournament-id" => $tournamentId
        ]);

        $responseStartTournament = json_decode($responseStartTournament, true);
        $this->assertEquals("ok", $responseStartTournament['status'], "Failed to start tournament!" . PHP_EOL . json_encode($responseStartTournament, JSON_PRETTY_PRINT));

        // Delete leaderboards
        $responseDeleteLeaderboards = json_decode($api->deleteLeaderboards(), true);
        $this->assertEquals("ok", $responseDeleteLeaderboards['status'], "Failed to delete leaderboards!" . PHP_EOL . json_encode($responseDeleteLeaderboards, JSON_PRETTY_PRINT));

        // Clean up: End the tournament and delete the player
        $responseEndTournament = json_decode($api->endTournament(["tournament-id" => $tournamentId]), true);
        $this->assertEquals("ok", $responseEndTournament['status'], "Failed to end tournament!" . PHP_EOL . json_encode($responseEndTournament, JSON_PRETTY_PRINT));

        $responseDeletePlayer = json_decode($api->deletePlayer(["player-id" => $playerId]), true);
        $this->assertEquals("ok", $responseDeletePlayer['status'], "Failed to delete player!" . PHP_EOL . json_encode($responseDeletePlayer, JSON_PRETTY_PRINT));
    }

    // Task 4: Check whether player token updated when refreshing player
    public function testPlayerTokenRefresh()
    {
        $api = new Api();
        $name = "Device" . Random::generateCharacters(10);
        $response = $api->openSession([
            "name" => $name,
            "platform" => 1,
            "version" => "1.0",
            "region" => "us"
        ]);

        $response = json_decode($response, true);
        $this->assertEquals("ok", $response['status'], "Failed to open session for player!" . PHP_EOL . json_encode($response, JSON_PRETTY_PRINT));
        $this->assertTrue(isset($response['data'][0]['player-id']), "Player id is not set!");
        $playerId = $response['data'][0]['player-id'];
        $sessionId = $response['data'][0]['session-id'];

        // Refresh the player
        $responseRefreshPlayer = $api->refreshPlayer([
            "player-id" => $playerId
        ]);

        $responseRefreshPlayer = json_decode($responseRefreshPlayer, true);
        $this->assertEquals("ok", $responseRefreshPlayer['status'], "Failed to refresh player!" . PHP_EOL . json_encode($responseRefreshPlayer, JSON_PRETTY_PRINT));
        $this->assertTrue(isset($responseRefreshPlayer['data'][0]['token']), "Player token is not set!");

        // Clean up: Delete the player
        $responseDeletePlayer = json_decode($api->deletePlayer(["player-id" => $playerId]), true);
        $this->assertEquals("ok", $responseDeletePlayer['status'], "Failed to delete player!" . PHP_EOL . json_encode($responseDeletePlayer, JSON_PRETTY_PRINT));
    }

    // Task 5: Check whether player token exists when closing session
    public function testPlayerTokenOnSessionClose()
    {
        $api = new Api();
        $name = "Device" . Random::generateCharacters(10);
        $response = $api->openSession([
            "name" => $name,
            "platform" => 1,
            "version" => "1.0",
            "region" => "us"
        ]);

        $response = json_decode($response, true);
        $this->assertEquals("ok", $response['status'], "Failed to open session for player!" . PHP_EOL . json_encode($response, JSON_PRETTY_PRINT));
        $this->assertTrue(isset($response['data'][0]['player-id']), "Player id is not set!");
        $playerId = $response['data'][0]['player-id'];
        $sessionId = $response['data'][0]['session-id'];

        // Close the session
        $responseCloseSession = $api->closeSession([
            "session-id" => $sessionId
        ]);

        $responseCloseSession = json_decode($responseCloseSession, true);
        $this->assertEquals("ok", $responseCloseSession['status'], "Failed to close session!" . PHP_EOL . json_encode($responseCloseSession, JSON_PRETTY_PRINT));
        $this->assertTrue(isset($responseCloseSession['data'][0]['token']), "Player token is not set!");

        // Clean up: Delete the player
        $responseDeletePlayer = json_decode($api->deletePlayer(["player-id" => $playerId]), true);
        $this->assertEquals("ok", $responseDeletePlayer['status'], "Failed to delete player!" . PHP_EOL . json_encode($responseDeletePlayer, JSON_PRETTY_PRINT));
    }

    // Task 6: Check if it's possible to create a player when the platform doesn't exist
    public function testCreatePlayerWithInvalidPlatform()
    {
        $api = new Api();
        $name = "Device" . Random::generateCharacters(10);
        $invalidPlatformId = 9999; // Assuming platform ID 9999 doesn't exist

        $response = $api->openSession([
            "name" => $name,
            "platform" => $invalidPlatformId,
            "version" => "1.0",
            "region" => "us"
        ]);

        $response = json_decode($response, true);
        $this->assertEquals("error", $response['status'], "Player created with invalid platform ID!" . PHP_EOL . json_encode($response, JSON_PRETTY_PRINT));
        $this->assertEquals("Invalid platform ID", $response['message'], "Invalid error message!");

        // Clean up: No need to delete the player as it should not be created
    }
}
