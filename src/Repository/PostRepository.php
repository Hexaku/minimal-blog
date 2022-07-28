<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    // Number of posts per page (pagination)
    public const TOTAL_POSTS_PER_PAGE = 6;
    public const ADMIN_TOTAL_POSTS_PER_PAGE = 10;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function add(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getPostQueryBuilder()
    {
        $queryBuilder = $this->createQueryBuilder('p');
        return $queryBuilder;
    }

    public function findLastXPosts(int $limit)
    {
        return $this->getPostQueryBuilder()
            ->orderBy('p.created_at', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getPostsByPage(int $pageNumber, bool $admin = false)
    {
        $totalPostsPerPage = $admin ? self::ADMIN_TOTAL_POSTS_PER_PAGE : self::TOTAL_POSTS_PER_PAGE;
		$firstResult = ($pageNumber - 1) * $totalPostsPerPage;

        $queryBuilder = $this->getPostQueryBuilder()
            ->setFirstResult($firstResult)
            ->setMaxResults($totalPostsPerPage)
            ->orderBy('p.created_at', 'DESC');
        
        $query = $queryBuilder->getQuery();

        $paginator = new Paginator($query, true);
        return $paginator;
    }

    public function findAll()
    {
        return $this->getPostQueryBuilder()
        ->orderBy('p.created_at', 'DESC')
        ->getQuery()
        ->getResult();
    }

//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
