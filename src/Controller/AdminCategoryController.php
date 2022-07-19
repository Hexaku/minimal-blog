<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories', name:'admin_category_')]
class AdminCategoryController extends AbstractController
{
    #[Route('/', name:'list')]
    public function list(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('admin/category.html.twig', [
            'categories' => $categories
        ]);
    }
}