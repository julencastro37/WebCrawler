<?php
// src/Controller/LuckyController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// !Comentario de alert
// * Comentario de importancia
// ? comentario informativo
// TODO comentario de algo pendiente
class HelloWorldController extends AbstractController
{
   /**
     * @Route("/hello", name="hello")
     */
    public function HelloAction()
    {
        $greeting ='Hola mundo!'; 
        $busqueda = $search;

        return $this->render(
            'index.html.twig',
            array('greeting' => $greeting,
            'search' => $busqueda)
        );
    }
    /**
     * @Route("/hello/{search}", name="hello")
     */
    public function receptSearch($search){
        $realsearch = $this->get('FormController')->get($search);

        return $this->render('view', array('twig_entity' => $real_search));
    }
}