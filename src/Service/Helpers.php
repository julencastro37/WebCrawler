<?php
namespace App\Service;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

class Helpers
{
    // ? Terminado perfecto
    public function getEroskiProducts($search)
    {

      $urlEroski = getenv('URL_EROSKI'). $search;
      $divEroski = '.product-item';
      $tituloEroski = '.product-title.product-title-resp > a';
      $priceEroski = '.price-offer-now';
      $imgEroski = '.product-image img';
  
      $client = new Client();
      $guzzleClient = new GuzzleClient();
      $client->setClient($guzzleClient);
      $crawler = $client->request('GET', $urlEroski);

      $valores = $crawler->filter($divEroski)->each(function ($node) use ($tituloEroski,$priceEroski,$imgEroski) {
          
        /** TÍTULO */
        $node2 = $node->filter($tituloEroski)->each(function ($node2) {
          return $node2->text();
        });

        /** PRECIO */
        $node3 = $node->filter($priceEroski)->each(function ($node3) {
          return $node3->text();
        });

        /** IMAGEN */
        $node4 = $node->filter($imgEroski)->each(function ($node4) {
          return $node4->attr('src');
        });

        return ["titulo" => $node2,"precio" =>$node3, "imagen" => $node4];

      });

      return $valores;

    }
    // ? Terminado perfecto
    public function getDiaProducts($search)
    {
      $urlDia = getenv('URL_DIA'). $search.'&x=0&y=0';
      $divDia = '.prod_grid ';
      $tituloDia = '.details';
      $priceDia = '.price';
      // $imgDia = '.lazy crispImage';
      $imgDia = 'img';
  
      $client = new Client();
      $guzzleClient = new GuzzleClient();
      $client->setClient($guzzleClient);
      $crawler = $client->request('GET', $urlDia);

      $valores = $crawler->filter($divDia)->each(function ($node) use ($tituloDia,$priceDia,$imgDia) {
          
        /** TÍTULO */
        $node2 = $node->filter($tituloDia)->each(function ($node2) {
          return $node2->text();
        });

        /** PRECIO */
        $node3 = $node->filter($priceDia)->each(function ($node3) {
          return $node3->text(); // !Trae dos precios, el segundo esta vacío
        });

        /** IMAGEN */
        $node4 = $node->filter($imgDia)->each(function ($node4) {
          return $node4->attr('data-original'); // !No es el atributo src pero nos funciona
        });

        return ["titulo" => $node2,"precio" =>$node3, "imagen" => $node4];

      });

      return $valores;

    }
    // ? Terminado perfecto
    public function getAlcampoProducts($search)
    {
      $urlAlcampo = getenv('URL_ALCAMPO'). $search;
      $divAlcampo = '.noTieneFolleto';
      $tituloAlcampo = '.productName > span';
      $priceAlcampo = '.price';
      $imgAlcampo = '.thumb > img';
  
      $client = new Client();
      $guzzleClient = new GuzzleClient();
      $client->setClient($guzzleClient);
      $crawler = $client->request('GET', $urlAlcampo);

      $valores = $crawler->filter($divAlcampo)->each(function ($node) use ($tituloAlcampo,$priceAlcampo,$imgAlcampo) {
          
        /** TÍTULO */
        $node2 = $node->filter($tituloAlcampo)->each(function ($node2) {
          return $node2->text();
        });

        /** PRECIO */
        $node3 = $node->filter($priceAlcampo)->each(function ($node3) {
          return $node3->text();
        });

        /** IMAGEN */
        $node4 = $node->filter($imgAlcampo)->each(function ($node4) {
          return $node4->attr('src');
        });

        return ["titulo" => $node2,"precio" =>$node3, "imagen" => $node4];

      });

      return $valores;

    }
     //!empieza en el item 14 del array y precio duplicado
    public function getCarrefourProducts($search)
    {
      $urlCarrefour = getenv('URL_CARREFOUR').$search;
      $divCarrefour = 'article';
      $tituloCarrefour = 'h2 > a';
      $priceCarrefour = '.price';
      $imgCarrefour = '.js-gap-product-click > img';
  
      $client = new Client();
      $guzzleClient = new GuzzleClient();
      $client->setClient($guzzleClient);
      $crawler = $client->request('GET', $urlCarrefour);

      $valores = $crawler->filter($divCarrefour)->each(function ($node) use ($tituloCarrefour,$priceCarrefour,$imgCarrefour) {
          
        /** TÍTULO */
        $node2 = $node->filter($tituloCarrefour)->each(function ($node2) {
          return $node2->text();
        });

        /** PRECIO */
        $node3 = $node->filter($priceCarrefour)->each(function ($node3) {
          return $node3->text(); //!Trae dos veces el precio
        });

        /** IMAGEN */
        $node4 = $node->filter($imgCarrefour)->each(function ($node4) {
          return $node4->attr('src');
        });

        return ["titulo" => $node2,"precio" =>$node3, "imagen" => $node4];

      });

      return $valores;

    }
    //TODO Buscar los productos de eroski en los demás supermercados
    public function searchInMarkets($products){
      foreach ($products as $prod){
        // dump($prod['titulo'][0]);
        $valoresComparadosDia = $this->getDiaProducts($prod['titulo'][0]);
        $valoresComparadosAlcampo = $this->getAlcampoProducts($prod['titulo'][0]);
        $valoresComparadosCarrefour = $this->getCarrefourProducts($prod['titulo'][0]);
      }
      return['dia' => $valoresComparadosDia, 'alcampo' => $valoresComparadosAlcampo, 'carrefour' => $valoresComparadosCarrefour];
    }
    public function bestPrice($eroski, $dia, $alcampo, $carrefour){
      $bestPiceEroski = array(
        'titulo' => '',
        'imagen' => '',
        'precio' => 500,
        'market' => 'EROSKI'
      );
      $bestPriceDia = array(
        'titulo' => '',
        'imagen' => '',
        'precio' => 500,
        'market' => 'DIA'
      );
      $bestPriceAlcampo = array(
        'titulo' => '',
        'imagen' => '',
        'precio' => 500,
        'market' => 'ALCAMPO'
      );
      $bestPriceCarrefour = array(
        'titulo' => '',
        'imagen' => '',
        'precio' => 500,
        'market' => 'CARREFOUR'
      );
      //? Funciona Bien
      foreach ($eroski as $array) {
        $valor = explode(',',$array['precio'][0]);
        $numeroStr = $valor[0].'.'.$valor[1];
        $precio = number_format($numeroStr, 2);
        if(doubleval($precio) < $bestPiceEroski['precio']){
          $bestPiceEroski['titulo'] = $array['titulo'][0];
          $bestPiceEroski['imagen'] = $array['imagen'][0];
          $bestPiceEroski['precio'] = $precio;
        }
      }
      //? Funciona Bien
      foreach ($dia as $array) {
        if($array['titulo'][0] != ""){
          $num = $array['precio'][0];
          $numeroStr = explode('€', $num);
          $numeroStr = explode(',', $numeroStr[0]);
          $numeroStr = $numeroStr[0].'.'.$numeroStr[1];
          $precio = number_format(doubleval($numeroStr), 2);
          if($precio < $bestPriceDia['precio']){
            $bestPriceDia['titulo'] = $array['titulo'][0];
            $bestPriceDia['imagen'] = $array['imagen'][0];
            $bestPriceDia['precio'] = $precio;
          }
        }
      }
      //? Funciona Bien
      foreach ($alcampo as $array) {
        $num = explode('€', $array['precio'][0]);
        if($num[0] < $bestPriceAlcampo['precio']){
          $bestPriceAlcampo['titulo'] = $array['titulo'][0];
          $bestPriceAlcampo['imagen'] = $array['imagen'][0];
          $bestPriceAlcampo['precio'] = $num[0];
        }
      }
      foreach ($carrefour as $array) {
        $valor = explode(',',$array['precio'][0]);
        $numeroStr = $valor[0].'.'.$valor[1];
        $precio = number_format(doubleval($numeroStr), 2);
        if($precio < $bestPriceCarrefour['precio']){
          $bestPriceCarrefour['titulo'] = $array['titulo'][0];
          $bestPriceCarrefour['imagen'] = $array['imagen'][0];
          $bestPriceCarrefour['precio'] = $precio;
        }
      }
      return [$bestPiceEroski, $bestPriceDia, $bestPriceAlcampo, $bestPriceCarrefour];
    }
}