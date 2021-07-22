<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TodoRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface as EMI;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * 
     * @Route("/admin", name="admin")
     */
    public function index(UserRepository $userRepo): Response
    {
        $users = $userRepo->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * 
     * @Route("/admin/delete/{id}", name="delete_user")
     */
    public function remove(User $user, EMI $emi): Response
    {
        $emi->remove($user);
        $emi->flush();

        return $this->redirectToRoute('admin');
    }

    /**
     * 
     * @Route("/admin/todos/{id}", name="show_user_todos")
     */
    public function showToDo(User $user, TodoRepository $repo, $orderByCreation = null, PaginatorInterface $paginator, Request $request): Response
    {
        $todos = $user->getTodos();


        switch ($orderByCreation) {
            case "oldiest":
                $todos = $repo->findAllTodosSortByOldest($user);;
                break;
            case "newiest":
                $todos = $repo->findAllTodosSortByNewest($user);;
                break;
            case "dueNewiest":
                $todos = $repo->findAllByUserSortByDuedateNew($user);
                break;
            case "dueOldiest":
                $todos = $repo->findAllTodosSortByDueByOld($user);
                break;
        }

        $pagination = $paginator->paginate(
            $todos, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );
        return $this->render('todo/index.html.twig', [
            'todos' => $pagination,
        ]);
    }
}
