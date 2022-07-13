<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts', name: 'posts_')]
class PostController extends AbstractController
{
    #[Route('/', name:'list')]
    public function list(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();
        dd($posts);
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/', name:'show')]
    public function show()
    {
        return $this->render('post/show.html.twig');
    }
}