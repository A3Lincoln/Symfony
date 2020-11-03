<?php

namespace App\Repository;

use App\Entity\Song;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Song|null find($id, $lockMode = null, $lockVersion = null)
 * @method Song|null findOneBy(array $criteria, array $orderBy = null)
 * @method Song[]    findAll()
 * @method Song[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Song::class);
    }


    /**
    //  * @return Song[] Returns an array of Song objects
    //  */

    public function findById($id)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    /**
    //  * @return Song[] Returns an array of Song objects
    //  */

    public function findByAlbum($album)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.album = :album')
            ->setParameter('album', $album)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
    //  * @return Song[] Returns an array of Song objects
    //  */

    public function findByAlbums($albums)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.album in (:albums)')
            ->setParameter('albums', $albums)
            ->orderBy('s.album')
            ->getQuery()
            ->getResult()
            ;
    }


    // /**
    //  * @return Song[] Returns an array of Song objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Song
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
