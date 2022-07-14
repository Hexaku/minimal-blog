<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'list')]
    public function list(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/{slug}', name: 'show')]
    public function show(Category $category, EntityManagerInterface $manager)
    {
        $categoryRepository = $manager->getRepository(Category::class);
        $posts = $categoryRepository->findAllLatestPosts($category->getId());
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'posts' => $posts
        ]);
    }
}