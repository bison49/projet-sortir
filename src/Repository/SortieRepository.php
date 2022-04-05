<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;


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
            ->getResult();
    }

    /**
     * @param $site
     * @param $rech
     * @param $orga
     * @param $id
     * @param $inscrit
     * @param $pasIns
     * @return float|int|mixed|string
     */
    public function rechercheFiltrer($site, $rech,$orga,$id,$inscrit,$pasIns,$passee)
    {

        $qb = $this->createQueryBuilder('s');



        if (!empty($site)) {
            $qb->Where('s.siteOrganisateur = :val' )
                ->setParameter('val', $site);
        }
        if (!empty($rech)) {
             $qb->andWhere('s.nom LIKE :vol')
                ->setParameter('vol', '%' . $rech . '%');
        }
        if (!empty($orga)) {
            $qb->andWhere('s.organisateur = :vil')
                ->setParameter('vil',$id );
        }
        if (!empty($inscrit)) {
            $qb->join('s.participants','p')
                ->andWhere('p.id = :vyl')
                ->setParameter('vyl', $id );
        }
        if (!empty($pasIns)) {
            $qb
            ->from($this->_entityName,'u')
            ->join('s.participants','p')
            ->where('p.id = :vyl')/*leftJoin('s.participants','p')
                ->andWhere("id IS NULL");*/
                ->setParameter('vyl', $id );
        }
        if (!empty($passee)) {
            $qb->andWhere('s.etat = 5');
        }
        return $qb->getQuery()->execute();
    }

    /**
     * Recupere les sortir en lien avec une recherche
     * @return Sortie[]
     */

    /*public function findSearch(SearchData $search): array{


        $query=$this
        ->$this->createQueryBuilder('p')
            ->select('c','p')
            -join('p.sorties','c');


        return $this->$query->getQuery()->getResult();
    }*/

    /**
     * Récupère les sorties en lien avec une recherche
     * @return \Doctrine\ORM\Query
     */

    /* public function findSearch2(SearchData $search): \Doctrine\ORM\Query
     {


         return    $query = $this->getSearchQuery($search)->getQuery();
         ;


     }*/

    /*private function getSearchQuery(SearchData $search ): QueryBuilder
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
    }*/
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
