<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }



    #[Route('/addBook', name: "addbook")]
    public function addBook(ManagerRegistry $manager, Request $req)
    {
        $em = $manager->getManager();
        $book = new Book();




        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $book->setPublished(true);
            //Incrémentaion nombre des livres pour chaque auteur
            $book->getAuthor()->setNb_Books($book->getAuthor()->getNb_Books() + 1);
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("listbooks");
        }
        return $this->renderForm('book/addBook.html.twig', ['fo' => $form]);
    }






    #[Route('/listBooks', name: "listbooks")]
    public function listBooks(BookRepository $repo)
    {

        $books = $repo->findAll();


        return $this->render('book/listBooks.html.twig', ['listBooks' => $books]);
    }
}
