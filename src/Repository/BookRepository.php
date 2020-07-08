<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Book;
use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Book[]
     */
    public function findLatest(): array
    {
        return $this->createQueryBuilder('b')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findFive(){
        $query = "
            SELECT b.*, a.* 
            FROM book b
            JOIN book_author ab
            ON b.id = ab.book_id
        JOIN author a
            ON a.id = ab.author_id;
        ";



        $stmt = $this->getEntityManager()->getConnection()->prepare($query);
        $stmt->execute();



        return $stmt->fetchAll();



    }

    /**
     * Recuperer les livres avec une recherche
     * @return Book[]
     */
    public function findSearch(SearchData $search):array
    {
        $query =$this
            ->createQueryBuilder('p')
            ->select('c','a','p')
            ->join('p.category', 'c')
            ->join('p.author', 'a')

        ;

        if(!empty($search->q)){
            $query =$query
                ->andWhere ('p.title LIKE :q')
                ->orWhere('p.isbn LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        if(!empty($search->categories)){
            $query =$query
                ->andWhere ('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        if(!empty($search->authors)){
            $query =$query
                ->andWhere ('a.id IN (:authors)')
                ->setParameter('authors', $search->authors);
        }
        return $query->getQuery()->getResult();

    }

    // /**
    //  * @return Book[] Returns an array of Book objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Book
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
