<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer  as JMS;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/api/v1")
 */
class ApiVersionOneController extends Controller
{
    /**
     *  @Route("/")
     */
    public function listAction() {

        $books = $this->getDoctrine()->getRepository('AppBundle:Book')->getList();

        $serializer = JMS\SerializerBuilder::create()->build();
        $serialized =  $serializer->serialize($books, 'json', JMS\SerializationContext::create()->setGroups(array('list')));

        $response = new JsonResponse($serialized,200,[],true);

        return $response;
    }
}
