<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\JsonResponse;

class LibraryJsonController extends AbstractController
{
    #[Route('/api/library/books', name: 'app_library_all_books_json')]
    public function allBooksJson(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();
        $jsonBooks = [];

        if (!empty($books)) {
            foreach ($books as $book) {
                $title = $book->getTitle();
                $author = $book->getAuthor();
                $isbn = $book->getIsbn();
                $image = $book->getImage();
                $jsonBooks[] = ['title' => $title,
                            'author' => $author,
                            'isbn' => $isbn,
                            'image-url' => $image
                        ];
            }
            $data = [
                'books' => $jsonBooks
            ];
            return new JsonResponse($data);
        }
        // If no books are found redirect to route for resetting the library
        return $this->redirectToRoute('app_library_reset');
    }

    #[Route('/api/library/book/{isbn}', name: 'app_library_one_book_json')]
    public function oneBookJson(
        int|float $isbn,
        BookRepository $bookRepository
    ): Response {
        $isbn = intval($isbn);
        $book = $bookRepository
            ->findOneBy(['isbn' => $isbn]);

        if ($book) {
            $title = $book->getTitle();
            $author = $book->getAuthor();
            $bookIsbn = $book->getIsbn();
            $image = $book->getImage();

            $data = [
                'title' => $title,
                'author' => $author,
                'isbn' => $bookIsbn,
                'image-url' => $image
            ];

            return new JsonResponse($data);
        }
        //If no book is found, redirect to route for resetting library
        return $this->redirectToRoute('app_library_reset');
    }
}
