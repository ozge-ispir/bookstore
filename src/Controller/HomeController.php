<?php
namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function index(BookRepository $bookRepository):Response
    {

        $books = $bookRepository->findFive();
        return $this->render('pages/home.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * @Route("/{id}", name="book.show")
     * @param Book $book
     * @return Response
     */
    public function show(Book $book):Response
    {
        return $this->redirectToRoute('book.show', [
            'id' => $book->getId()
        ], 301);

    }
}
