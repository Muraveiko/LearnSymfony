<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Book;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class FManagerBooksSubscriber implements EventSubscriber
{


    public function getSubscribedEvents()
    {
        return array(
            'preUpdate',
            'preRemove'
        );
    }

    public function preUpdate(PreUpdateEventArgs $args) {
        $entity = $args->getObject();
        if($entity instanceof Book) {
            if($args->hasChangedField('cover')){
                $entity->removeCover($args->getOldValue('cover'));
            }
        }
    }
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if($entity instanceof Book) {
            $entity->removeUpload();
        }
    }

    /**
     *  Вариант добраться к удаляемым объектам через флуш
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();


        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if($entity instanceof Book) {
                 $entity->removeUpload();
            }

        }

    }

}