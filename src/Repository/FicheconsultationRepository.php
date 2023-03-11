<?php

namespace App\Repository;

use App\Entity\Ficheconsultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ficheconsultation>
 *
 * @method Ficheconsultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ficheconsultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ficheconsultation[]    findAll()
 * @method Ficheconsultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheconsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ficheconsultation::class);
    }

    public function save(Ficheconsultation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ficheconsultation $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    //public function findByFirstName(string $firstName): array
   // {
       // return $this->createQueryBuilder('dm')
          //  ->andWhere('dm.firstName LIKE :firstName')
           /// ->setParameter('firstName', '%' . $firstName . '%')
           // ->orderBy('dm.id', 'ASC')
          //  ->getQuery()
           // ->getResult();
    //}
    
//    /**
//     * @return Ficheconsultation[] Returns an array of Ficheconsultation objects
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

//    public function findOneBySomeField($value): ?Ficheconsultation
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
