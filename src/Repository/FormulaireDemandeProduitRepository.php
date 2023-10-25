<?php

namespace App\Repository;

use App\Entity\FormulaireDemandeProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormulaireDemandeProduit>
 *
 * @method FormulaireDemandeProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormulaireDemandeProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormulaireDemandeProduit[]    findAll()
 * @method FormulaireDemandeProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormulaireDemandeProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormulaireDemandeProduit::class);
    }

//    /**
//     * @return FormulaireDemandeProduit[] Returns an array of FormulaireDemandeProduit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FormulaireDemandeProduit
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
