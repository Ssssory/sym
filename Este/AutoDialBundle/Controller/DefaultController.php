<?php

namespace AutoDialBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Doctrine\ORM\EntityManager;

// Este
use AutoDialBundle\Entity\Dial;
use AutoDialBundle\Entity\Sound;
use AutoDialBundle\Entity\UserSound;
use AutoDialBundle\Entity\Phones;
use AutoDialBundle\Entity\Rules;

class DefaultController extends Controller
{
  // костыль на время отладки
  private $currentDialUrl = '/sym/call/web/app_dev.php';
    /**
     * @Route("/dial", name="dial")
     */
    public function indexAction()
    {

      $allDial = $this->getDoctrine()
        ->getRepository('AutoDialBundle:Dial')
        ->findAll();

        return $this->render('dial/index.html.twig', array(
          'breadcrumbs'=>'Автоинформатор',
          'canonicl'=>$this->currentDialUrl,
          'dials' => $allDial
        ));
    }

    /**
     * @Route("/dial/list/{id}", name="dial_list")
     */
    public function listAction($id, Request $request)
    {
        $oneDial = $this->getDoctrine()
          ->getRepository('AutoDialBundle:Dial')
          ->find($id);

        if (empty($oneDial)) {
          $oneDial ='empty';
        }
        $userPhones = $this->getDoctrine()->getEntityManager()
          ->createQuery('
              SELECT p FROM AutoDialBundle:Phones p
              WHERE p.dial = :dial AND p.active = :active
          ')->setParameters(array('dial'=>$id,'active'=>'Y'));
        if (empty($userPhones)) {
          $userPhones = array();
        }

        return $this->render('dial/list.html.twig', array(
          'breadcrumbs'=>'Информация об обзвоне',
          'dial'=>$oneDial,
          'users'=>count($userPhones->getResult()),
          'list'=>$userPhones->getResult()
        ));
    }

    /**
     * @Route("/dial/list/{id}/add", name="dial_list_add")
     */
    public function listAction1($id, Request $request)
    {
        $oneDial = $this->getDoctrine()
          ->getRepository('AutoDialBundle:Dial')
          ->find($id);

        if (empty($oneDial)) {
          $oneDial ='empty';
        }

        $form = $this->createFormBuilder()
          ->add('file', FileType::class, array(
            'attr'=> array('class'=>'form-group'),
            'label'=>'Таблица абонентов в CSV '
          ))
          ->add('Добавить номера', SubmitType::class, array('attr'=> array('class'=>'btn btn-success')))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $file = $form['file']->getData();
          $file->move('/var/www/html/sym/call/var','temp.csv');

          $content = file_get_contents('/var/www/html/sym/call/var/temp.csv');
          unlink('/var/www/html/sym/call/var/temp.csv');
          $arContent = explode("\n",trim($content));
          foreach ($arContent as $key => $value) {
            $currentNum = explode(";",$value);
            // foreach ($currentNum as $keyNN => $phoneValue) {
              $phoneEnt = new Phones;

              $phone = $currentNum[6];
              $fio = $currentNum[4];
              $addr = $currentNum[5];
              $opt1 = $currentNum[1];
              $opt2 = '';
              $opt3 = '';

              $active = 'Y';
              $dial = $id;
              $date = date('d-m-Y H:i');

              $phoneEnt->setPhone($phone);
              $phoneEnt->setFio($fio);
              $phoneEnt->setDial($dial);
              $phoneEnt->setActive($active);
              $phoneEnt->setDate($date);
              $phoneEnt->setAddr($addr);
              $phoneEnt->setOpt1($opt1);
              $phoneEnt->setOpt2($opt2);
              $phoneEnt->setOpt3($opt3);

              $em = $this->getDoctrine()->getManager();
              $em->persist($phoneEnt);
              $em->flush();
            // }
          }
          // return $this->render('default/empty.html.twig', array(
          //   'tmp'=>$dial,
          // ));
          return $this->redirectToRoute('dial_list',array('id'=>$id));
        }

        // $POST = $request->request->get()

        return $this->render('dial/list.add.html.twig', array(
          'breadcrumbs'=>'Добавить пользователей к обзвону',
          'dial'=>$oneDial,
          'form'=>$form->createView(),
        ));
    }

    /**
     * @Route("/dial/add", name="dial_add")
     */
    public function addAction(Request $request)
    {
        // $post = new Post();
        $dial = new Dial;
        $form = $this->createFormBuilder($dial)
          ->add('name', TextType::class, array(
            'attr'=> array('class'=>'form-group'),
            'label'=>'Название Обзвона '
          ))
          ->add('date_start', TextType::class, array(
            'attr'=> array('class'=>'form-group'),
            'label'=>'Дата начала обзвона в формате дд-мм-гггг '
          ))
          ->add('comment', TextType::class, array(
            'attr'=> array('class'=>'form-group'),
            'label'=>'Комментарий ',
            'required'=>false
          ))
          ->add('create', SubmitType::class, array('attr'=> array('class'=>'btn btn-success')))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

          $name = $form['name']->getData();
          $date_start = $form['date_start']->getData();
          $active = 'Y';
          $date_create = date('d-m-Y');
          $comment = $form['comment']->getData();
          if (empty($comment)) {
            $comment = '';
          }

          // $str = $name.' '.$date_start.' '.$active.' '.$date_create.' '.$comment;
          $dial->setName($name);
          $dial->setDateStart($date_start);
          $dial->setActive($active);
          $dial->setDateCreate($date_create);
          $dial->setComment($comment);

          $dial->setDateCallStart('');
          $dial->setDateCallEnd('');

          $em = $this->getDoctrine()->getManager();
          $em->persist($dial);
          $em->flush();

          $this->addFlash(
            'notice',
            'обзвон добавлен'
          );
          // die($str);

          return $this->redirectToRoute('dial');
        }

        return $this->render('dial/add.html.twig', array(
          'breadcrumbs'=>'Добавить обзвон',
          'form'=>$form->createView(),
        ));
    }


    /**
     * @Route("/dial/{id}/edit", name="dial_edit")
     */
    public function editAction($id, Request $request)
    {
      $oneDial = $this->getDoctrine()
        ->getRepository('AutoDialBundle:Dial')
        ->find($id);

      $userPhones = $this->getDoctrine()->getEntityManager()
        ->createQuery('
            SELECT p FROM AutoDialBundle:Phones p
            WHERE p.dial = :dial AND p.active = :active
        ')->setParameters(array('dial'=>$id,'active'=>'Y'));

      if (empty($userPhones)) {
        $userPhones = array();
      }

      $form = $this->createFormBuilder($oneDial)
        ->add('name', TextType::class, array(
          'attr'=> array('class'=>'form-group'),
          'label'=>'Название Обзвона '
        ))
        ->add('active', TextType::class, array(
          'attr'=> array('class'=>'form-group'),
          'label'=>'Активность '
        ))
        ->add('date_create', TextType::class, array(
          'attr'=> array('class'=>'form-group'),
          'label'=>'Дата создания '
        ))
        ->add('date_start', TextType::class, array(
          'attr'=> array('class'=>'form-group'),
          'label'=>'Дата начала обзвона '
        ))
        ->add('date_call_start', TextType::class, array(
          'attr'=> array('class'=>'form-group'),
          'label'=>'Обзвон стартовал '
        ))
        ->add('date_call_end', TextType::class, array(
          'attr'=> array('class'=>'form-group'),
          'label'=>'Обзвон закончился '
        ))
        ->add('comment', TextType::class, array(
          'attr'=> array('class'=>'form-group'),
          'label'=>'Комментарий ',
          'required'=>false
        ))
        ->add('Сохранить', SubmitType::class, array('attr'=> array('class'=>'btn btn-success')))
        ->getForm();

        $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $name = $form['name']->getData();
        $active = $form['active']->getData();
        $date_create = $form['date_create']->getData();
        $date_start = $form['date_start']->getData();
        $date_call_start = $form['date_call_start']->getData();
        $date_call_end = $form['date_call_end']->getData();
        $comment = $form['comment']->getData();

        $oneDial->setName($name);
        $oneDial->setActive($active);
        $oneDial->setDateCreate($date_create);
        $oneDial->setDateCallStart($date_call_start);
        $oneDial->setDateCallEnd($date_call_end);
        $oneDial->setDateStart($date_start);
        $oneDial->setComment($comment);
        $em = $this->getDoctrine()->getManager();
        $em->persist($oneDial);
        $em->flush();
        return $this->redirectToRoute('dial_list',array('id'=>$id));
      }

        return $this->render('dial/edit.html.twig', array(
        'breadcrumbs'=>'Редактирование обзвона',
        'dial'=>$oneDial,
        'form'=>$form->createView()
      ));
    }

    /**
     * @Route("/dial/list/{id}/clear", name="dial_list_clear")
     */
    public function clearUsertAction($id, Request $request)
    {
        return $this->redirectToRoute('dial_list',array('id'=>$id));
    }

    /**
     * @Route("/dial/list/{id}/rules", name="dial_rules")
     */
    public function rulesAction($id, Request $request)
    {
        $oneDial = $this->getDoctrine()
            ->getRepository('AutoDialBundle:Dial')
            ->find($id);

        $dialRules = $this->getDoctrine()->getEntityManager()
          ->createQuery('
              SELECT p FROM AutoDialBundle:Rules p
              WHERE p.dial = :dial ORDER BY p.id ASC
          ')->setParameters(array('dial'=>$id));
        if (empty($dialRules)) {
          $dialRules = array();
        }

        $file = '';

        $form = $this->createFormBuilder()
          ->add('file', FileType::class, array(
            'attr'=> array('class'=>'form-group'),
            'label'=>'Добавление звукового файла в формате *.WAV'
          ))
          ->add('Добавить файл', SubmitType::class, array('attr'=> array('class'=>'fileupload btn btn-info btn-anim')))
          ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $file = $form['file']->getData();
          if ($file->getClientMimeType() != "audio/wav") {
          //тут когда нибудь будет код, который будет кричать о несоответствии файла
          return $this->redirectToRoute('dial_rules',array('id'=>$id));
          }
          $currentName = $file->getClientOriginalName();
          $hashName = md5($currentName);
          $file->move('/var/www/html/sym/call/var/sound/user', $hashName.'_old.wav');
          $this->convertoToAsteriskFormat('/var/www/html/sym/call/var/sound/user/'.$hashName.'_old.wav', '/var/www/html/sym/call/var/sound/user/'.$hashName.'_'.$id.'.wav');
          $obSound = new UserSound;
          $obSound->setName($currentName);
          $obSound->setHash($hashName);
          $obSound->setUser('admin');
          $obSound->setAppruved('N');
          $obSound->setPath('/var/www/html/sym/call/var/sound/user/'.$hashName.'_'.$id.'.wav');
          $em = $this->getDoctrine()->getManager();
          $em->persist($obSound);
          $em->flush();
          return $this->redirectToRoute('dial_rules',array('id'=>$id));
        }

        $sounds = $this->getDoctrine()
          ->getRepository('AutoDialBundle:UserSound')
          ->findBy(array('user' => 'admin'));// сюда отдавать имя усера

        foreach ($sounds as $key => $value) {
          $sounds[$key]->setPath(str_replace('/var/www/html', '', $value->getPath()));
        }

        return $this->render('dial/rules.html.twig', array(
            'breadcrumbs'=>'Правила обзвона',
            'rules'=>$dialRules->getResult(),
            'dial'=>$oneDial,
            'sounds'=>$sounds,
            'temp'=>$file,
            'form'=>$form->createView(),
        ));
    }

    /**
     * @Route("/dial/report", name="dial_report")
     */
    public function reportAction()
    {
        return $this->render('dial/report.html.twig', array(
          'breadcrumbs'=>'Отчёт'
        ));
    }

    /**
     * @Route("/dial/{id}/calling", name="dial_calling")
     */
    public function callingAction($id, Request $request)
    {

        $oneDial = $this->getDoctrine()
          ->getRepository('AutoDialBundle:Dial')
          ->find($id);

        return $this->render('dial/calling.html.twig', array(
          'breadcrumbs'=>'Обзвон',
          'dial'=>$oneDial
        ));
    }

    /**
     * @Route("/dial/{id}/rule/{item}", name="dial_rule_edit")
     */
    public function ruleAction($id, $item, Request $request)
    {

    $dial = $request->request->get('dial');
    if (!empty($dial)) {
      die(json_encode(array('dial' => $dial)));
    }

    if ($item == 'delete') {
      $rule_id = $request->query->get('rule_id');
      if (!empty($rule_id)) {
        $obRules = $this->getDoctrine()->getRepository('AutoDialBundle:Rules')->find($rule_id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($obRules);
        $em->flush();
      }
    }

    if ($item == 'save') {
        $rule_id = $request->request->get('rule_id');
        $value = $request->request->get('value');
        $obRules = $this->getDoctrine()->getRepository('AutoDialBundle:Rules')->find($rule_id);
        $obRules->setValue($value);
        $em = $this->getDoctrine()->getManager();
        $em->persist($obRules);
        $em->flush();
        die(json_encode(array('ok' => 'ok')));
      }

      if ($item == 'addSoundToRule') {
        $obRules = new Rules;
        $obRules->setDial($id);
        $obRules->setType('sound');
        $obRules->setValue('');
        $em = $this->getDoctrine()->getManager();
        $em->persist($obRules);
        $em->flush();
      }
      if ($item == 'generateSoundFromText') {
        $obRules = new Rules;
        $obRules->setDial($id);
        $obRules->setType('text_to_speech');
        $obRules->setValue('');
        $em = $this->getDoctrine()->getManager();
        $em->persist($obRules);
        $em->flush();
      }
      if ($item == 'processAbonentClickToKey') {
        $obRules = new Rules;
        $obRules->setDial($id);
        $obRules->setType('number');
        $obRules->setValue('');
        $em = $this->getDoctrine()->getManager();
        $em->persist($obRules);
        $em->flush();
      }
      return $this->redirectToRoute('dial_rules', array('id'=>$id));
    }

    /**
     * @Route("/test", name="dial_test")
     */
    public function testAction()
    {
      $content = file_get_contents('https://www.alta.ru/kladrs/7500000000000/',true);
        return $this->render('default/empty.html.twig', array(
          // 'tmp'=>'Тестовая страница',
          'tmp'=>$content,

        ));
    }

    private function convertoToAsteriskFormat($filePath, $convetrFilePath){
        if (file_exists($convetrFilePath)){
            unlink($convetrFilePath);
        }

        //exec('sox -v 0.5 ' . $filePath . ' -t wav -b 16 -r 8000 -c 1 ' . $convetrFilePath);
        exec('sox -v 0.5 ' . $filePath . ' -t wav -2 -r 8000 -c 1 ' . $convetrFilePath);
        unlink($filePath);
    }
}
