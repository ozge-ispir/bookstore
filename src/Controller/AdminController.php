<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\BookType;
use App\Entity\Book;
use App\Form\AuthorType;
use App\Entity\Author;
use App\Entity\Invoice;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin", name="admin")
 */
class AdminController extends AbstractController
{

    
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Book::class);
        $books = $repo->findAll();
        return $this->render('admin/index.html.twig', [
            'books' => $books
        ]);
    }

    /**
     * @Route("/editbook/{id}", name="editbook")
     */
    public function editBook($id, Request $request,  EntityManagerInterface $manager)
    {
        $book = new Book();

        $repo = $this->getDoctrine()->getRepository(Book::class);
        $book = $repo->find($id);

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($book);
            $manager->flush();

            return $this->redirectToRoute('adminindex');
        }

        return $this->render('admin/editbook.html.twig', [
            'bookform' => $form->createView()
        ]);
        
    }


    /**
     * @Route("/addbook", name="addbook")
     */
    public function addBook(Request $request,  EntityManagerInterface $manager)
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($book);
            $manager->flush();

            return $this->redirectToRoute('adminindex');
        }

        return $this->render('admin/bookform.html.twig', [
            'bookform' => $form->createView()
        ]);
    }

    /**
     * @Route("/removebook/{id}", name="removebook")
     */
    public function removeBook($id, Request $request,  EntityManagerInterface $manager)
    {
        $book = new Book();

        $repo = $this->getDoctrine()->getRepository(Book::class);
        $book = $repo->find($id);
        $manager->remove($book);
        $manager->flush();

        return $this->redirectToRoute('adminindex');
    }


    /**
     * @Route("/addauthor", name="addauthor")
     */
    public function addAuthor(Request $request, EntityManagerInterface $manager)
    {
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($author);
            $manager->flush();

            return $this->redirectToRoute('adminindex');
        }

        return $this->render('admin/authorform.html.twig', [
            'authorform' => $form->createView()
        ]);
    }

    /**
     * @Route("/editauthor/{id}", name="editauthor")
     */
    public function editAuthor($id, Request $request,  EntityManagerInterface $manager)
    {
        $author = new Author();

        $repo = $this->getDoctrine()->getRepository(Author::class);
        $author = $repo->find($id);

        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($author);
            $manager->flush();

            return $this->redirectToRoute('adminindex');
        }

        return $this->render('admin/editauthor.html.twig', [
            'authorform' => $form->createView()
        ]);
        
    }

    // /**
    //  * @Route("/removeauthor/{id}", name="removeauthor")
    //  */
    // public function removeAuthor($id, Request $request,  EntityManagerInterface $manager)
    // {
    //     $author = new Author();

    //     $repo = $this->getDoctrine()->getRepository(Author::class);
    //     $author = $repo->find($id);
    //     $manager->remove($author);
    //     $manager->flush();

    //     return $this->redirectToRoute('adminindex');
    // }

    /**
     * @Route("/invoices", name="invoices")
     */
    public function showInvoices(Request $request,  EntityManagerInterface $manager){
        $repo = $this->getDoctrine()->getRepository(Invoice::class);
        $invoices = $repo->findAll();


        return $this->render('admin/invoices.html.twig', [
            'invoices' => $invoices
        ]);
    }

    /**
     * @Route("/invoice/{id}", name="invoiceinfo")
     */
    public function showOneInvoice($id, Request $request, EntityManagerInterface $manager){

        $invoice = new Invoice();
        $repo = $this->getDoctrine()->getRepository(Invoice::class);
        $invoice = $repo->find($id);
        dump($invoice);

        $user = $invoice->getUser();

        $books = $invoice->getBooks();

        return $this->render('admin/showinvoice.html.twig', [
            'invoice' => $invoice,
            'user' => $user,
            'books' => $books
        ]);
    }



}