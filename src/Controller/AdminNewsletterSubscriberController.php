<?php

namespace App\Controller;

use App\Repository\NewsletterSubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/newsletter/subscribers', name:'admin_newsletter_subscriber_')]
class AdminNewsletterSubscriberController extends AbstractController
{
    #[Route('/page/{pageNumber}', name:'list', requirements: ['pageNumber' => '\d+'])]
    public function list(NewsletterSubscriberRepository $newsletterSubscriberRepository, int $pageNumber = 1)
    {
        // Get subscribers by page admin and total subscribers count
        $newsletterSubscribers = $newsletterSubscriberRepository->getNewsletterSubscribersByPage($pageNumber, true);
        $totalSubscribers = count($newsletterSubscribers);

        // Calculate total pages count
        $totalSubscribersPerPage = $newsletterSubscriberRepository::ADMIN_TOTAL_SUBSCRIBERS_PER_PAGE;
        $totalPages = ceil($totalSubscribers / $totalSubscribersPerPage);

        // Get first result page number and last result page number
        $firstResult = 1 + ($totalSubscribersPerPage * ($pageNumber - 1));
        $lastResult = $totalSubscribersPerPage * $pageNumber;

        // Can't exceed total subscribers count
        if($lastResult > $totalSubscribers){
            $lastResult = $totalSubscribers;
        } 
        if($firstResult > $totalSubscribers){
            $firstResult = $totalSubscribers;
        }

        return $this->render('admin/newsletter_subscriber_list.html.twig', [
            'newsletterSubscribers' => $newsletterSubscribers,
            'totalElements' => $totalSubscribers,
            'totalElementsPerPage' => $totalSubscribersPerPage,
            'totalPages' => $totalPages,
            'lastResult' => $lastResult,
            'firstResult' => $firstResult,
            'currentPageNumber' => $pageNumber,
            'path' => 'admin_newsletter_subscriber_list',
            'name' => 'newsletter subscribers'
        ]);
    }
}