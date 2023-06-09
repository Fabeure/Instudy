<?php

namespace App\Repository;

use App\Entity\Notification;
use App\Entity\User;
use App\Utils\Utils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

/**
 * @extends ServiceEntityRepository<Notification>
 *
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)

    {
        parent::__construct($registry, Notification::class);

    }

    public function save(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function addNotification(string $url,Notification $entity, ?User $sender, ?User $recipient, string $content, $hub){
        $entity->setContent($content." from:  ".$sender->getUsername());
        $entity->setNotifSender($sender);
        $entity->setUpdatedAt(new \DateTimeImmutable());
        $entity->setUrl($url);
        $entity->setNotifRecipient($recipient);
        $entity->setIdentifier(Utils::generateUniqueNumber());

        //create the new update that will be passed to the mercure HUB

        if (!$recipient){
            Utils::Realtime('PUBLIC',
                ['content' => $entity->getContent(),
                'url' => $entity->getUrl(),
                'id' => $entity->getIdentifier()],
                $hub );
        }
        else{

            Utils::Realtime($recipient->getUserIdentifier(),
                ['content' => $entity->getContent(),
                'url' => $entity->getUrl(),
                'id' => $entity->getIdentifier()],
            $hub);
        }
    }

    public function remove(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Notification[] Returns an array of Notification objects
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

//    public function findOneBySomeField($value): ?Notification
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
