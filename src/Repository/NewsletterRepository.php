<?php

namespace App\Repository;

use App\Entity\Newsletter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Newsletter>
 *
 * @method Newsletter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Newsletter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Newsletter[]    findAll()
 * @method Newsletter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterRepository extends ServiceEntityRepository
{
    public const ADMIN_TOTAL_NEWSLETTERS_PER_PAGE = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Newsletter::class);
    }

    public function add(Newsletter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Newsletter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getNewsletterQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('n');
        return $queryBuilder;
    }

    public function getNewslettersByPage(int $pageNumber): Paginator
    {
        $totalNewslettersPerPage = self::ADMIN_TOTAL_NEWSLETTERS_PER_PAGE;
		$firstResult = ($pageNumber - 1) * $totalNewslettersPerPage;

        $queryBuilder = $this->getNewsletterQueryBuilder()
            ->setFirstResult($firstResult)
            ->setMaxResults($totalNewslettersPerPage);
        
        $query = $queryBuilder->getQuery();

        $paginator = new Paginator($query, true);
        return $paginator;
    }
}
