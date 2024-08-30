<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


use App\Entity\User;

class UserController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function register(ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $encoder): Response
    {


        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);


        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRole('ROLE_USER');
            $user->setCreatedAt(new \DateTime('now'));
            $date_now = (new \DateTime())->format('Y-m-d H:i:s');
            //var_dump($date_now);
            //die;
            $user->setCreatedAt($date_now);

            $encoded = $encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($encoded);


            $em = $doctrine->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('message', 'Вы уже зарегистрированы');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView()
        ]);
    }


}