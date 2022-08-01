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
            '_username' => 'admin@minimal.com',
            '_password' => 'admin'
        ]);
        $client->submit($form);

        // Redirected to home page as admin
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('#welcome', 'Welcome Admin');
    }

    public function testLoginWithBadCredentials()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'admin@minimal.com',
            '_password' => 'bad_password'
        ]);
        $client->submit($form);

        // Redirected to login page with error message
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('.text-red-600', 'Invalid credentials.');
    }

    // This test could break other tests, try it separately !
    /*
    public function testLoginWithBruteForce()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'admin@minimal.com',
            '_password' => 'bad_password_again'
        ]);

        // Brute force form 20 times
        for($i=0; $i<20; $i++){
            $client->submit($form);
        }
        
        $this->assertResponseStatusCodeSame(302);
        $this->assertResponseRedirects('http://localhost/login');
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('.text-red-600', 'Too many failed login attempts, please try again in 15 minutes.');
    }
    */
}