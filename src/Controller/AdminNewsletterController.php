<?php

namespace App\Controller;

use App\Repository\NewsletterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/newsletters', name:'admin_newsletter_')]
class AdminNewsletterController extends AbstractController
{
    #[Route('/', name:'list')]
    public function list(NewsletterRepository $newsletterRepository)
    {
        $newsletters = $newsletterRepository->findAll();
        return $this->render('admin/newsletter_list.html.twig', [
            'newsletters' => $newsletters
        ]);
    }
}