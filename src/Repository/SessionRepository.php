<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function add(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Session $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    // afficher les stagiaires non inscrit
    public function findNonInscrits($session_id)
    {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();
        
        $qb = $sub;
        $qb->select('s')
            ->from('App\Entity\Stagiaire','s')
            ->leftJoin('s.Session', 'se')
            ->where('se.id = :id');

        $sub = $em->createQueryBuilder();
        $sub->select('st')
            ->from('App\Entity\Stagiaire','st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            ->setParameter('id', $session_id)
            ->orderBy('st.nom');

        $query = $sub->getQuery();
        return $query->getResult();
    }
        // afficher les modules non associés
        public function findNonAssociés($session_id)
        {
            $em = $this->getEntityManager();
            $sub = $em->createQueryBuilder();
            
            $qb = $sub;
            $qb->select('pr')
                ->from('App\Entity\Programme','pr')
                ->leftJoin('pr.Session', 'se')
                ->where('se.id = :id');
    
            $sub = $em->createQueryBuilder();
            $sub->select('pr')
                ->from('App\Entity\Programme','pr')
                ->leftJoin('pr.Module','mo')
                ->where($sub->expr()->notIn('mo.id', $qb->getDQL()))
                ->setParameter('id', $session_id);
    
            $query = $sub->getQuery();
            return $query->getResult();
        }
//    /**
//     * @return Session[] Returns an array of Session objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Session
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
