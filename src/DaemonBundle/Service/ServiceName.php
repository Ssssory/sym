<?php

namespace DaemonBundle\Service;

use Doctrine\ORM\EntityManager;

class ServiceName {

    /** @var EntityManager  */
    private $em;

    /**
     * ServiceName constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
}