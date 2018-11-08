<?php
namespace App\Controller;

use App\Entity\Search;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    /**
    * @Route("/search", name="search")
    */
    public function new(Request $request)
    {
        // creates a task and gives it some dummy data for this example
        $search = new Search();
        $search->setSearch('Buscar productos');

        $form = $this->createFormBuilder($search)
            ->add('search', TextType::class, array('required' => true, 'label' => 'Productos para buscar'))
            ->add('save', SubmitType::class, array('label' => 'Buscar'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
          $search = $form->getData();

          return $this->redirectToRoute('crawler', array('search' => $search->search));     
        }
        return $this->render('searchForm.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}