<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\NewsletterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/new', name:'new')]
    public function new(NewsletterRepository $newsletterRepository,  Request $request)
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newsletter->setIsSent(false);
            $newsletterRepository->add($newsletter, true);
            return $this->redirectToRoute('admin_newsletter_list');
        }

        return $this->renderForm('admin/newsletter_new.html.twig', [
            'newsletterForm' => $form,
            'buttonLabel' => 'Create newsletter'
        ]);
    }

    #[Route('/{id}', name:'show')]
    public function show(Newsletter $newsletter)
    {
        return $this->render('admin/newsletter_show.html.twig', [
            'newsletter' => $newsletter
        ]);
    }
}