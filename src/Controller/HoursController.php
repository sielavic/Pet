<?php
/*
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
*/

namespace App\Controller;

use App\Entity\Hours;
use App\Form\HoursType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface as UserInterfase;


use Doctrine\Persistence\ManagerRegistry;


class HoursController extends AbstractController
{

    public function index(ManagerRegistry $doctrine, UserInterfase $user,): Response
    {
        $taskRepo = $doctrine->getRepository(Task::Class);
        /** @var User $user */
        $project = $user->getProject();
        $count = 0;
        $tasks = $taskRepo->findBy([], ['id' => 'desc']);
        /** @var Task $task */
        foreach ($tasks as $task) {
            if ($task->getProject()->getId() == $project->getId()) {
                $count += 1;
            }
            $assigneduserid = $task->getAssignedUser();
            if ($assigneduserid != null && $assigneduserid->getId() != 0) {
                if ($assigneduserid->getSurname() && $assigneduserid->getName()) {
                    $surname = $assigneduserid->getSurname();
                    $name = $assigneduserid->getName();
                    $fio = $surname . ' ' . $name;
                    if (!empty($fio)) {
                        $task->setAssignedUserName($fio);
                    }
                }
            } else {
                $task->setAssignedUserName(null);
            }
        }


        return $this->render('task/index.html.twig', [
            'user' => $user,
            'tasks' => $tasks,
            'project' => $project,
            'count_task' => $count
        ]);
    }


    public function creat($id, Request $request, UserInterfase $user, ManagerRegistry $doctrine)
    {

        $em = $doctrine->getManager();
        $taskRepo = $em->getRepository(Task::class);
        /** @var Task $task */
        $task = $taskRepo->find($id);

        $hours = new Hours();
        $hours->setTaskId($task->getId());
        $hours->setUserId($user->getId());
        $hours->setUser($user);
        $hours->setTask($task);
        $hours->setCreatedAt(new \Datetime('now'));
        $form = $this->createForm(HoursType::class, $hours);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($hours);
            $em->flush();

            $this->addFlash('message', 'Успешно добавили трудозатраты');

            return $this->redirectToRoute('tasks');
        }


        return $this->render('hours/hours-add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    public function detail($id, ManagerRegistry $doctrine)
    {


        $taskRepo = $doctrine->getRepository(Task::Class);

        /** @var Task $task */
        $task = $taskRepo->find($id);
        if (!$task) {
            return $this->redirectToRoute('tasks');
        }
        $hours = $task->getLaborCosts();


        return $this->render('hours/hours-detail.html.twig', [
            'task' => $task,
            'hours' => $hours
        ]);
    }


    public function edit($id, ManagerRegistry $doctrine, Request $request, UserInterfase $user)
    {

        $hours = $doctrine->getRepository(Hours::class)->find($id);
        $task = $hours->getTask();


        $form = $this->createForm(HoursType::class, $hours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($hours);
            $em->flush();
            $this->addFlash('message', 'Трудозатраты успешно отредактированы');

            return $this->redirectToRoute('detail_hours', ['id' => $task->getId()]);
        }


        return $this->render('hours/hours-edit.html.twig', [
            'edit' => true,
            'form' => $form->createView(),
        ]);
    }


    public function delete($id, ManagerRegistry $doctrine)
    {


        $hours = $doctrine->getRepository(Hours::class)->find($id);
        $task = $hours->getTask();

        if (!$hours) {
            throw $this->createNotFoundException('Запись не найдена');
        }

        $em = $doctrine->getManager();
        $em->remove($hours);
        $em->flush();

        return $this->redirectToRoute('detail_hours', ['id' => $task->getId()]);
    }

    function getExtension($filename)
    {
        $array = explode(".", $filename);
        return end($array);
    }

}