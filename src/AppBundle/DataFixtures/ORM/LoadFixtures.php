<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Book;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBooks($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');

        $johnUser = new User();
        $johnUser->setUsername('user');
        $johnUser->setEmail('john_user@symfony.com');
        $encodedPassword = $passwordEncoder->encodePassword($johnUser, 'user');
        $johnUser->setPassword($encodedPassword);
        $manager->persist($johnUser);

        $manager->flush();
    }

    private function loadBooks(ObjectManager $manager)
    {
        foreach (range(1, 30) as $i) {
            $book = new Book();

            $book->setName($this->getRandomName());
            $book->setAuthor($this->getRandomAuthor());
            $book->setCover('');
            $book->setFilename('');
            $book->setAllowedDownload(FALSE);
            $book->setDateRead(new \DateTime('now'));

            $manager->persist($book);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    private function getPhrases()
    {
        return [
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
        ];
    }

    private function getRandomName()
    {
        $titles = $this->getPhrases();

        return $titles[array_rand($titles)];
    }

    private function getRandomAuthor()
    {
        $titles = [
            'Иванов А.И.',
            'Петров П.В',
            'Сидоров В.С.'
        ];

        return $titles[array_rand($titles)];
    }


}
