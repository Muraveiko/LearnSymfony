<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer as JMS;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @Route("/api/v1")
 */
class ApiVersionOneController extends Controller
{
    /**
     * @Route("/books")
     *  пришлось описывать так, в задании нет слеша в конце
     *  в .htaccess нужно добавить
     *  DirectorySlash off
     *
     * @View(serializerGroups={"book_list"})
     */
    public function listAction()
    {
       $books = $this->getDoctrine()->getRepository('AppBundle:Book')->getList();

        return $books;
    }

    /**
     * @Route("/books/")
     */
    public function wrongHtaccessAction()
    {
       @trigger_error('add DirectorySlash off in .htaccess',E_USER_WARNING);

       throw new NotFoundHttpException();
    }


    /**
     * @Route("/books/{id}", requirements={"id": "\d+"})
     *
     * @View(serializerGroups={"book_details"})
     */
    public function detailsAction($id)
    {
        $entity = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);

        return $entity;
    }

    /**
     * @Route("/books/add")
     */
    public function addAction()
    {

    }
}
