<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Book
 *
 * @ORM\Table(name="book",indexes={@ORM\Index(name="date_read_idx", columns={"date_read"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 */
class Book
{
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
     * @ORM\Column(name="cover", type="string", length=45)
     * @Assert\File(mimeTypes={ "image/jpeg","image/png"})
     */
    private $cover;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     * @Assert\File(mimeTypes={ "application/pdf","application/epub+zip","application/rtf","text/rtf","text/plain" })
     */
    private $filename;

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
     * Set filename
     *
     * @param string $filename
     *
     * @return Book
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
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
     * @var
     */
    private $uploadDirs = array();

    /**
     * @param $dir
     * @param string $type
     * @return $this
     */
    public function setUploadDir($dir,$type='image') {
        $this->uploadDirs[$type] = $dir;

        return $this;
    }

    /**
     * @param string $filename
     * @return null|string
     */
    static public function _fixPath($filename) {
        if(null === $filename) {
            return null;
        }
        return mb_substr($filename,0,2).'/'.$filename;
    }

    /**
     * @param $file
     * @param string $type [image|file]
     * @return null|string
     */
    public function getUploadDir($filename,$type='image')
    {
        if(null === $filename) {
            return null;
        }

        $dir = $this->uploadDirs[$type].DIRECTORY_SEPARATOR.mb_substr($filename,0,2);
        @mkdir($dir,0755);

        return $dir;

    }

    public function getCoverSubDir()
    {
        return mb_substr($this->cover,0,2);
    }

}

