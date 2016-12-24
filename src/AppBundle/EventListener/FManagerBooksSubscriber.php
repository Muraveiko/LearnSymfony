<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Book;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 *  удаление картинок и файлов, которые больше не требуются
 */
class FManagerBooksSubscriber implements EventSubscriber
{

    /**
     * @inheritdoc
     */
    public function getSubscribedEvents()
    {
        return [
            'preUpdate',
            'preRemove',
        ];
    }

    /**
     * При перезаливке
     *
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Book) {
            if ($args->hasChangedField('cover')) {
                $entity->removeCover($args->getOldValue('cover'));
            }
            if ($args->hasChangedField('bookFile')) {
                $entity->removeBookFile($args->getOldValue('bookFile'));
            }
        }
    }

    /**
     * При удалении книги
     *
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Book) {
            $entity->removeUpload();
        }
    }

    /**
     *  Вариант добраться к удаляемым объектам через флуш
     * @param OnFlushEventArgs $args
     * @deprecated
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();


        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof Book) {
                $entity->removeUpload();
            }

        }

    }

}