<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface as EMI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class TodoController extends AbstractController
{
    /**
     *@Route("/todo", name="todo")
     */
    public function index(TodoRepository $repo): Response
    {
        $todos = $repo->findAll();


        return $this->render('todo/index.html.twig', [
            'todos' => $todos,
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
        if ($user == $todo->getUser()) {
            $em->remove($todo);
            $em->flush();
        }
        return $this->redirect('/todo');
    }

    /**
     *@Route("/todo/show/{id}", name="show")
     */
    public function show(Todo $todo): Response
    {

        return $this->render('todo/show.html.twig', [
            'todo' => $todo
        ]);
    }
}
