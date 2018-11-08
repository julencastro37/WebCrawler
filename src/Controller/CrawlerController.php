<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Helpers;

class CrawlerController extends AbstractController{

  /**
    * @Route("/crawler/{search}", name="crawler")
    */
  public function searchPage ($search, Helpers $helpers){
    
    $busqueda = $search;
    $valoresEroski = $helpers->getEroskiProducts($search);
    $valoresDia = $helpers->getDiaProducts($search);
    $valoresAlcampo = $helpers->getAlcampoProducts($search);
    $valoresCarrefour = $helpers->getCarrefourProducts($search);
    //TODO Ahora con los productos devueltos de eroski buscaremos exactamente el producto en los demás supermercados
    //? Sería interesante poner opción a que se haga el método o no por si se buscan productos generales, no es lo mismo buscar una marca que buscar patatas en general.
    //! DIA y Carrefour funcionan con busqueda por mayor coincidencia, sin embargo alcampo funciona con busqueda exacta

    // $restOfMarkets = $helpers->searchInMarkets($valoresEroski);
    // dump($restOfMarkets); //!ralentiza mucho la página y no es lo que se esperaba (búsquedas)
    $bestProduct = $helpers->bestPrice($valoresEroski, $valoresDia, $valoresAlcampo, $valoresCarrefour);
    return $this->render('result.html.twig', array('eroski' => $valoresEroski,'dia' =>$valoresDia, 'alcampo'=>$valoresAlcampo, 'carrefour'=>$valoresCarrefour, 'busqueda'=>$search, 'bestPrice'=>$bestProduct)); //,'restMarkets' => $restOfMarkets));

  }
}
