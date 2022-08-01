<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    public function getClientLogger(string $username, string $password)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form([
            '_username' => $username,
            '_password' => $password
        ]);
        $client->submit($form);
        return $client;
    }

    public function testRedirectedIfNotLogged()
    {
        $client = static::createClient();
        $client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testAccessDeniedIfUserRole()
    {
        // Loggin as user
        $client = $this->getClientLogger('chopin@minimal.com', 'user');

        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);

        // Try accessing /admin as user without ROLE_ADMIN
        $crawler = $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(403);
    }

    public function testAccessAuthorizedIfAdminRole()
    {
        // Loggin as admin
        $client = $this->getClientLogger('admin@minimal.com', 'admin');
        
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);

        // Try accessing /admin
        $client->request('GET', '/admin');

        $this->assertResponseStatusCodeSame(301);
        $this->assertResponseRedirects('http://localhost/admin/');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('#hello', 'Hello Admin');
    }
}