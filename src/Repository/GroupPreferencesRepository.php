<?php

namespace Pixiekat\AlicantoConsult\Repository;

use Pixiekat\AlicantoConsult\Entity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupPreferences>
 *
 * @method GroupPreferences|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupPreferences|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupPreferences[]    findAll()
 * @method GroupPreferences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupPreferencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entity\GroupPreferences::class);
    }

//    /**
//     * @return GroupPreferences[] Returns an array of GroupPreferences objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GroupPreferences
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
