<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookFormType;
use App\Form\SearchType;
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

            $nb = $book->getAuthor()->getNb_Books() + 1;
            $book->getAuthor()->setNb_Books($nb);
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("listbooks");
        }
        return $this->renderForm('book/addBook.html.twig', ['fo' => $form]);
    }






    #[Route('/listBooks', name: "listbooks")]
    public function listBooks(BookRepository $repo, Request $req)
    {



        $books = $repo->findAll();


        return $this->renderForm('book/listBooks.html.twig', ['listBooks' => $books]);
    }





    #[Route('/updateCat', name: "updateCat")]
    public  function updateCat(BookRepository $repo)
    {

        $repo->updateBook();

        return $this->redirectToRoute('listbooks');
    }

    #[Route('/editBook/{idBook}', name: "editBook")]
    public function editBook($idBook, ManagerRegistry $manager, Request $req, BookRepository $repo)
    {
        $em = $manager->getManager();
        $book = $repo->find($idBook);
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("listbooks");
        }
        return $this->renderForm('book/editBook.html.twig', ['fo' => $form]);
    }



    #[Route('/deleteBook/{id}', name: 'deleteBook')]
    public function deleteBook($id, ManagerRegistry $manager, BookRepository $repo)
    {
        $book = $repo->find($id);

        $em = $manager->getManager();


        $em->remove($book);

        $em->flush();

        return $this->redirectToRoute("listbooks");
    }




    #[Route('/detailsBook/{id}', name: "detailsbook")]
    public function detailsBook($id, BookRepository $repo)
    {


        $book = $repo->find($id);

        return $this->render('book/details.html.twig', ['book' => $book]);
    }
}
