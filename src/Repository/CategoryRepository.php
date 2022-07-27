<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 *
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public const TOTAL_CATEGORIES_PER_PAGE = 6;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function add(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Category $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCategoryQueryBuilder()
    {
        $queryBuilder = $this->createQueryBuilder('c');
        return $queryBuilder;
    }

    public function getCategoriesByPage(int $pageNumber)
    {
        $totalCategoriesPerPage = self::TOTAL_CATEGORIES_PER_PAGE;
		$firstResult = ($pageNumber - 1) * $totalCategoriesPerPage;

        $queryBuilder = $this->getCategoryQueryBuilder()
            ->setFirstResult($firstResult)
            ->setMaxResults($totalCategoriesPerPage);
        
        $query = $queryBuilder->getQuery();

        $paginator = new Paginator($query, true);
        return $paginator;
    }

    public function findAllLatestPosts(int $categoryId)
    {
        return $this->getCategoryQueryBuilder()
            ->select('p.id', 'p.title', 'p.synopsis', 'p.created_at', 'p.slug')
            ->setParameter('categoryId', $categoryId)
            ->innerJoin('c.posts', 'p')
            ->orderBy('p.created_at', 'DESC')
            ->andWhere('c.id = :categoryId')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Category[] Returns an array of Category objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Category
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
