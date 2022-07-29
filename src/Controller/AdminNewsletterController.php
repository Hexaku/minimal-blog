<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Message\NewsletterEmailMessage;
use App\Repository\NewsletterRepository;
use App\Repository\NewsletterSubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/newsletters', name:'admin_newsletter_')]
class AdminNewsletterController extends AbstractController
{
    #[Route('/page/{pageNumber}', name:'list', requirements: ['pageNumber' => '\d+'])]
    public function list(NewsletterRepository $newsletterRepository, int $pageNumber = 1)
    {
        // Get newsletters by page admin and total newsletters count
        $newsletters = $newsletterRepository->getNewslettersByPage($pageNumber);
        $totalNewsletters = count($newsletters);

        // Calculate total pages count
        $totalNewslettersPerPage = $newsletterRepository::ADMIN_TOTAL_NEWSLETTERS_PER_PAGE;
        $totalPages = ceil($totalNewsletters / $totalNewslettersPerPage);

        // Get first result page number and last result page number
        $firstResult = 1 + ($totalNewslettersPerPage * ($pageNumber - 1));
        $lastResult = $totalNewslettersPerPage * $pageNumber;

        // Can't exceed total newsletters count
        if($lastResult > $totalNewsletters){
            $lastResult = $totalNewsletters;
        } 
        if($firstResult > $totalNewsletters){
            $firstResult = $totalNewsletters;
        }

        return $this->render('admin/newsletter_list.html.twig', [
            'newsletters' => $newsletters,
            'totalElements' => $totalNewsletters,
            'totalElementsPerPage' => $totalNewslettersPerPage,
            'totalPages' => $totalPages,
            'lastResult' => $lastResult,
            'firstResult' => $firstResult,
            'currentPageNumber' => $pageNumber,
            'path' => 'admin_newsletter_list',
            'name' => 'newsletters'
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
            $this->addFlash('success', 'Newsletter successfully created !');

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
            $this->addFlash('success', 'Newsletter successfully updated !');

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
            $this->addFlash('success', 'Newsletter successfully deleted !');
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
        $this->addFlash('info', 'All newsletters emails are successfully sent to RabbitMQ ! Don\'t forget to consume !');
        
        return $this->redirectToRoute('admin_newsletter_list');
    }
}