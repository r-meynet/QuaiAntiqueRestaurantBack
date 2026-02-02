<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    public function testApiDocUrlIsSuccessful(): void
    {
        $client = self::createClient();
        $client->request('GET', 'api/doc');

        self::assertResponseIsSuccessful();
    }

    public function testApiAccountUrlIsSecure(): void
    {
        $client = self::createClient();
        $client->request('GET', 'api/account/me');

        self::assertResponseStatusCodeSame(401);
    }

    // ATTENTION POUR CE TEST
    // Il retournera une failure d'assertion car il n'y a pas l'utilisateur en base de données
    // Cette méthode de test est donnée à titre d'exemple
    public function testLoginRouteCanConnectAValidUser(): void
    {
        $client = self::createClient();
        $client->request('POST', 'api/login', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'username' => 'toto@toto.fr',
            'password' => 'toto'
        ], JSON_THROW_ON_ERROR));

        $statusCode = $client->getResponse()->getStatusCode();
        $this->assertEquals(200, $statusCode);

        $content = $client->getResponse()->getContent();
        $this->assertStringContainsString('user', $content);
    }
}
