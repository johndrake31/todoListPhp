<?php

namespace App\Controller;

use App\Entity\Checked;
use App\Entity\Todo;
use App\Entity\User;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface as EMI;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Length;

/**
 *@Route("/{_locale}")
 */
class TodoController extends AbstractController
{
    /**
     *@Route("/todo", name="todo")
     *@Route("/todo/orderby/{orderByCreation}", name="orderBy_todo")
     */
    public function index(UserInterface $user, TodoRepository $repo, $orderByCreation = null, PaginatorInterface $paginator, Request $request): Response
    {

        $todos = $user->getTodos();
        $todoLength = count($todos);
        $checks = count($user->getCheckeds());


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
            'checks' => $checks,
            'todoLength' => $todoLength
        ]);
    }


    /**
     *@Route("/todo/add", name="add_todo")
     *@Route("/todo/edit/{id}", name="edit_todo")
     */
    public function add(Todo $todo = null, Request $req, EMI $emi, UserInterface $user): Response
    {
        $createMode = null;
        if (!$todo) {
            $todo = new Todo();
            $createMode = true;
        }

        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($createMode) {
                $todo->setCreatedAt(new \DateTime());
                $todo->setUser($user);
            }

            if (!$createMode) {
                if ($user != $todo->getUser()) {
                    return $this->redirectToRoute('add_todo');
                }
            }
            $todo = $form->getData();
            $emi->persist($todo);
            $emi->flush();

            return $this->redirectToRoute('todo');
        }

        return $this->render('todo/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * 
     * @Route("/todo/delete/{id}", name="delete_todo")
     */
    public function delete(Todo $todo, EMI $em, UserInterface $user): Response
    {
        $isAdmin = in_array("ROLE_ADMIN", $user->getRoles());

        if ($user == $todo->getUser() || $isAdmin) {
            $user2 = $todo->getUser();
            $id = $user2->getId();

            $em->remove($todo);
            $em->flush();
        }
        if ($isAdmin) {
            return $this->redirect('/admin/todos/' . $id);
        }
        return $this->redirectToRoute('todo');
    }

    /**
     * 
     * @Route("/todo/checked/{id}", name="checked")
     */
    public function checked(Todo $todo, EMI $em): Response
    {
        $checked = $todo->getChecked();
        $user = $todo->getUser();


        if (!$checked) {
            $checked = new Checked();
            $checked->setUser($todo->getUser());
            $checked->setTodo($todo);

            $em->persist($checked);
            $message = "checked";
        } else {
            $em->remove($checked);
            $message = "unchecked";
        }
        $em->flush();

        $userChecks = count($user->getCheckeds());
        $data = [
            'message' => $message,
            'nombreDeChecks' => $userChecks
            ];
        // return $this->redirectToRoute('todo');
        return $this->json($data, 200);
    }
}
