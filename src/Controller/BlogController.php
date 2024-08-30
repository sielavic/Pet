<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class BlogController extends AbstractController
{

    public function index(): Response
    {
        $userFirstName = 'Юзер';
        $userNotifications = ['какое то уведомление', '...'];

        // путь шаблона - это относительн путь файла из `templates/`
        return $this->render('blog/index.html.twig', [
            // этот массив определяет переменные, переданные шаблону, где ключ - это
            // имя переменной, а значение - значение переменной
            // (Twig рекомендует использование имен переменных snake_case : 'foo_bar' вместо 'fooBar')
            'user_first_name' => $userFirstName,
            'notifications' => $userNotifications,
        ]);
    }
}