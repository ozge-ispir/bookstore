<?php
namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param BookRepository $bookRepository
     * @return Response
     */
    public function index(SessionInterface $session, BookRepository $bookRepository):Response
    {

        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'book' => $bookRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;

        foreach ($panierWithData as $item) {
            $totalItem = $item['book']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }

        $books = $bookRepository->findPerso();
        return $this->render('pages/home.html.twig', [
            'books' => $books,
            'items' => $panierWithData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/{id}", name="book_show")
     * @param Book $book
     * @return Response
     */
    public function show(Book $book):Response
    {
        return $this->redirectToRoute('book_show', [
            'id' => $book->getId()
        ], 301);

    }
}
