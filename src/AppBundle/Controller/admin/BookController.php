<?php

namespace AppBundle\Controller\admin;

use AppBundle\Form\BookType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Book;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



/**
 * @Route("/book")
 */
class BookController extends Controller
{
    /**
     * Creates a new Book entity.
     *
     * @Route("/new", name="add_book")
     * @Method({"GET", "POST"})
     *
     */
    public function addAction(Request $request)
    {
        $this->container->get('logger')->addDebug('book addAction');
        $book = new Book();
        $book->setContainer($this->container);

        $book->setDateRead(new \DateTime('today'));

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
     * Creates a new Book entity.
     *
     * @Route("/{id}/edit", requirements={"id": "\d+"}, name="edit_book")
     * @Method({"GET", "POST"})
     *
     */
    public function editAction(Book $book, Request $request) {
        $entityManager = $this->getDoctrine()->getManager();

        $editForm = $this->createForm(BookType::class, $book);
        $deleteForm = $this->createDeleteForm($book);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $unlinkFiles = $request->get('unlinkFiles');
            if(!is_null($unlinkFiles)) {
                foreach ($unlinkFiles as $property => $value) {
                    $method = 'set'.str_replace('upload','',$property);
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
     * Deletes a Post entity.
    *
     * @Route("/{id}",requirements={"id": "\d+"}, name="book_delete")
     * @Method("DELETE")
     *
     * The Security annotation value is an expression (if it evaluates to false,
     * the authorization mechanism will prevent the user accessing this resource).
     * The isAuthor() method is defined in the AppBundle\Entity\Post entity.
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
            ->getForm()
            ;
    }


}