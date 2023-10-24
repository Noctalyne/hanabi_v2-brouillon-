<?php

namespace App\Repository;

use App\Entity\Clients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Clients>
 *
 * @method Clients|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clients|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clients[]    findAll()
 * @method Clients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Clients::class);
    }

    // public function renvoieObjetClient($paramss) {
    //     $client = new Clients();
    //     $client->setUser($paramss);
        // $client->setId($idClient);
        // $client->setUsername($test['username']) ;
        // $client->setEmail($test['email']) ;
        // $client->setPassword($test['password']) ;
        // $client->setNomClient($test['nomClient']);
        // $client->setPrenomClient($test['prenomClient']);
        // $client->setTelephone($test['telephone']); // Accéder à l'attribut ID
    //     return $client;
    // }



    public function findClient($user_id) // retourne un objet client
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT * 
            FROM `clients` as c
            INNER JOIN `user` as u
            ON u.user_id=  c.user_id 
            WHERE u.user_id = :user_id
            ';
        $params = ['user_id' => $user_id]; // recupère la valeur de l'url
        $resultSet = $conn->executeQuery($sql, $params);

        $user = $resultSet->fetchAssociative();// returns un tableau de tableau SANS objet

        // $client= $client->renvoieObjetClient($user);

        $client = new Clients();
        // $client->setUser($user);
        // $client->setUsername($user['username']) ;
        // $client->setEmail($user['email']) ;
        // $client->setPassword($user['password']) ;
        // $client->setNom($user['nom']);
        // $client->setPrenom($user['prenom']);
        // $client->setTelephone($user['telephone']); 
       
        return $client; // retourne un objet d instance clients
    }

    public function findAllWithUser()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = '
            SELECT *
            FROM clients c
            INNER JOIN user u ON u.user_id = c.user_id
            ';

        $resultSet = $conn->executeQuery($sql);

        return $resultSet->fetchAllAssociative();// returns un tableau de tableau SANS objet
    }

    // public function updateClient () {

    //     $conn = $this->getEntityManager()->getConnection();
    //     $sql = '';  
        
    //     // $params = ['user_id'=> $this->getUser()->getId()];
    // }



//    /**
//     * @return Clients[] Returns an array of Clients objects
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

//    public function findOneBySomeField($value): ?Clients
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
