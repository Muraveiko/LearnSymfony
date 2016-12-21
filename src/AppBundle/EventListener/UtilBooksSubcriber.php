<?php

namespace AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class UtilBooksSubcriber implements EventSubscriber,ContainerAwareInterface
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
        $this->logger = $this->container->get('logger');
        $this->logger->debug('UtilBooksSubcriber constuct !');
    }

    public function getSubscribedEvents()
    {
        return ['prePersist','onLoad'];
    }

    public function prePersist(LifecycleEventArgs $args) {
        $this->fixContainer($args);
    }

    public function onLoad(LifecycleEventArgs $args) {
        $this->fixContainer($args);
    }

    public function fixContainer(LifecycleEventArgs $args){
        $this->logger->debug('fixContainer');
        $entity = $args->getEntity();

        if($entity instanceof ContainerInterface) {
            $entity->setContainer($this->container);
        }

    }

}