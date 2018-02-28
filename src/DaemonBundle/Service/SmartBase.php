<?php
namespace DaemonBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use Doctrine\ORM\Mapping as ORM;


class SmartBase extends EntityRepository
{
  // /**
  //  * @param EntityManager $em
  //  */
  // function __construct(EntityManager $em)
  // {
  //   $this->em = $em;
  // }
  /**
 * Initializes a new <tt>EntityRepository</tt>.
 *
 * @param EntityManagerInterface $em    The EntityManager to use.
 * @param Mapping\ClassMetadata  $class The class descriptor.
 */
  function __construct($em, Mapping\ClassMetadata $class)
   {
       parent::__construct($em, $class);
       $this->CI =& get_instance(); // looks strange
   }

  function insertInToLog(){
    return $this->getEntityManager()
      ->createQuery('SELECT p FROM DaemonBundle:AmiInputEvents p ORDER BY p.id ASC')
      ->getResult();
  }


}
