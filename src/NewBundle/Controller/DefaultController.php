<?php

namespace NewBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/new")
     */
    public function indexAction()
    {
      die('New!!!!!!!!!!!');
        return $this->render('NewBundle:Default:index.html.twig');
    }
}
