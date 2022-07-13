<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(PostRepository $postRepository, CategoryRepository $categoryRepository)
    {
        $posts = $postRepository->findLastXPosts(6);
        return $this->render('index.html.twig', [
            'posts' => $posts
        ]);
    }
}