<?php

namespace AutoDialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserSound
 *
 * @ORM\Table(name="user_sound")
 * @ORM\Entity(repositoryClass="AutoDialBundle\Repository\UserSoundRepository")
 */
class UserSound
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
     * @ORM\Column(name="user", type="string", length=120)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=40)
     */
    private $hash;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="appruved", type="string", length=100, nullable=true)
     */
    private $appruved;

    /**
     * @var string
     *
     * @ORM\Column(name="uploaded", type="string", length=1, nullable=true)
     */
    private $uploaded;


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
     * Set user
     *
     * @param string $user
     *
     * @return UserSound
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return UserSound
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
     * Set hash
     *
     * @param string $hash
     *
     * @return UserSound
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return UserSound
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set appruved
     *
     * @param string $appruved
     *
     * @return UserSound
     */
    public function setAppruved($appruved)
    {
        $this->appruved = $appruved;

        return $this;
    }

    /**
     * Get appruved
     *
     * @return string
     */
    public function getAppruved()
    {
        return $this->appruved;
    }

    /**
     * Set uploaded
     *
     * @param string $uploaded
     *
     * @return UserSound
     */
    public function setUploaded($uploaded)
    {
        $this->uploaded = $uploaded;

        return $this;
    }

    /**
     * Get uploaded
     *
     * @return string
     */
    public function getUploaded()
    {
        return $this->uploaded;
    }
}

