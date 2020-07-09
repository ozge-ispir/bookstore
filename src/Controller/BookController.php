<?php

namespace App\Controller;


use App\Data\SearchData;
use App\Form\SearchForm;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends  AbstractController{


    /**
     * @Route("/", name="book")
     * @param SessionInterface $session
     * @param BookRepository $repository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(SessionInterface $session, BookRepository $bookRepository, Request $request){

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

        $data= new SearchData();
        $form= $this ->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $books= $bookRepository->findSearch($data);
        return $this->render('pages/home.html.twig',[
            'books'=> $books,
            'items' => $panierWithData,
            'total' => $total,
            'form'=> $form->createView()
        ]);
    }
}
