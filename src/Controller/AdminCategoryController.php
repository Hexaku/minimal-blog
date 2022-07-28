<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\Slugifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories', name:'admin_category_')]
class AdminCategoryController extends AbstractController
{
    #[Route('/page/{pageNumber}', name:'list', requirements: ['pageNumber' => '\d+'])]
    public function list(CategoryRepository $categoryRepository, int $pageNumber = 1)
    {
        // Get categories by page admin and total categories count
        $categories = $categoryRepository->getCategoriesByPage($pageNumber, true);
        $totalCategories = count($categories);

        // Calculate total pages count
        $totalCategoriesPerPage = $categoryRepository::ADMIN_TOTAL_CATEGORIES_PER_PAGE;
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

        return $this->render('admin/category_list.html.twig', [
            'categories' => $categories,
            'totalElements' => $totalCategories,
            'totalElementsPerPage' => $totalCategoriesPerPage,
            'totalPages' => $totalPages,
            'lastResult' => $lastResult,
            'firstResult' => $firstResult,
            'currentPageNumber' => $pageNumber,
            'path' => 'admin_category_list',
            'name' => 'categories'
        ]);
    }

    #[Route('/new', name:'new')]
    public function new(CategoryRepository $categoryRepository, Request $request, Slugifier $slugifier)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $slug = $slugifier->slugify($category->getName());
            $category->setSlug($slug);

            $categoryRepository->add($category, true);
            return $this->redirectToRoute('admin_category_list');
        }

        return $this->renderForm('admin/category_new.html.twig', [
            'categoryForm' => $form,
            'buttonLabel' => 'Create category'
        ]);
    }

    #[Route('/{slug}/edit', name:'edit')]
    public function edit(Category $category, CategoryRepository $categoryRepository, Request $request, Slugifier $slugifier)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $slug = $slugifier->slugify($category->getName());
            $category->setSlug($slug);
            $categoryRepository->add($category, true);

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->renderForm('admin/category_edit.html.twig', [
            'categoryForm' => $form,
            'category' => $category,
            'buttonLabel' => 'Edit category'
        ]);
    }

    #[Route('/{slug}/delete', name:'delete')]
    public function delete(Category $category, CategoryRepository $categoryRepository, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$category->getSlug(), $request->request->get('_token'))) {
            $categoryRepository->remove($category, true);
        }
        return $this->redirectToRoute('admin_category_list');
    }
}