<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Symfony\Component\Security\Core\User\UserInterface as UserInterfase;
use Doctrine\Common\Collections\Criteria;

use Doctrine\Persistence\ManagerRegistry;


class TaskController extends AbstractController
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
            if ($project != null) {
                if ($task->getProject()->getId() == $project->getId()) {
                    $count += 1;
                }
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


    public function creation(Request $request, UserInterfase $user, ManagerRegistry $doctrine)
    {

        $em = $doctrine->getManager();

        $repository = $em->getRepository(User::class);
        $repositoryTask = $em->getRepository(Task::class);
        /** @var User $user */
        $projectId = $user->getProject()->getId();

        $queryBuilder = $repository->createQueryBuilder('u');
        $queryBuilderTask = $repositoryTask->createQueryBuilder('t');

        $queryTask = $em->createQuery(
            'SELECT t
            FROM App\Entity\Task t
            WHERE t.projectId = :project_id
            ORDER BY t.id DESC'
        )->setParameter('project_id', $projectId);
        $tasks = $queryTask->getResult();

        $query = $em->createQuery(
            'SELECT u
            FROM App\Entity\User u
            WHERE u.projectId = :project_id
            ORDER BY u.surname DESC'
        )->setParameter('project_id', $projectId);
        $users = $query->getResult();


        $task = new Task();
        $form = $this->createForm(TaskType::class, $task, [
            'users' => $users,
            'tasks' => $tasks
        ]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $task->setCreatedAt(new \Datetime('now'));
            /** @var User $user */
            $task->setUser($user);
            $assignedUser = $form->get('assignedUser')->getData();
            $assignedUser = $em->getRepository(User::class)->find($assignedUser);
            $project = $user->getProject();
            $task->setProject($project);

            $task->setAssignedUser($assignedUser);
            $taskFile = $request->files->get('task');


            $em = $doctrine->getManager();
            $em->persist($task);
            $em->flush();

            $this->addFlash('message', 'Успешно создали задачу');

            return $this->redirectToRoute('tasks');
        }


        return $this->render('task/creation.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function detail($id, ManagerRegistry $doctrine)
    {


        $taskRepo = $doctrine->getRepository(Task::Class);

        /** @var Task $task */
        $task = $taskRepo->find($id);
        $parent = $task->getParentId();
        $childrens = $task->getChildren();
        $fileImg = null;
        $filePdf = null;
        $file = $task->getFile();
        $fileName = $this->getExtension($file);


        if ($fileName == 'jpg') {
            $fileImg = true;
        }
        if ($fileName == 'pdf') {
            $filePdf = true;
        }
        if (!$task) {
            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/detail.html.twig', [
            'task' => $task,
            'fileImg' => $fileImg,
            'filePdf' => $filePdf,
            'parent' => $parent,
            'childrens' => $childrens
        ]);
    }


    // Muestra mis Tareas
    public function myTasks(UserInterfase $user, ManagerRegistry $doctrine)
    {


        $tasks = $user->getAssignedTasks();
        /** @var User $user */
        $project = $user->getProject();
        $criteria = Criteria::create()->orderBy(['id' => 'DESC']);
        $sortedTasks = $tasks->matching($criteria);
        $count = 0;
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

        return $this->render('task/my-tasks.html.twig', [
            'user' => $user,
            'tasks' => $sortedTasks,
            'project' => $project,
            'count_task' => $count
        ]);
    }


    public function edit($id, ManagerRegistry $doctrine, Request $request, UserInterfase $user)
    {

        $em = $doctrine->getManager();

        $task = $doctrine->getRepository(Task::class)->find($id);
        $taskRepo = $doctrine->getRepository(Task::Class);
        $userRepo = $doctrine->getRepository(User::Class);
        /** @var User $user */
        $projectId = $user->getProject()->getId();
        $queryBuilder = $userRepo->createQueryBuilder('u');
        $queryBuilderTask = $taskRepo->createQueryBuilder('t');


        $queryTask = $em->createQuery(
            'SELECT t
            FROM App\Entity\Task t
            WHERE t.projectId = :project_id
            ORDER BY t.id DESC'
        )->setParameter('project_id', $projectId);
        $tasks = $queryTask->getResult();

        $query = $em->createQuery(
            'SELECT u
            FROM App\Entity\User u
            WHERE u.projectId = :project_id
            OR  u.email = :admin
            ORDER BY u.surname DESC'
        )->setParameter('project_id', $projectId)
            ->setParameter('admin', 'admin@mail.ru');
        $users = $query->getResult();

        $form = $this->createForm(TaskType::class, $task, [
            'users' => $users,
            'tasks' => $tasks
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $assignedUser = $form->get('assignedUser')->getData();

            $assignedUser = $em->getRepository(User::class)->find($assignedUser);
            $task->setAssignedUser($assignedUser);
            $task = $form->getData();

            $em = $doctrine->getManager();
            $em->persist($task);
            $em->flush();

            $this->addFlash('message', 'Задача успешно отредактирована');

            return $this->redirectToRoute('tasks');

        }


        return $this->render('task/creation.html.twig', [
            'edit' => true,
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }


    public function delete($id, ManagerRegistry $doctrine)
    {

        $task = $doctrine->getRepository(Task::class)->find($id);

        if (!$task) {
            throw $this->createNotFoundException('Запись не найдена');
        }

        $em = $doctrine->getManager();
        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('tasks');
    }

    function getExtension($filename)
    {
        $array = explode(".", $filename);
        return end($array);
    }

}