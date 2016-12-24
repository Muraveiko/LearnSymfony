<?php

namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;

/**
 * Class CacheBooksSubscriber
 * актулизация кеша списка книг
 */
class CacheBooksSubscriber implements EventSubscriber
{


    /**
     * @inheritdoc
     */
    public function getSubscribedEvents()
    {
        return [
            'onFlush',
        ];
    }

    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $args->getEntityManager()->getConfiguration()->getResultCacheImpl()->delete('book_get_list');
    }

}