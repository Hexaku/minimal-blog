<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/page/{pageNumber}', name:'list', requirements: ['pageNumber' => '\d+'])]
    public function list(CategoryRepository $categoryRepository, int $pageNumber = 1): Response
    {
        // Get categories by page and total categories count
        $categories = $categoryRepository->getCategoriesByPage($pageNumber);
        $totalCategories = count($categories);

        // Calculate total pages count
        $totalCategoriesPerPage = $categoryRepository::TOTAL_CATEGORIES_PER_PAGE;
        $totalPages = ceil($totalCategories / $totalCategoriesPerPage);

        // Get first result page number and last result page number
        $firstResult = 1 + ($totalCategoriesPerPage * ($pageNumber - 1));
        $lastResult = $totalCategoriesPerPage * $pageNumber;

        // Can't exceed total categories count
        if($lastResult > $totalCategories){
            $lastResult = $totalCategories;
        } 
        if($firstResult > $totalCategories){
            $firstResult = $totalCategories;
        }

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'totalElements' => $totalCategories,
            'totalPages' => $totalPages,
            'lastResult' => $lastResult,
            'firstResult' => $firstResult,
            'currentPageNumber' => $pageNumber,
            'path' => 'category_list',
            'name' => 'categories'
        ]);
    }

    #[Route('/{slug}', name: 'show')]
    public function show(Category $category, EntityManagerInterface $manager): Response
    {
        $categoryRepository = $manager->getRepository(Category::class);
        $posts = $categoryRepository->findAllLatestPosts($category->getId());
        return $this->render('category/show.html.twig', [
            'category' => $category,
            'posts' => $posts
        ]);
    }
}