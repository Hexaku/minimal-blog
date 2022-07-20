<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\Slugifier;
use DateTime;
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
        return $this->render('admin/post.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(PostRepository $postRepository, Request $request, Slugifier $slugifier)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $slug = $slugifier->slugify($post->getTitle());
            $post->setSlug($slug);
            $post->setCreatedAt(new DateTime());

            $postRepository->add($post, true);

            return $this->redirectToRoute('admin_post_list');
        }

        return $this->renderForm('admin/post_new.html.twig', [
            'postForm' => $form,
            'buttonLabel' => 'Create post'
        ]);
    }
}