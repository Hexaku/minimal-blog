<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function show(Post $post, CommentRepository $commentRepository, Request $request)
    {
        $comments = $commentRepository->findAllLatestCommentsByPost($post->getId());

        // A user can post a comment under every post
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()){
            $comment->setAuthor($this->getUser());
            $comment->setCreatedAt(new DateTime());
            $comment->setPost($post);

            $commentRepository->add($comment, true);

            return $this->redirectToRoute('posts_show', ['slug' => $post->getSlug()]);
        }

        return $this->renderForm('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'commentForm' => $commentForm,
            'buttonLabel' => 'Post Comment'
        ]);
    }
}