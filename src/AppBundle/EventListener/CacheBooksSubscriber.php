<?php
/**
 * Created by PhpStorm.
 * User: mur
 * Date: 14.12.2016
 * Time: 21:34
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Book;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CacheBooksSubscriber implements EventSubscriber
{


    public function getSubscribedEvents()
    {
        return array(
            'onFlush',
        );
    }

    public function onFlush(OnFlushEventArgs $args)
    {
            $args->getEntityManager()->getConfiguration()->getResultCacheImpl()->delete('book_get_list');
    }

}