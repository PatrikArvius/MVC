<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;
use App\Entity\Book;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->sampleDisplay();

        $data = [
            'books' => $books
        ];
        return $this->render('library/index.html.twig', $data);
    }

    #[Route('/library/add', name: 'app_library_add')]
    public function addBook(
    ): Response {
        return $this->render('library/add.html.twig');
    }

    #[Route('/library/add/post', name: 'app_library_add_post', methods:['POST'])]
    public function addBookPost(
        ManagerRegistry $doctrine,
        Request $request,
        BookRepository $bookRepository
    ): Response {
        $data = $request->request->all();
        $bookRepository->addBook($doctrine, $data);

        return $this->redirectToRoute('app_library_show_all');
    }

    #[Route('/library/show', name: 'app_library_show_all')]
    public function showAllBooks(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();

        $data = [
            'books' => $books
        ];
        return $this->render('library/show.html.twig', $data);
    }

    #[Route('/library/show/{title}', name: 'app_library_show_one')]
    public function showOneBook(
        BookRepository $bookRepository,
        string $title
    ): Response {
        $book = $bookRepository
            ->findOneBy(['title' => $title]);
        $data = [
            'book' => $book
        ];
        return $this->render('library/show_one.html.twig', $data);
    }

    #[Route('/library/reset', name: 'app_library_reset')]
    public function resetBook(
    ): Response {
        return $this->render('library/reset.html.twig');
    }

    #[Route('/library/reset/post', name: 'app_library_reset_post', methods:['POST'])]
    public function resetBookPost(
        BookRepository $bookRepository,
        ManagerRegistry $doctrine
    ): Response {
        $bookRepository->deleteBooks();
        $bookRepository->restockBooks($doctrine);

        return $this->redirectToRoute('app_library');
    }

    #[Route('/library/update', name: 'app_library_update')]
    public function updateBook(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();

        $data = [
            'books' => $books
        ];
        return $this->render('library/update.html.twig', $data);
    }

    #[Route('/library/update/{title}', name: 'app_library_update_one')]
    public function updateOneBook(
        string $title,
        BookRepository $bookRepository
    ): Response {
        $book = $bookRepository
            ->findOneBy(['title' => $title]);
        $data = [
            'book' => $book
        ];

        return $this->render('library/update_one.html.twig', $data);
    }

    #[Route('/library/update/post/{title}', name: 'app_library_update_post', methods: ['POST'])]
    public function updateBookPost(
        string $title,
        ManagerRegistry $doctrine,
        Request $request,
        BookRepository $bookRepository
    ): Response {
        $book = $bookRepository
            ->findOneBy(['title' => $title]);
        $data = $request->request->all();
        $bookRepository->updateBook($doctrine, $data);

        if (!$book) {
            return $this->redirectToRoute('app_library_show_all');
        }

        return $this->redirectToRoute('app_library_show_all');
    }

    #[Route('/library/delete', name: 'app_library_delete')]
    public function deleteBook(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();

        $data = [
            'books' => $books
        ];
        return $this->render('library/delete.html.twig', $data);
    }

    #[Route('/library/delete/{isbn}', name: 'app_library_delete_one')]
    public function deleteOneBook(
        int|float $isbn,
        BookRepository $bookRepository,
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();
        $isbn = intval($isbn);
        $book = $bookRepository
            ->findOneBy(['isbn' => $isbn]);
        if ($book) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_library_delete');
    }
}
