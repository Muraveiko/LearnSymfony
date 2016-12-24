<?php

namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;


/**
 * Class ContainerForDoctrineSubscriber
 *
 *  Внедряет в сущности возможность доступа к компонентам симфони
 *
 * @package AppBundle\EventListener
 */
 class ContainerForDoctrineSubscriber implements EventSubscriber,ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    private $logger;
    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return ['postLoad'];
    }

     /**
      * @param LifecycleEventArgs $args
      * После создания объекта в ручную вызовете SetContainer или persist нужно звать сразу после конструктора
      */
    public function prePersist(LifecycleEventArgs $args) {
        if( 1 == 1 ) { // заглушено
            throw new \Exception('читайте примечание ');
        }
        $this->fixContainer($args);
    }

    public function postLoad(LifecycleEventArgs $args) {
        $this->fixContainer($args);
    }

    public function fixContainer(LifecycleEventArgs $args){
        $entity = $args->getEntity();

        if($entity instanceof ContainerAwareInterface) {
            $entity->setContainer($this->container);
        }

    }

}