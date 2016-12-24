<?php

namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;

/**
 * Class CacheBooksSubscriber
 * @package AppBundle\EventListener
 */
class CacheBooksSubscriber implements EventSubscriber
{


    /**
     * @inheritdoc
     */
    public function getSubscribedEvents()
    {
        return array(
            'onFlush',
        );
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $args->getEntityManager()->getConfiguration()->getResultCacheImpl()->delete('book_get_list');
    }

}