<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Book;
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
     */
    public function listAction()
    {

        $books = $this->getDoctrine()->getRepository('AppBundle:Book')->getList();

        $serializer = JMS\SerializerBuilder::create()->build();
        $serialized = $serializer->serialize($books, 'json', JMS\SerializationContext::create()->setGroups(array('book_list')));

        $response = new JsonResponse($serialized, 200, [], true);

        return $response;
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
     */
    public function detailsAction($id)
    {
        $entity = $this->getDoctrine()->getRepository('AppBundle:Book')->find($id);

        $serializer = JMS\SerializerBuilder::create()->build();
        $serialized = $serializer->serialize($entity, 'json', JMS\SerializationContext::create()->setGroups(array('book_details')));

        return new JsonResponse($serialized, 200, [], true);
    }

    /**
     * @Route("/books/add")
     */
    public function addAction()
    {

    }
}
