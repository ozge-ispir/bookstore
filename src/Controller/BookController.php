<?php

namespace App\Controller;


use App\Data\SearchData;
use App\Form\SearchForm;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends  AbstractController{

    /**
     * @Route("/", name="book")
     */
    public function index(BookRepository $repository){
        $data= new SearchData();
        $form= $this ->createForm(SearchForm::class, $data);
        $books= $repository->findSearch();
        return $this->render('pages/home.html.twig',[
            'books'=> $books,
            'form'=> $form->createView()
        ]);
    }
}