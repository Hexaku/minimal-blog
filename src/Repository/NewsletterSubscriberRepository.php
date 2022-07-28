<?php

namespace App\Repository;

use App\Entity\NewsletterSubscriber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NewsletterSubscriber>
 *
 * @method NewsletterSubscriber|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterSubscriber|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterSubscriber[]    findAll()
 * @method NewsletterSubscriber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterSubscriberRepository extends ServiceEntityRepository
{
    public const ADMIN_TOTAL_SUBSCRIBERS_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterSubscriber::class);
    }

    public function add(NewsletterSubscriber $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(NewsletterSubscriber $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNewsletterSubscriberQueryBuilder()
    {
        $queryBuilder = $this->createQueryBuilder('ns');
        return $queryBuilder;
    }

    public function getNewsletterSubscribersByPage(int $pageNumber)
    {
        $totalSubscribersPerPage = self::ADMIN_TOTAL_SUBSCRIBERS_PER_PAGE;
		$firstResult = ($pageNumber - 1) * $totalSubscribersPerPage;

        $queryBuilder = $this->getNewsletterSubscriberQueryBuilder()
            ->setFirstResult($firstResult)
            ->setMaxResults($totalSubscribersPerPage);
        
        $query = $queryBuilder->getQuery();

        $paginator = new Paginator($query, true);
        return $paginator;
    }
//    /**
//     * @return NewsletterSubscriber[] Returns an array of NewsletterSubscriber objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NewsletterSubscriber
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
