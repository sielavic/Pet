<?php
use App\Controller\BlogController;
use App\Controller\DefaultController;
use App\Controller\HoursController;
use App\Controller\LoginController;
use App\Controller\ProjectController;
use App\Controller\RegistrationController;
use App\Controller\TaskController;
use App\Controller\UserController;
use App\Security\ApiKeyAuthenticator;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('task_detail', '/tarea/{id}')
        ->controller([TaskController::class, 'detail'])->methods(['GET', 'POST']);
    $routes->add('creat_task', '/crear-tarea')
        ->controller([TaskController::class, 'creation'])
        ->methods(['GET', 'POST']);


    $routes->add('tasks', '/')
        ->controller([TaskController::class, 'index'])
        ->methods(['GET', 'POST']);
    $routes->add('task_edit', '/editar-tarea/{id}')
        ->controller([TaskController::class, 'edit'])
        ->methods(['GET', 'POST']);
    $routes->add('app_logout', '/logout')
        ->controller([LoginController::class, 'logout'])
        ->methods(['GET', 'POST']);
    $routes->add('task_delete', '/eliminar-tarea/{id}')
        ->controller([TaskController::class, 'delete'])
        ->methods(['GET', 'POST']);
    $routes->add('my_tasks', '/my-tasks')
        ->controller([TaskController::class, 'myTasks'])
        ->methods(['GET', 'POST']);
    $routes->add('app_register', 'user/register')
        ->controller([RegistrationController::class, 'register'])
        ->methods(['GET', 'POST']);
    $routes->add('app_login', '/login')
        ->controller([LoginController::class, 'login']);
    $routes->add('app_edit', '/edit/{id}')
        ->controller([RegistrationController::class, 'edit']);
    $routes->add('app_add_man', '/add')
        ->controller([RegistrationController::class, 'addMan']);
    $routes->add('detail_project', '/project/{id}')
        ->controller([ProjectController::class, 'index']);

    $routes->add('edit_project', '/edit-project/{id}')
        ->controller([ProjectController::class, 'edit']);

    $routes->add('delete_man_project', '/delete-man-project/{id}')
        ->controller([ProjectController::class, 'deleteMan']);


    $routes->add('creat_hours', '/creat-hours/{id}')
        ->controller([HoursController::class, 'creat']);
    $routes->add('detail_hours', '/detail-hours/{id}')
        ->controller([HoursController::class, 'detail']);
    $routes->add('edit_hours', '/edit-hours/{id}')
        ->controller([HoursController::class, 'edit']);
    $routes->add('delete_hours', '/delete-hours/{id}')
        ->controller([HoursController::class, 'delete']);

    $routes->add('search_man', '/search-man/{id}')
        ->controller([ProjectController::class, 'searchMan']);

    $routes->add('search_men', '/search_men/{id}')
        ->controller([ProjectController::class, 'searchMen']);

};