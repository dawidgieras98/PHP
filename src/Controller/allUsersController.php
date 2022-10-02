<?php

namespace App\Controller;

use App\Form\PersonFormType;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class allUsersController extends AbstractController
{
        private $personRepository;
    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    #[Route('/allUsers', name: 'users')]
    public function showAllUsers(): Response
    {
        $users = $this->personRepository->findAll();
        return $this->render('Person/allUsers.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/allUsers/edit/{id}', name:'edit_user')]
    public function edit($id, Request $request, EntityManagerInterface $em): Response
    {
       $person = $this->personRepository->find($id);
       $form = $this->createForm(PersonFormType::class, $person);

       $form->handleRequest($request);
        
       if($form->isSubmitted() && $form->isValid()){
         dd('ok');
       } else {
            $person->setFirstName($form->get('firstName')->getData());
            $person->setLastName($form->get('lastName')->getData());
            $person->setEmail($form->get('email')->getData());
            $person->setCompany($form->get('company')->getData());
            $person->setPosition($form->get('position')->getData());

            $this->$em->flush();
            return $this->redirectToRoute('users');
       }

       return $this->render('Person/editUser.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
       ]
       );
       }
}