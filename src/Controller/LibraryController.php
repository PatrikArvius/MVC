<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(
        BookRepository $bookRepository
    ): Response
    {
        $books = $bookRepository->sampleDisplay();

        $data = [
            'books' => $books
        ];
        return $this->render('library/index.html.twig', $data);
    }

    #[Route('/library/add', name: 'app_library_add')]
    public function addBook(
    ): Response
    {
        return $this->render('library/add.html.twig');
    }

    #[Route('/library/add/post', name: 'app_library_add_post', methods:['GET','POST'])]
    public function addBookPost(
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $entityManager = $doctrine->getManager();
        $title = $request->request->get('title');
        $isbn = $request->request->get('isbn');
        $author = $request->request->get('author');
        $image = $request->request->get('image');
        $book = new Book();
        $book->setTitle($title);
        $book->setIsbn($isbn);
        $book->setAuthor($author);
        $book->setImage($image);

        // tell Doctrine you want to (eventually) save the Product
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->redirectToRoute('app_library');
    }
}
