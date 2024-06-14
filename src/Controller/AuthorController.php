<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }


    #[Route('/list', name: "listAuthors")]
    public function listAuhtors(AuthorRepository $repo)
    {
        return $this->render('author/listAuthors.html.twig', ['list' =>  $repo->findAll()]);
    }


    #[Route('/addStatic', name: "addAuthor")]
    public function addAuthor(ManagerRegistry $manager)
    {
        $em = $manager->getManager();
        $author = new Author();

        $author->setUsername("Samah");
        $author->setEmail("samah@email.com");

        $em->persist($author);
        $em->flush();

        return  $this->redirectToRoute("listAuthors");
    }




    #[Route('/add', name: "add")]
    public function add(ManagerRegistry $manager, Request $request)
    {
        $em = $manager->getManager();
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute("listAuthors");
        }
        return $this->renderForm('author/add.html.twig', ['f' => $form]);
    }



    #[Route('/delete/{id}', name: "delete")]
    public function delete($id, ManagerRegistry $manager, AuthorRepository $repo)
    {

        $em = $manager->getManager();

        $author = $repo->find($id);

        $em->remove($author);

        $em->flush();

        return $this->redirectToRoute("listAuthors");
    }
}
