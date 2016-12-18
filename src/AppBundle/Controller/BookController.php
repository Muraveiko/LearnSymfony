<?php

namespace AppBundle\Controller;

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
        $book = new Book();

        $book->setUploadDir($this->getParameter('book_file_directory'),'file')
             ->setUploadDir($this->getParameter('book_image_directory'),'image');

        $form = $this->createForm(BookType::class, $book)
            ->add('save', SubmitType::class)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file1 */
            $file1 = $book->getFilename();
            $fileName = md5(uniqid()).'.'.$file1->guessExtension();
            $file1->move(
                $book->getUploadDir($fileName,'file'),
                $fileName
            );
            $book->setFilename($fileName);

            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file2 */
            $file2 = $book->getCover();
            $fileCover = md5(uniqid()).'.'.$file2->guessExtension();
            $file2->move(
                $book->getUploadDir($fileCover,'image'),
                $fileCover
            );
            $book->setcover($fileCover);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See http://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'post.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('add_book');
            }

            return $this->redirectToRoute('homepage');
        }

        return $this->render('book/new.html.twig', [
            'post' => $book,
            'form' => $form->createView(),
        ]);

    }
}