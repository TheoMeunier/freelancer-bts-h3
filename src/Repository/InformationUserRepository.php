<?php

namespace App\Repository;

use App\Entity\InformationUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InformationUser>
 *
 * @method InformationUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method InformationUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method InformationUser[]    findAll()
 * @method InformationUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InformationUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InformationUser::class);
    }

    public function save(InformationUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InformationUser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return InformationUser[] Returns an array of InformationUser objects
//     */
//    public function findUserId(int $userId): array
//    {
//        return $this->createQueryBuilder('i')
//            ->select('i', 'user')
//            ->leftJoin('i.user', 'user')
//            ->where('user.id = :userId')
//            ->setParameter('userId', $userId)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InformationUser
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
