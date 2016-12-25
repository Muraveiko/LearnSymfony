<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations as REST;
use AppBundle\Entity\Book;


/**
 * @REST\Route("/api/v1")
 *
 */
class ApiVersionOneController extends Controller
{
    /**
     * @REST\Route("/books")
     *  пришлось описывать так, в задании нет слеша в конце
     *  в .htaccess нужно добавить
     *  DirectorySlash off
     *
     * @REST\View(serializerGroups={"book_list"})
     */
    public function listAction()
    {
        $books = $this->getDoctrine()->getRepository('AppBundle:Book')->getList();

        return $books;
    }

    /**
     * @REST\Route("/books/")
     */
    public function wrongHtaccessAction()
    {
        @trigger_error('add DirectorySlash off in .htaccess', E_USER_WARNING);

        throw new NotFoundHttpException();
    }


    /**
     * @REST\Route("/books/{id}", requirements={"id": "\d+"})
     *
     * @REST\View(serializerGroups={"book_details"})
     */
    public function detailsAction(Book $entity)
    {
        return $entity;
    }


    /**
     * @REST\POST()
     * @REST\Route("/books/add")
     */
    public function addAction()
    {
    }
}
