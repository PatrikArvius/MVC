<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Method that returns a shuffled sample of Books from the database
     * It will pick out and shuffle three books with the lowest id's
     * if there are books in the database
     *
     * @return ?array<Book>
     * */
    public function sampleDisplay(): ?array
    {
        $res = $this->createQueryBuilder('b')
                ->orderBy('b.id', 'ASC')
                ->setMaxResults(3)
                ->getQuery()
                ->getResult()
        ;
        if (gettype($res) != "array") {
            return null;
        }
        shuffle($res);
        return $res;
    }

    /**
     * Method that deletes all books from the book database
     */
    public function deleteBooks(): void
    {
        $ids = $this->createQueryBuilder('b')
                ->select('b.id')
                ->getQuery()
                ->getResult();

        $this->createQueryBuilder('b')
            ->where('b.id in (:ids)')
            ->setParameter('ids', $ids)
            ->delete()
            ->getQuery()
            ->execute();
    }

    /**
     * Method that adds pre-set books to the book database
     */
    public function restockBooks(ManagerRegistry $doctrine): void
    {
        $entityManager = $doctrine->getManager();

        $titles = ["Dune", "Silmarillon", "The Hitchhiker's Guide To The Galaxy", "Moby Dick", "The Consolations of Philosophy"];
        $isbns = [9781473233959, 9789113084930, 9781399617246, 9780062085641, 9780679779179];
        $authors = ["Frank Herbert", "J R R Tolkien", "Douglas Adams", "Herman Melville", "Alain De Botton"];
        $images = [
            "https://bilder.akademibokhandeln.se/images_akb/9781473233959_383/dune",
            "https://bilder.akademibokhandeln.se/images_akb/9789113084930_383/silmarillion",
            "https://bilder.akademibokhandeln.se/images_akb/9781399617246_383/the-hitchhikers-guide-to-the-galaxy",
            "https://bilder.akademibokhandeln.se/images_akb/9780062085641_383/moby-dick",
            "https://bilder.akademibokhandeln.se/images_akb/9780679779179_383/the-consolations-of-philosophy"
                ];
        $num = count($titles);

        for ($i = 0; $i < $num; $i++) {
            $book = new Book();
            $book->setTitle($titles[$i]);
            $book->setIsbn(intval($isbns[$i]));
            $book->setAuthor($authors[$i]);
            $book->setImage($images[$i]);

            $entityManager->persist($book);
            $entityManager->flush();
        }
    }

    /**
     * Method that adds a book to the book database
     * @param array<string, string|int> $data
     */
    public function addBook(ManagerRegistry $doctrine, array $data): void
    {
        $data = $data;
        $title = $data['title'];
        $isbn = $data['isbn'];
        $author = $data['author'];
        $image = $data['image'];
        $book = new Book();
        $book->setTitle(strval($title));
        $book->setIsbn(intval($isbn));
        $book->setAuthor(strval($author));
        $book->setImage(strval($image));

        $entityManager = $doctrine->getManager();
        $entityManager->persist($book);
        $entityManager->flush();
    }

    /**
     * Method that updates a book from the book database
     * @param array<string, string|int> $data
     */
    public function updateBook(ManagerRegistry $doctrine, array $data): void
    {
        $data = $data;
        $id = intval($data['id']);
        $title = strval($data['title']);
        $isbn = intval($data['isbn']);
        $author = strval($data['author']);
        $image = strval($data['image']);

        $book = $this->findOneBy(['id' => $id]);

        if ($book) {
            $book->setTitle(strval($title));
            $book->setIsbn(intval($isbn));
            $book->setAuthor(strval($author));
            $book->setImage(strval($image));

            $entityManager = $doctrine->getManager();
            $entityManager->flush();
        }
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
}
