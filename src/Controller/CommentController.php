<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment', name:'comment_')]
class CommentController extends AbstractController
{
    #[Route('/{id}/edit', name:'edit')]
    public function edit(Comment $comment, CommentRepository $commentRepository, Request $request)
    {
        $post = $comment->getPost();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $commentRepository->add($comment, true);
            return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'commentForm' => $form,
            'post' => $post,
            'buttonLabel' => 'Edit comment'
        ]);
    }
}