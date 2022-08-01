<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment', name:'comment_')]
class CommentController extends AbstractController
{
    #[Route('/{id}/edit', name:'edit')]
    public function edit(Comment $comment, CommentRepository $commentRepository, Request $request): Response
    {
        // Only author of the comment can edit it (CommentVoter)
        $this->denyAccessUnlessGranted('EDIT', $comment);

        $post = $comment->getPost();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setIsEdited(true);
            $commentRepository->add($comment, true);
            $this->addFlash('success', 'Your comment has been updated');

            return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'commentForm' => $form,
            'post' => $post,
            'buttonLabelComment' => 'Edit comment'
        ]);
    }

    #[Route('/{id}/delete', name:'delete')]
    public function delete(Comment $comment, CommentRepository $commentRepository, Request $request): Response
    {
        $post = $comment->getPost();
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
            if($this->isGranted('ROLE_ADMIN')){
                $this->addFlash('success', 'This comment has been deleted');
            } else {
                $this->addFlash('success', 'Your comment has been deleted');

            }
        }
        return $this->redirectToRoute('post_show', ['slug' => $post->getSlug()]);
    }
}