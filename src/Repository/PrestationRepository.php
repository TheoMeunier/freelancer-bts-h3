<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Prestation;
use App\Entity\PrestationSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @extends ServiceEntityRepository<Prestation>
 *
 * @method Prestation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Prestation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Prestation[]    findAll()
 * @method Prestation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrestationRepository extends ServiceEntityRepository
{
    public function __construct(
       ManagerRegistry $registry,
        private readonly PaginatorInterface $paginationInterface
    )
    {
        parent::__construct($registry, Prestation::class);
    }

    public function findBySearch(PrestationSearch $search): PaginationInterface
    {
        $queryBuilder = $this->createQueryBuilder('p');

        if (!empty($search->getName())) {
            $queryBuilder = $queryBuilder
                ->andWhere('p.title LIKE :title')
                ->setParameter('title', "%{$search->getName()}%");
        }

        $query = $queryBuilder->getQuery();

        $pagination = $this->paginationInterface->paginate($query, $search->getPage(), 12);

        return $pagination;
    }

    public function save(Prestation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Prestation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Prestation[] Returns an array of Prestation objects
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

//    public function findOneBySomeField($value): ?Prestation
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
