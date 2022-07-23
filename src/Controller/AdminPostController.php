<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\Slugifier;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/posts', name:'admin_post_')]
class AdminPostController extends AbstractController
{
    #[Route('/', name:'list')]
    public function list(PostRepository $postRepository)
    {
        $posts = $postRepository->findAll();
        return $this->render('admin/post_list.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(PostRepository $postRepository, Request $request, Slugifier $slugifier)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $slug = $slugifier->slugify($post->getTitle());
            $post->setSlug($slug);
            $post->setCreatedAt(new DateTimeImmutable());

            $postRepository->add($post, true);

            return $this->redirectToRoute('admin_post_list');
        }

        return $this->renderForm('admin/post_new.html.twig', [
            'postForm' => $form,
            'buttonLabel' => 'Create post'
        ]);
    }

    #[Route('/{slug}/edit', name:'edit')]
    public function edit(Post $post, PostRepository $postRepository, Request $request, Slugifier $slugifier)
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $slug = $slugifier->slugify($post->getTitle());
            $post->setSlug($slug);

            $postRepository->add($post, true);

            return $this->redirectToRoute('admin_post_list');
        }

        return $this->renderForm('admin/post_edit.html.twig', [
            'postForm' => $form,
            'buttonLabel' => 'Edit post'
        ]);
    }

    #[Route('/{slug}/delete', name:'delete')]
    public function delete(Post $post, PostRepository $postRepository, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$post->getSlug(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);
        }
        return $this->redirectToRoute('admin_post_list');
    }
}