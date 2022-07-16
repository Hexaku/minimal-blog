<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function home(PostRepository $postRepository)
    {
        // Current home template will display 6 posts maximum
        $posts = $postRepository->findLastXPosts(6);
        return $this->render('index.html.twig', [
            'posts' => $posts
        ]);
    }
}