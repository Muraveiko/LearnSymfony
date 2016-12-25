<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use JMS\Serializer\Annotation as Serialization;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Book
 *
 * @ORM\Table(name="book",indexes={@ORM\Index(name="date_read_idx", columns={"date_read"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @Serialization\ExclusionPolicy("ALL")
 */
class Book implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var  array | null
     */
    static private $uploadDirs = null;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serialization\Expose
     * @Serialization\Groups({"book_details", "book_list"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Name must be at least {{ limit }} characters long",
     *      maxMessage = "Name cannot be longer than {{ limit }} characters"
     * )
     *
     * @Serialization\Expose
     * @Serialization\Groups({"book_details", "book_list"})
     * @Serialization\SerializedName("book_name")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=128)
     * @Assert\Length(
     *      min = 1,
     *      max = 127,
     *      minMessage = "Author must be at least {{ limit }} characters long",
     *      maxMessage = "Author cannot be longer than {{ limit }} characters"
     * )
     *
     * @Serialization\Expose
     * @Serialization\Groups({"book_details", "book_list"})
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", length=128, nullable=true)
     */
    private $cover;

    /**
     * @var mixed
     *
     * @Assert\File(
     *     maxSize="1m",
     *     mimeTypes={ "image/jpeg","image/png"}
     * )
     *
     */
    private $uploadCover = null;


    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=true)
     */
    private $bookFile;


    /**
     * @var mixed
     *
     * @Assert\File(
     *     maxSize="5m",
     *     mimeTypes={ "application/pdf","application/epub+zip","application/rtf","text/rtf","text/plain" }
     * )
     */
    private $uploadBookFile = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_read", type="date")
     * @Assert\Date()
     *
     * @Serialization\Expose
     * @Serialization\Groups({"book_details", "book_list"})
     * @Serialization\Type("DateTime<'Y-m-d'>")
     */
    private $dateRead;

    /**
     * @var bool
     *
     * @ORM\Column(name="allowed_download", type="boolean")
     *
     * @Serialization\Expose
     * @Serialization\Groups({"book_details", "book_list"})
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

            self::$uploadDirs = [
                'image' => $this->container->getParameter('book_image_directory'),
                'file' => $this->container->getParameter('book_file_directory'),
            ];
        }

        $dir = self::$uploadDirs[$type] . mb_substr($filename, 0, 2);
        if (!is_dir($dir) && (false === @mkdir($dir, 0775, true))) {
            throw new \Exception('directory not writable');
        }
        if (!is_writable($dir)) {
            throw new \Exception('directory not writable');
        }

        return $dir;

    }

    /**
     * @return string
     */
    public function getCoverSubDir()
    {
        return mb_substr($this->cover, 0, 2);
    }


    /**
     * @return string
     */
    public function getFileSubDir()
    {
        return mb_substr($this->bookFile, 0, 2);
    }


    /**
     *
     */
    public function upload()
    {
        $this->uploadBookFile();
        $this->uploadCover();
    }

    /**
     *
     */
    public function uploadBookFile()
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

    /**
     *
     */
    public function uploadCover()
    {
        $this->container->get('logger')->addDebug('call uploadCover()');
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
            $this->container->get('logger')->addDebug('move '.$fileCover);
            $this->setCover($fileCover);
        }

        $this->uploadCover = null;
    }

    /**
     *
     */
    public function removeUpload()
    {
        $this->removeBookFile($this->bookFile);
        $this->removeCover($this->cover);
    }

    /**
     * @param null $cover
     * @return void
     */
    public function removeCover($cover = null)
    {
        if (null === $cover) return;
        $file = $this->getPathCover($cover);
        @unlink($file);
        return;
    }
    /**
     * @param null $bookFile
     * @return void
     */
    public function removeBookFile($bookFile = null)
    {
        if (null === $bookFile) return;
        $file = $this->getPathBookFile($bookFile);
        @unlink($file);
        return;
    }

    /**
     * @param null $cover
     * @return null|string
     */
    public function getPathCover($cover = null)
    {
        if (null === $cover) $cover = $this->cover;
        if (null === $cover) return null;
        return $this->getUploadDir($cover, 'image') . DIRECTORY_SEPARATOR . $cover;
    }

    /**
     * @param null $bookFile
     * @return null|string
     */
    public function getPathBookFile($bookFile = null)
    {
        if (null === $bookFile) $bookFile = $this->bookFile;
        if (null === $bookFile) return null;
        return $this->getUploadDir($bookFile, 'file') . DIRECTORY_SEPARATOR . $bookFile;
    }



    /**
     * @Serialization\VirtualProperty
     * @Serialization\Groups({"book_list"})
     *
     * @return null|string
     */
    public function getCoverUrl()
    {
        if (null === $this->cover) return null;
        return $this->container->get('router')->generate('_book_cover', ['image' => $this->cover, 'subdir' => $this->getCoverSubDir()],UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @Serialization\VirtualProperty
     * @Serialization\Groups({"book_list"})
     *
     * @return null|string
     */
    public function getBookUrl()
    {
        if (null === $this->bookFile) return null;
        if (false === $this->allowedDownload) return null;
        return $this->container->get('router')->generate('book_download', ['id' => $this->id],UrlGeneratorInterface::ABSOLUTE_URL);
    }


    /**
     * @return null|string
     */
    public function infoCover()
    {
        if (null === $this->cover) return null;
        $file = $this->getUploadDir($this->cover, 'image') . DIRECTORY_SEPARATOR . $this->cover;
        if (!file_exists($file)) {
            return 'notFound';
        }

        return $this->cover . ' (' . @filesize($file) . ' bytes) ';
    }

    /**
     * @return null|string
     */
    public function infoBookFile()
    {
        if (null === $this->bookFile) return null;
        $file = $this->getUploadDir($this->bookFile, 'file') . DIRECTORY_SEPARATOR . $this->bookFile;
        if (!file_exists($file)) {
            return 'notFound';
        }

        return $this->bookFile . ' (' . @filesize($file) . ' bytes) ';
    }

}

