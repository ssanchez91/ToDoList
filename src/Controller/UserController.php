<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * List all users
     *
     * @Route("/users", name="user_list")
     *
     * @return Response
     */
    public function list(): Response
    {
        return $this->render(
            'user/list.html.twig',
            ['users' => $this->getDoctrine()->getRepository('App:User')->findAll()]
        );
    }

    /**
     * Create a new user
     *
     * @Route("/users/create", name="user_create")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoderInterface
     * @return Response
     */
    public function create(Request $request, UserPasswordEncoderInterface $userPasswordEncoderInterface): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit a user function
     *
     * @Route("/users/{id}/edit", name="user_edit")
     *
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function edit(User $user, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
