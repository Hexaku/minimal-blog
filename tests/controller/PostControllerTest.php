<?php

namespace App\Tests\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class PostControllerTest extends WebTestCase
{
    public function getLoggedUserClient()
    {
        // Login as user 
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Login')->form([
            '_username' => 'chopin@minimal.com',
            '_password' => 'user'
        ]);
        $client->submit($form);
        $client->followRedirect();
        return $client;
    }

    public function testListShouldReturn200()
    {
        $client = static::createClient();
        $client->request('GET', '/posts/page');

        $this->assertResponseStatusCodeSame(200);
    }

    // Every post show route should return HTTP 200
    public function testShowShouldReturn200()
    {
        $client = static::createClient();
        $posts = $client->getContainer()->get(PostRepository::class)->findAll();
        foreach($posts as $post){
            $client->request('GET', '/posts/' . $post->getSlug());
            $this->assertResponseStatusCodeSame(200);
        }
    }

    public function testShowShouldDisplayLogin()
    {
        $client = static::createClient();
        $posts = $client->getContainer()->get(PostRepository::class)->findAll();
        foreach($posts as $post){
            $client->request('GET', '/posts/' . $post->getSlug());
            $this->assertSelectorTextContains('#comment_login', 'You need to be connected to post a comment !');
        }
    }

    public function testCreateComment()
    {
        $client = $this->getLoggedUserClient();

        // Get one post page
        $posts = $client->getContainer()->get(PostRepository::class)->findAll();
        $post = $posts[0];
        $crawler = $client->request('GET', '/posts/' . $post->getSlug());
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('#thoughts', 'What are your thoughts');

        // Submit comment
        $form = $crawler->selectButton('Post comment')->form([
            'comment[content]' => 'Nice post, I love it !'
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
    }

    public function testDeleteComment()
    {
        $client = $this->getLoggedUserClient();

        // Get one post page
        $posts = $client->getContainer()->get(PostRepository::class)->findAll();
        $post = $posts[0];

        // Removing comment
        $crawler = $client->request('GET', '/posts/' . $post->getSlug());
        $form = $crawler->selectButton('Delete')->form();
        $client->submit($form);
        $this->assertResponseStatusCodeSame(302);
        $client->followRedirect();
        $this->assertResponseStatusCodeSame(200);
    }
}