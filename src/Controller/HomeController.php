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
     * @param SessionInterface $session
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
     * @Route("book/{id}", name="book_show")
     * @param $id
     * @return Response
     */
    public function show($id):Response
    {
        $bookInfo = new Book();
        $repo = $this->getDoctrine()->getRepository(Book::class);
        $bookInfo = $repo->find($id);

        $author = $bookInfo->getAuthor();

        $category = $bookInfo->getCategory();

        return $this->render('pages/book_show.html.twig', [
            'book' => $bookInfo,
            'author' => $author,
            'category' => $category
        ]);
    }
}
