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
 */
class ContainerForDoctrineSubscriber implements EventSubscriber, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * ContainerForDoctrineSubscriber constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function getSubscribedEvents()
    {
        return ['postLoad'];
    }

    /**
     * @param LifecycleEventArgs $args
     * @deprecated
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        @trigger_error(sprintf('После создания объекта в ручную вызовете SetContainer или persist нужно звать сразу после конструктора'), E_USER_DEPRECATED);
        $this->fixContainer($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $this->fixContainer($args);
    }

    /**
     * Внедрение связи с контейнером из доктрины
     *
     * @param LifecycleEventArgs $args
     */
    public function fixContainer(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof ContainerAwareInterface) {
            $entity->setContainer($this->container);
        }

    }

}