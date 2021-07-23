<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface as EMI;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{

    /**
     * 
     *@Route("/index", name="index")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('login');
    }

    /**
     * 
     *@Route("/register", name="register")
     */
    public function register(User $user = null, Request $req, EMI $em, UserPasswordHasherInterface $hash): Response
    {
        if (!$user) {
            $creationMode = true;
            $user = new User();
        }

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $hashedPassword = $hash->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login');
        }


        return $this->render('auth/register.html.twig', [
            'registrationForm' => $form->createView(),
            'creationMode' => $creationMode,


        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        // all functionality handled by security yaml
        return $this->render('auth/login.html.twig', []);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        // all functionality handled by security yaml
    }
}
