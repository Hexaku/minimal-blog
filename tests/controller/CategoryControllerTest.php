<?php

namespace App\Tests\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testListShouldReturn200()
    {
        $client = static::createClient();
        $client->request('GET', '/categories/page');

        $this->assertResponseStatusCodeSame(200);
    }

    // Every category show route should return HTTP 200
    public function testShowShouldReturn200()
    {
        $client = static::createClient();
        $categories = $client->getContainer()->get(CategoryRepository::class)->findAll();
        foreach($categories as $category){
            $client->request('GET', '/categories/' . $category->getSlug());
            $this->assertResponseStatusCodeSame(200);
        }
    }
}