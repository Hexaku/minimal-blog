<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Message\NewsletterEmailMessage;
use App\Repository\NewsletterRepository;
use App\Repository\NewsletterSubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Email;
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

    #[Route('/{id}/edit', name:'edit')]
    public function edit(Newsletter $newsletter, NewsletterRepository $newsletterRepository, Request $request)
    {
        $form = $this->createForm(NewsletterType::class, $newsletter);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $newsletterRepository->add($newsletter, true);
            return $this->redirectToRoute('admin_newsletter_list');
        }

        return $this->renderForm('admin/newsletter_edit.html.twig', [
            'newsletterForm' => $form,
            'buttonLabel' => "Edit newsletter" 
        ]);
    }

    #[Route('/{id}/delete', name:'delete')]
    public function delete(Newsletter $newsletter, NewsletterRepository $newsletterRepository, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$newsletter->getId(), $request->request->get('_token'))) {
            $newsletterRepository->remove($newsletter, true);
        }
        return $this->redirectToRoute('admin_newsletter_list');
    }

    #[Route('/{id}/send', name:'send')]
    public function send(Newsletter $newsletter, NewsletterSubscriberRepository $newsletterSubscriberRepository, NewsletterRepository $newsletterRepository, MessageBusInterface $bus)
    {
        $newsletterSubscribers = $newsletterSubscriberRepository->findAll();
        foreach($newsletterSubscribers as $newsletterSubscriber){
            $bus->dispatch(new NewsletterEmailMessage(
                $newsletterSubscriber->getEmail(),
                $newsletter->getTitle(),
                $newsletter->getContent()
            ));
        }

        $newsletter->setIsSent(true);
        $newsletterRepository->add($newsletter, true);
        
        return $this->redirectToRoute('admin_newsletter_list');
    }
}