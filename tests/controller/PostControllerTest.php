<?php

namespace App\Tests\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
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
}