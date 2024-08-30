<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Form\AddFormType;
use App\Form\EditFormType;
use App\Form\RegistrationFormType;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface as UserInterfase;
use Symfony\Component\Validator\Validation;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{

    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $project = new Project();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $password = $form->get('plainPassword')->getData();

        $errors = $form->getErrors();
        foreach ($errors as $error) {
            if ($error->getMessage() == "This value should not be blank.") {
                $form->clearErrors();
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setRole('ROLE_USER');
            $user->setCreatedAt(new \DateTime('now'));
            $date_now = new \DateTime();

            $user->setCreatedAt($date_now);
            $project->setProjectName($form->get('projectName')->getData());
            $project->setDescription($form->get('projectDescription')->getData());
            $project->setCreatedAt($date_now);
            $project->setUpdatedAt($date_now);
            $user->setProject($project);

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('tasks');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function edit($id, ManagerRegistry $doctrine, Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {

        $em = $doctrine->getManager();
        $user = $doctrine->getRepository(User::class)->find($id);
        $form = $this->createForm(EditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setRole('ROLE_USER');

            if (!is_null($form->get('password')->getData())) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('tasks');
        }

        return $this->render('registration/edit.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }

    public function addMan(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserInterfase $currentUser)
    {
        $user = new User();
        $form = $this->createForm(AddFormType::class, $user);
        $form->handleRequest($request);
        $password = $form->get('plainPassword')->getData();

        $errors = $form->getErrors();
        foreach ($errors as $error) {
            if ($error->getMessage() == "This value should not be blank.") {
                $form->clearErrors();
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setRole('ROLE_USER');
            $user->setCreatedAt(new \DateTime('now'));
            $date_now = new \DateTime();
            /** @var User $currentUser */
            $currentProject = $currentUser->getProject();
            $user->setProject($currentProject);
            $user->setCreatedAt($date_now);

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('tasks');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'registerMan' => 1,
            'project' => $user->getProject()
        ]);
    }


}
