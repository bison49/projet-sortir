<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr;
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
            ->getResult();
    }

    /**
     * @param $site
     * @param $rech
     * @param $orga
     * @param $id
     * @param $inscrit
     * @param $pasIns
     *  @param $recherche_date_1
     *  @param $recherche_date_2
     * @return float|int|mixed|string
     */
    public function rechercheFiltrer($site, $rech,$orga,$id,$inscrit,$pasIns,$passee,$recherche_date_1,$recherche_date_2)
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
            $qb->andWhere(':vyl MEMBER OF s.participants')
                ->setParameter('vyl', $id );
        }
        if (!empty($pasIns)) {
            $qb->andWhere(':vyl NOT MEMBER OF s.participants')
                ->setParameter('vyl', $id );
        }
        if (!empty($passee)) {
            $qb->andWhere('s.etat = 5');
        }

        if (!empty($recherche_date_1)) {
            $qb->andWhere('s.dateHeureDebut > :dateDebut')
            ->setParameter('dateDebut',$recherche_date_1);
        }
        if (!empty($recherche_date_2)) {
            $qb->andWhere('s.dateHeureDebut < :dateFin')
            ->setParameter('dateFin',$recherche_date_2);
        }
        return $qb->getQuery()->execute();
    }




}
