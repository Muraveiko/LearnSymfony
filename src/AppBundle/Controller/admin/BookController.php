<?php

/*
 * Пример простой админки для управления сущностью КНИГА
 */

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Book;
use AppBundle\Form\BookType;


/**
 * @Route("/book")
 * @Security("has_role('ROLE_ADMIN')")
 */
class BookController extends Controller
{
    /**
     * Add book
     *
     * @Route("/new", name="add_book")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        // учимся выводить отладку в логи
        $this->container->get('logger')->addDebug('book addAction');

        $book = new Book();
        $book->setContainer($this->container);

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $book->upload();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash('success', 'post.created_successfully');


            return $this->redirectToRoute('homepage');
        }

        return $this->render('book/new.html.twig', [
            'post' => $book,
            'form' => $form->createView(),
        ]);

    }

    /**
     * Edit a  Book entity.
     *
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit_book")
     * @Method({"GET", "POST"})
     *
     * @param Book $book
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Book $book, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $editForm = $this->createForm(BookType::class, $book);
        $deleteForm = $this->createDeleteForm($book);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $unlinkFiles = $request->get('unlinkFiles');
            if (!is_null($unlinkFiles)) {
                foreach ($unlinkFiles as $property => $value) {
                    $method = 'set' . str_replace('upload', '', $property);
                    $book->$method(null);
                }
            }
            $book->upload();
            $entityManager->flush();
            $this->addFlash('success', 'post.updated_successfully');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }


    /**
     * Deletes a entity.
     *
     * @Route("/{id}",requirements={"id": "\d+"}, name="book_delete")
     * @Method("DELETE")
     *
     * @param Book $book
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Book $book, Request $request)
    {
        $form = $this->createDeleteForm($book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($book);
            $entityManager->flush();

            $this->addFlash('success', 'post.deleted_successfully');
        }

        return $this->redirectToRoute('homepage');
    }


    /**
     * Creates a form to delete  entity by id.
     *
     * @param  Book $book
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Book $book)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('book_delete', ['id' => $book->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }


}