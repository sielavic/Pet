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

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface as UserInterfase;
use Doctrine\Common\Collections\Criteria;

use Doctrine\Persistence\ManagerRegistry;


class ProjectController extends AbstractController
{


    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index($id, Request $request, UserInterfase $user, ManagerRegistry $doctrine): Response
    {

        $projectRepo = $doctrine->getRepository(Project::Class);

        /** @var Project $project */
        $project = $projectRepo->find($id);
        $description = $project->getDescription();
        $users = $project->getUsers();
        $tasks = $project->getTasks();


        return $this->render('project/index.html.twig', [
            'project' => $project,
            'description' => $description,
            'users' => $users,
        ]);
    }

    public function searchMan($id, Request $request, UserInterfase $user, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $projectRepo = $doctrine->getRepository(Project::Class);

        /** @var Project $project */
        $project = $projectRepo->find($id);
        $description = $project->getDescription();

        $repository = $em->getRepository(User::class);
        $queryBuilder = $repository->createQueryBuilder('u');

        $projectId = null;
        $query = $em->createQuery(
            'SELECT u
    FROM App\Entity\User u
    WHERE ( u.projectId IS NULL) 
    ORDER BY u.surname DESC'
        );
        $users = $query->getResult();


        return $this->render('project/add-man.twig', [
            'project' => $project,
            'description' => $description,
            'users' => $users,
        ]);
    }

    public function searchMen($id, Request $request, ManagerRegistry $doctrine)
    {
        $query = $request->query->get('query');


        $projectRepo = $doctrine->getRepository(Project::Class);

        /** @var Project $project */
        $project = $projectRepo->find($id);
        $description = $project->getDescription();

        if ($query) {
            $results = $this->userRepository->searchUsers($query);
        } else {
            $results = [];
        }

        return $this->render('project/search_results.html.twig', [
            'project' => $project,
            'description' => $description,
            'results' => $results,
            'query' => $query,
        ]);
    }


    public function creat(Request $request, UserInterfase $user, ManagerRegistry $doctrine)
    {


        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $project->setCreatedAt(new \Datetime('now'));
            $project->setUpdatedAt(new \Datetime('now'));

            $em = $doctrine->getManager();
            $em->persist($project);
            $em->flush();

            $this->addFlash('message', 'Успешно создали проект');


            $this->render('task/index.html.twig', [
                'project' => $project
            ]);
        }

        return $this->render('project/creation.html.twig', [
            'form_project' => $form->createView()
        ]);
    }


    public function detail($id, ManagerRegistry $doctrine)
    {


        $taskRepo = $doctrine->getRepository(Task::Class);

        /** @var Task $task */
        $task = $taskRepo->find($id);
        $parent = $task->getParentId();
        $childrens = $task->getChildren();
        $fileImg = false;
        $filePdf = false;
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


    public function myTasks(UserInterfase $user, ManagerRegistry $doctrine)
    {

        $tasks = $user->getAssignedTasks();


        $criteria = Criteria::create()->orderBy(['id' => 'DESC']);


        $sortedTasks = $tasks->matching($criteria);

        /** @var Task $task */
        foreach ($tasks as $task) {
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
            'tasks' => $sortedTasks
        ]);
    }


    // Edita la Tarea
    public function edit($id, ManagerRegistry $doctrine, Request $request, UserInterfase $user)
    {


        $project = $doctrine->getRepository(Project::class)->find($id);


        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project->setUpdatedAt(new \Datetime('now'));
            $em = $doctrine->getManager();
            $em->persist($project);
            $em->flush();
            $this->addFlash('message', 'Успешно отредактировали проект');
            $em->flush();

            return $this->redirectToRoute('tasks');
        }

        return $this->render('project/creation.html.twig', [
            'form_project' => $form->createView()
        ]);
    }


    public function deleteMan($id, ManagerRegistry $doctrine)
    {


        $user = $doctrine->getRepository(User::class)->find($id);


        if (!$user) {
            throw $this->createNotFoundException('Пользователь не найден');
        }

        $em = $doctrine->getManager();
        $user->setProject(null);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('tasks');
    }

    public function delete($id, ManagerRegistry $doctrine)
    {


        $task = $doctrine->getRepository(Task::class)->find($id);


        if (!$task) {
            throw $this->createNotFoundException('El registro no fue encontrado');
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