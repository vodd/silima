<?php
namespace App\Controllers;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AdminController {
    private $container;

    public function __construct($container){
        $this->container = $container;
    }

    public function home(RequestInterface $request, ResponseInterface $response){
        $query = $this->container->db->prepare('SELECT * FROM posts');
        $query->execute();
        $posts = $query->fetchAll();
        $this->container->view->render($response,'admin/home.twig',array(
            'title'=>'Administration',
            'posts'=> $posts
        ));
    }

    public function addNews(RequestInterface $request, ResponseInterface $response){
        $nameKey = $this->container->csrf->getTokenNameKey();
        $valueKey = $this->container->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        $this->container->view->render($response,'admin/addNews.twig',array(
            'title'=>'Administration',
            'nameKey'=>$nameKey,
            'valueKey'=>$valueKey,
            'name'=>$name,
            'value'=>$value
        ));
    }

    public function showNews(RequestInterface $request, ResponseInterface $response,$id){
        $nameKey = $this->container->csrf->getTokenNameKey();
        $valueKey = $this->container->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);

        $route = $request->getAttribute('route');
        $theid = $route->getArgument('id');
        $sql = "SELECT * FROM posts WHERE id=".$theid;
        $query = $this->container->db->prepare($sql);
        $query->execute();
        $post = $query->fetch();

        $this->container->view->render($response,'admin/addNews.twig',array(
            'title'=>'Administration',
            'nameKey'=>$nameKey,
            'valueKey'=>$valueKey,
            'name'=>$name,
            'value'=>$value,
            'post' => $post,
        ));
    }

    public function postNews(RequestInterface $request, ResponseInterface $response){
        $input = $request->getParsedBody();
        if($input['id']){
            $sql = 'UPDATE posts SET title=:title,des=:des WHERE id='.$input['id'];
            $query = $this->container->db->prepare($sql);
            $query->bindParam('title',$input['title']);
            $query->bindParam('des',$input['des']);
            $query->execute();
            return $response->withStatus(302)->withHeader("location",'/admin');
        }
        elseif($input['title']){
            $sql = 'INSERT INTO posts (title,des) VALUE (:title,:des)';
            $query = $this->container->db->prepare($sql);
            $query->bindParam('title',$input['title']);
            $query->bindParam('des',$input['des']);
            $query->execute();
            return $response->withStatus(302)->withHeader("location",'/admin');

        }
    }
    public function deleteNews(RequestInterface $request, ResponseInterface $response,$id){
            $route = $request->getAttribute('route');
            $theid = $route->getArgument('id');
            $sql = 'DELETE FROM posts WHERE id='.$theid;
            $query = $this->container->db->prepare($sql);
            $query->execute();
         return $response->withStatus(302)->withHeader("location",'/admin');
    }

    public function getImg(RequestInterface $request, ResponseInterface $response){
        $nameKey = $this->container->csrf->getTokenNameKey();
        $valueKey = $this->container->csrf->getTokenValueKey();
        $name = $request->getAttribute($nameKey);
        $value = $request->getAttribute($valueKey);
        $this->container->view->render($response,'admin/galerie.twig',array(
            'title'=>'Administration',
            'nameKey'=>$nameKey,
            'valueKey'=>$valueKey,
            'name'=>$name,
            'value'=>$value
        ));
    }

    public function postImg(RequestInterface $request, ResponseInterface $response){
        $input = $request->getParsedBody();
        if($input['title']){
            $path = $_FILES['img']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $unik = uniqid('img-'.date('Ymd').'-');
            $file = $unik.'.'.$ext;
            move_uploaded_file($_FILES['img']['tmp_name'],'images/gallerie/'.$file);
            $sql = 'INSERT INTO galleries (title,file) VALUE (:title,:file)';
            $query = $this->container->db->prepare($sql);
            $query->bindParam('title',$input['title']);
            $query->bindParam('file',$file);
            $query->execute();
            return $response->withStatus(302)->withHeader("location",'/admin');
        }
    }

    public function delImg(RequestInterface $request, ResponseInterface $response,$id){
        $route = $request->getAttribute('route');
        $theid = $route->getArgument('id');
        if($theid){
            $sql = 'DELETE FROM galleries WHERE id='.$theid;
            $query = $this->container->db->prepare($sql);
            $query->execute();
            return $response->withStatus(302)->withHeader("location",'/admin');
        }
    }

    public function galleries(RequestInterface $request, ResponseInterface $response){
        $query = $this->container->db->prepare('SELECT * FROM galleries  ORDER BY id DESC ');
        $query->execute();
        $posts = $query->fetchAll();
        $this->container->view->render($response,'admin/galleries.twig',array(
            'title'=>'Administration',
            'posts'=> $posts
        ));
    }
}