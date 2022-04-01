<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Sortie $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Sortie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
      * @return Sortie[] Returns an array of Sortie objects
      */

    public function findByPublish($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.etat != :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * Recupere les sortir en lien avec une recherche
     * @return Sortie[]
     */

    public function findSearch(SearchData $search): array{


        $query=$this
        ->$this->createQueryBuilder('p')
            ->select('c','p')
            -join('p.sorties','c');


        return $this->$query->getQuery()->getResult();
    }

    /**
     * Récupère les sorties en lien avec une recherche
     * @return \Doctrine\ORM\Query
     */

    public function findSearch2(SearchData $search): \Doctrine\ORM\Query
    {


        return    $query = $this->getSearchQuery($search)->getQuery();
        ;


    }

    private function getSearchQuery(SearchData $search ): QueryBuilder
    {
        $query = $this
        ->createQueryBuilder('p')
        ->select('c', 'p')
        ->join('p.categories', 'c');

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('p.name LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if (!empty($search->sortie1)) {
            $query = $query
                ->andWhere('p.sortie1 = 1');
        }
        if (!empty($search->sortie2)) {
            $query = $query
                ->andWhere('p.sortie2 = 2');
        }
        if (!empty($search->sortie3)) {
            $query = $query
                ->andWhere('p.sortie3 = 3');
        }

        if (!empty($search->sortie4)) {
            $query = $query
                ->andWhere('p.sortie4 = 4');
        }
        return
            $query
            ;
    }
    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
