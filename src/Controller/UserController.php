<?php

namespace App\Controller;

use App\Form\ProfileEditFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'main.profile')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    #[Route('/user/edit', name: 'main.profile.edit')]
    public function edit(Request $request, ManagerRegistry $doctrine) : Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileEditFormType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('main.profile');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView()]);

    }

}
