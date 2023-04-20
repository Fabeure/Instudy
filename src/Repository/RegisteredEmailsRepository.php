<?php

namespace App\Repository;

use App\Entity\RegisteredEmails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RegisteredEmails>
 *
 * @method RegisteredEmails|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegisteredEmails|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegisteredEmails[]    findAll()
 * @method RegisteredEmails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegisteredEmailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegisteredEmails::class);
    }

    public function save(RegisteredEmails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(RegisteredEmails $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return RegisteredEmails[] Returns an array of RegisteredEmails objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RegisteredEmails
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
