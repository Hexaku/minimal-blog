<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\NewsletterSubscriber;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\NewsletterSubscriberType;
use App\Repository\CommentRepository;
use App\Repository\NewsletterSubscriberRepository;
use App\Repository\PostRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts', name: 'post_')]
class PostController extends AbstractController
{
    #[Route('/page/{pageNumber}', name:'list', requirements: ['pageNumber' => '\d+'])]
    public function list(PostRepository $postRepository, int $pageNumber = 1)
    {
        // Get posts by page and total posts count
        $posts = $postRepository->getPostsByPage($pageNumber);
        $totalPosts = count($posts);

        // Calculate total pages count
        $totalPostsPerPage = $postRepository::TOTAL_POSTS_PER_PAGE;
        $totalPages = ceil($totalPosts / $totalPostsPerPage);

        // Get first result page number and last result page number
        $firstResult = 1 + ($totalPostsPerPage * ($pageNumber - 1));
        $lastResult = $totalPostsPerPage * $pageNumber;

        // Can't exceed total posts count
        if($lastResult > $totalPosts){
            $lastResult = $totalPosts;
        } 
        if($firstResult > $totalPosts){
            $firstResult = $totalPosts;
        }

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
            'totalElements' => $totalPosts,
            'totalPages' => $totalPages,
            'lastResult' => $lastResult,
            'firstResult' => $firstResult,
            'currentPageNumber' => $pageNumber,
            'path' => 'post_list',
            'name' => 'posts'
        ]);
    }

    #[Route('/{slug}', name:'show')]
    public function show(Post $post, CommentRepository $commentRepository, NewsletterSubscriberRepository $newsletterSubscriberRepository, Request $request)
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
            $comment->setIsEdited(false);

            $commentRepository->add($comment, true);
            $this->addFlash('success', 'Your comment has been post !');

            return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
        }

        $newsletterSubscriber = new NewsletterSubscriber();
        $newsletterSubscriberForm = $this->createForm(NewsletterSubscriberType::class, $newsletterSubscriber);
        $newsletterSubscriberForm->handleRequest($request);

        if($newsletterSubscriberForm->isSubmitted() && $newsletterSubscriberForm->isValid()){
            $newsletterSubscriberRepository->add($newsletterSubscriber, true);

            return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
        }

        return $this->renderForm('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'commentForm' => $commentForm,
            'buttonLabelComment' => 'Post comment',
            'newsletterSubscriberForm' => $newsletterSubscriberForm,
            'buttonLabelNewsletter' => 'Subscribe'
        ]);
    }
}