<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UserRepository $userRepository, PostRepository $postRepository, CategoryRepository $categoryRepository)
    {
        $users = $userRepository->findAll();
        $totalUsers = count($users);

        $posts = $postRepository->findAll();
        $totalPosts = count($posts);

        $categories = $categoryRepository->findAll();
        $totalCategories = count($categories);

        return $this->render('admin/index.html.twig', [
            'totalUsers' => $totalUsers,
            'totalPosts' => $totalPosts,
            'totalCategories' => $totalCategories
        ]);
    }
}