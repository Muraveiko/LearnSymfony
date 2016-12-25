<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as REST;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Book;
use AppBundle\Form\BookType;


/**
 * @REST\Route("/api/v1")
 */
class ApiVersionOneController extends FOSRestController
{
    /**
     * @REST\Get("/books")
     *  пришлось описывать так, в задании нет слеша в конце
     *  в .htaccess нужно добавить
     *  DirectorySlash off
     *
     * @REST\QueryParam(name="apikey", nullable=false)
     *
     * @REST\View(serializerGroups={"book_list"})
     */
    public function listAction()
    {
        $books = $this->getDoctrine()->getRepository('AppBundle:Book')->getList();

        return $books;
    }

    /**
     * @REST\Get("/books/")
     */
    public function wrongHtaccessAction()
    {
        @trigger_error('add DirectorySlash off in .htaccess', E_USER_WARNING);

        throw new NotFoundHttpException();
    }


    /**
     * @REST\Get("/books/{id}", requirements={"id": "\d+"})
     *
     * @REST\View(serializerGroups={"book_details"})
     */
    public function detailsAction(Book $entity)
    {
        return $entity;
    }


    /**
     * @REST\Post("/books/add")
     */
    public function addAction(Request $request)
    {
        $book = new Book();
        $book->setContainer($this->container);

        $form = $this->createForm(BookType::class, $book, ['csrf_protection' => false,]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            return $this->view("Created Successfully", Codes::HTTP_CREATED);
        }

        return $this->view($form, Codes::HTTP_BAD_REQUEST);
    }

    /**
     * @REST\Put("/books/{id}", requirements={"id": "\d+"})
     * @REST\Post("/books/{id}/edit", requirements={"id": "\d+"})
     */
    public function editAction(Book $book, Request $request)
    {
        $editForm = $this->createForm(BookType::class, $book, ['csrf_protection' => false,]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->view("Updated Successfully", Codes::HTTP_NO_CONTENT);
        }

        return $this->view($editForm, Codes::HTTP_BAD_REQUEST);
    }

}
