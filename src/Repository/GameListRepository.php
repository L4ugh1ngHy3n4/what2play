<?php

namespace App\Repository;

use App\Entity\GameList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameList>
 *
 * @method GameList|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameList|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameList[]    findAll()
 * @method GameList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameList::class);
    }

//    /**
//     * @return GameList[] Returns an array of GameList objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GameList
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
