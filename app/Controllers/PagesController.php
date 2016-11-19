<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

Class PagesController {
    private $container;

    public function __construct($container){
        $this->container = $container;
    }

    public function home(RequestInterface $request, ResponseInterface $response){
//        $query = $this->container->db->prepare('SELECT * FROM posts');
//        $query->execute();
//        $posts = $query->fetchAll();
        $nameKey = $this->container->csrf->getTokenNameKey();
        $valueKey = $this->container->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        $this->container->view->render($response,'pages/home.twig',array(
            'title'=>'Neptune',
            'nameKey'=>$nameKey,
            'valueKey'=>$valueKey,
            'name'=>$name,
            'value'=>$value
        ));
    }

//    public function getNew(RequestInterface $request, ResponseInterface $response){
//        $this->container->view->render($response,'pages/new.twig');
//    }
//
//    public function postNew(RequestInterface $request, ResponseInterface $response){
//        $input = $request->getParsedBody();
//        $sql = 'INSERT INTO posts (title,des) VALUE (:title,:des)';
//        $query = $this->container->db->prepare($sql);
//        $query->bindParam('title',$input['title']);
//        $query->bindParam('des',$input['des']);
//        $query->execute();
//    }
    public function getContact(RequestInterface $request, ResponseInterface $response){
        // CSRF token name and value

        $this->container->view->render($response,'pages/home.twig');
    }
    public function getRes(RequestInterface $request, ResponseInterface $response){
        $nameKey = $this->container->csrf->getTokenNameKey();
        $valueKey = $this->container->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        $this->container->view->render($response,'pages/contact.twig',array(
            'title'=>'Neptune',
            'nameKey'=>$nameKey,
            'valueKey'=>$valueKey,
            'name'=>$name,
            'value'=>$value
        ));
    }

    public function postRes(RequestInterface $request, ResponseInterface $response){
        $message = \Swift_Message::newInstance('Formulaire de reservations site web')
            //->setFrom([$request->getParam('email')=>$request->getParam('name')])
            ->setFrom(array('contact@respawn-agency.com' => 'Contact par site'))
            ->setTo('vodwow@gmail.com')
            ->setBody("Nom & prenom : {$request->getParam('name')} {$request->getParam('lastname')}
             Télephone : {$request->getParam('telephone')}
             Email : {$request->getParam('email')}
             type : {$request->getParam('type')}
             event : {$request->getParam('event')}
             Qst : {$request->getParam('qst')}
           ");
        $this->container->mailer->send($message);
        return $response->withStatus(302)->withHeader('location','/');
    }
    public function postContact(RequestInterface $request, ResponseInterface $response){
        $message = \Swift_Message::newInstance('Formulaire de contact site web')
            //->setFrom([$request->getParam('email')=>$request->getParam('name')])
            ->setFrom(array('contact@respawn-agency.com' => 'Contact par site'))
           ->setTo('vodwow@gmail.com')
           ->setBody("Nom & prenom : {$request->getParam('name')}            
            {$request->getParam('message')}
           ");
        $this->container->mailer->send($message);
        return $response->withStatus(302)->withHeader('location','/');
    }
}