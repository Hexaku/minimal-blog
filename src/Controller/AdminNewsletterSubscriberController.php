<?php

namespace App\Controller;

use App\Repository\NewsletterSubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/newsletter/subscribers', name:'admin_newsletter_subscriber_')]
class AdminNewsletterSubscriberController extends AbstractController
{
    #[Route('/', name:'list')]
    public function list(NewsletterSubscriberRepository $newsletterSubscriberRepository)
    {
        $newsletterSubscribers = $newsletterSubscriberRepository->findAll();
        return $this->render('admin/newsletter_subscriber_list.html.twig', [
            'newsletterSubscribers' => $newsletterSubscribers
        ]);
    }
}