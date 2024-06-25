<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
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

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function listBooksOrderByTitle()
    {

        return $this->createQueryBuilder('b')
            ->orderBy('b.title', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function searchBookByTitle($title)
    {


        return $this->createQueryBuilder('a')
            ->where('a.title LIKE :search')
            ->setParameter('search', $title)
            ->getQuery()
            ->getResult();
    }



    public function updateBook()
    {

        return $this->createQueryBuilder('b')
            ->update()->set('b.category', ':newCat')
            ->setParameter('newCat', 'newcattt')
            ->where('b.category LIKE  :cat')
            ->setParameter('cat', 'Test')
            ->getQuery()
            ->getResult();
    }

    public function listBooksByTitleDQL()
    {
        $em = $this->getEntityManager();
        return  $em->createQuery('SELECT b from App\Entity\Book b order by b.title ASC ')->getResult();
    }

    public function showAllBooksByAuthor($title)
    {
        return $this->createQueryBuilder('b')
            ->join('b.author', 'a')
            ->addSelect('a')
            ->where('b.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ->getQuery()
            ->getResult();
    }
}
