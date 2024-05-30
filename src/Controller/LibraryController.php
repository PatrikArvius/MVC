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

    #[Route('/library/reset', name: 'app_library_reset')]
    public function resetBook(
    ): Response
    {
        return $this->render('library/reset.html.twig');
    }

    #[Route('/library/reset/post', name: 'app_library_reset_post', methods:['POST'])]
    public function resetBookPost(
        BookRepository $bookRepository,
        ManagerRegistry $doctrine
    ): Response
    {
        $entityManager = $doctrine->getManager();
        $bookRepository->deleteBooks();

        $titles = ["Dune", "Silmarillon", "The Hitchhiker's Guide To The Galaxy"];
        $isbns = [9781473233959, 9789113084930, 9781399617246];
        $authors = ["Frank Herbert", "J R R Tolkien", "Douglas Adams"];
        $images = [
            "https://bilder.akademibokhandeln.se/images_akb/9781473233959_383/dune", 
            "https://bilder.akademibokhandeln.se/images_akb/9789113084930_383/silmarillion", 
            "https://bilder.akademibokhandeln.se/images_akb/9781399617246_383/the-hitchhikers-guide-to-the-galaxy"
                ];

        for ($i = 0; $i < 3; $i++) {
            $book = new Book();
            $book->setTitle($titles[$i]);
            $book->setIsbn($isbns[$i]);
            $book->setAuthor($authors[$i]);
            $book->setImage($images[$i]);

            $entityManager->persist($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_library');
    }
}
