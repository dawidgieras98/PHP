<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{

    #[Route('/show', name:'person_form')]
    public function show(Environment $twig, Request $request, EntityManagerInterface $em)
    {
        $person = new Person();

        $form = $this->createForm(PersonFormType::class, $person);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($person);
            $em->flush();

            return $this->redirectToRoute('person_form');
        }

        return new Response($twig->render('Person/show.html.twig', [
            'person_form' => $form->createView()
        ]));
    }
}