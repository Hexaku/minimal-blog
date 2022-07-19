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
    #[Route('/', name:'list')]
    public function list(CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();
        return $this->render('admin/category.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/new', name:'new')]
    public function new(CategoryRepository $categoryRepository, Request $request, Slugifier $slugifier)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted()){
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
    public function delete(Category $category, CategoryRepository $categoryRepository)
    {
        $categoryRepository->remove($category, true);
        return $this->redirectToRoute('admin_category_list');
    }
}