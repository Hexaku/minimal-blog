<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testIndexShouldReturn200()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testLoginWithValidCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'admin',
            '_password' => 'admin'
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'admin',
            '_password' => 'bad_password'
        ]);
        $client->submit($form);

        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('.text-red-600', 'Invalid credentials.');
    }
}