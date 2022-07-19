<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories', name:'admin_category_')]
class AdminCategoryController extends AbstractController
{
    #[Route('/', name:'list')]
    public function list()
    {
        return $this->render('admin/category.html.twig');
    }
}