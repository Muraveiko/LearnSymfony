<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Book;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class DownloadController extends Controller
{
    /**
     * @Route("/download/{id}", requirements={"id": "\d+"}, name="book_download")
     */
    public function downloadAction(Book $book)
    {

        if(!$book->getAllowedDownload()){
            throw $this->createAccessDeniedException();
        }

        $fileName = $book->getPathBookFile();

        $ext = substr(strrchr($fileName, '.'), 1);
        $downloadName = $book->getAuthor().'.'.$book->getName().'.'.$ext;

        $response = new BinaryFileResponse($fileName);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT,$downloadName);

        return $response;
    }
}
