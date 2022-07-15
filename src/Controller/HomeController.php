<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function home(PostRepository $postRepository)
    {
        // Template can accept 6 posts maximum
        $posts = $postRepository->findLastXPosts(6);
        return $this->render('index.html.twig', [
            'posts' => $posts
        ]);
    }
}