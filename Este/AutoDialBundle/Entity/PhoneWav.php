<?php

namespace AutoDialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PhoneWav
 *
 * @ORM\Table(name="phone_wav")
 * @ORM\Entity(repositoryClass="AutoDialBundle\Repository\PhoneWavRepository")
 */
class PhoneWav
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
     * @ORM\Column(name="phone", type="string", length=15)
     */
    private $phone;

    /**
     * @var int
     *
     * @ORM\Column(name="dial_rule_id", type="integer")
     */
    private $dialRuleId;

    /**
     * @var string
     *
     * @ORM\Column(name="sound", type="string", length=255)
     */
    private $sound;

    /**
     * @var string
     *
     * @ORM\Column(name="upload", type="string", length=1, nullable=true)
     */
    private $upload;


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
     * Set phone
     *
     * @param string $phone
     *
     * @return PhoneWav
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set dialRuleId
     *
     * @param integer $dialRuleId
     *
     * @return PhoneWav
     */
    public function setDialRuleId($dialRuleId)
    {
        $this->dialRuleId = $dialRuleId;

        return $this;
    }

    /**
     * Get dialRuleId
     *
     * @return int
     */
    public function getDialRuleId()
    {
        return $this->dialRuleId;
    }

    /**
     * Set sound
     *
     * @param string $sound
     *
     * @return PhoneWav
     */
    public function setSound($sound)
    {
        $this->sound = $sound;

        return $this;
    }

    /**
     * Get sound
     *
     * @return string
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * Set upload
     *
     * @param string $upload
     *
     * @return PhoneWav
     */
    public function setUpload($upload)
    {
        $this->upload = $upload;

        return $this;
    }

    /**
     * Get upload
     *
     * @return string
     */
    public function getUpload()
    {
        return $this->upload;
    }
}

