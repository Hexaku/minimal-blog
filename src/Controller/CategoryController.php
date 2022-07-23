<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'category_')]
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