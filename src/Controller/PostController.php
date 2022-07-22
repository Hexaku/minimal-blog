<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts', name: 'posts_')]
class PostController extends AbstractController
{
    #[Route('/', name:'list')]
    public function list(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/{slug}', name:'show')]
    public function show(Post $post, CommentRepository $commentRepository)
    {
        $comments = $commentRepository->findAllLatestCommentsByPost($post->getId());
        $today = new DateTime();
        //$today->diff($comments[0]->getCreatedAt()
        //dd($today->diff($comments[0]->getCreatedAt()));
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }
}