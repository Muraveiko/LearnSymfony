<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


/**
 * Book
 *
 * @ORM\Table(name="book",indexes={@ORM\Index(name="date_read_idx", columns={"date_read"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Book implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=128)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=128)
     */
    private $cover;

    /**
     * @var
     *
     * @Assert\File(mimeTypes={ "image/jpeg","image/png"})
     */
    private $uploadCover;


    public function __construct($container=null)
    {
        if($container instanceof ContainerInterface ) {
            $this->container = $container;
        }
    }

    /**
     * @return mixed
     */
    public function getUploadCover()
    {
        return $this->uploadCover;
    }

    /**
     * @param UploadedFile $uploadCover
     */
    public function setUploadCover(UploadedFile $uploadCover)
    {
        $this->uploadCover = $uploadCover;
    }

    /**
     * @return string
     */
    public function getBookFile()
    {
        return $this->bookFile;
    }

    /**
     * @param string $bookFile
     */
    public function setBookFile($bookFile)
    {
        $this->bookFile = $bookFile;
    }

    /**
     * @return mixed
     */
    public function getUploadBookFile()
    {
        return $this->uploadBookFile;
    }

    /**
     * @param UploadedFile $uploadBookFile
     */
    public function setUploadBookFile(UploadedFile $uploadBookFile)
    {
        $this->uploadBookFile = $uploadBookFile;
    }

    /**
     * @return array|null
     */
    public static function getUploadDirs()
    {
        return self::$uploadDirs;
    }

    /**
     * @param array|null $uploadDirs
     */
    public static function setUploadDirs($uploadDirs)
    {
        self::$uploadDirs = $uploadDirs;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $bookFile;


    /**
     * @var
     *
     * @Assert\File(mimeTypes={ "application/pdf","application/epub+zip","application/rtf","text/rtf","text/plain" })
     */
    private $uploadBookFile;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_read", type="date")
     */
    private $dateRead;

    /**
     * @var bool
     *
     * @ORM\Column(name="allowed_download", type="boolean")
     */
    private $allowedDownload;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Book
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Book
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set cover
     *
     * @param string $cover
     *
     * @return Book
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }


    /**
     * Set dateRead
     *
     * @param \DateTime $dateRead
     *
     * @return Book
     */
    public function setDateRead($dateRead)
    {
        $this->dateRead = $dateRead;

        return $this;
    }

    /**
     * Get dateRead
     *
     * @return \DateTime
     */
    public function getDateRead()
    {
        return $this->dateRead;
    }

    /**
     * Set allowedDownload
     *
     * @param boolean $allowedDownload
     *
     * @return Book
     */
    public function setAllowedDownload($allowedDownload)
    {
        $this->allowedDownload = $allowedDownload;

        return $this;
    }

    /**
     * Get allowedDownload
     *
     * @return bool
     */
    public function getAllowedDownload()
    {
        return $this->allowedDownload;
    }


    /**
     * @var  array | null
     */
    static private $uploadDirs = null;

    /**
     * @param $dir
     * @param string $type
     * @return $this
     */
    public function setUploadDir($dir, $type = 'image')
    {
        self::$uploadDirs[$type] = $dir;

        return $this;
    }


    /**
     * @param $file
     * @param string $type [image|file]
     * @return null|string
     */
    public function getUploadDir($filename, $type = 'image')
    {
        if (null === $filename) {
            return null;
        }
        if (null === self::$uploadDirs) {
            $container = new ContainerBuilder();
            $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
            $loader->load('Book.yml');

            self::$uploadDirs = array(
                'image' => $container->getParameter('book_image_directory'),
                'file' => $container->getParameter('book_file_directory'),
            );
        }

        $dir = self::$uploadDirs[$type] . mb_substr($filename, 0, 2);
        @mkdir($dir, 0755);

        return $dir;

    }

    public function getCoverSubDir()
    {
        return mb_substr($this->cover, 0, 2);
    }


    public function getFileSubDir()
    {
        return mb_substr($this->bookFile, 0, 2);
    }


    public function upload()
    {
        $this->uploadBook();
        $this->uploadCover();
    }

    public function uploadBook()
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $this->getUploadBookFile();

        if (null === $file) {
            return;
        }

        if ($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getUploadDir($fileName, 'file'),
                $fileName
            );
            $this->setBookFile($fileName);
        }

        $this->uploadBookFile = null;
    }

    public function uploadCover()
    {
        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $this->getUploadCover();

        if (null === $file) {
            return;
        }

        if ($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
            $fileCover = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getUploadDir($fileCover, 'image'),
                $fileCover
            );
            $this->setCover($fileCover);
        }

        $this->uploadCover = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {

    }


    public function getcoverUrl()
    {
        if(null === $this->cover) return null;
        return $this->container->get('router')->generate('_book_cover',['image'=>$this->cover,'subdir'=>$this->getCoverSubDir()]);
    }
}

