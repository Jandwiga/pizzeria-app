<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile/{id}", name="profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="_main")
     * @return Response
     */
    public function index(User $user): Response
    {
//        dump($this->getUser());

        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/edit", name="_edit")
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function update(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() &&
            $form->isValid() &&
            $userPasswordHasher->isPasswordValid($user, $form->get('plainPassword')->getData())) {
                 $entityManager->flush();
                // do anything else you need here, like send an email
                return $this->redirectToRoute('profile_main', ['id' => $user->getId()]);
        }
        return $this->render("profile/edit.html.twig", [
            'editForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete", name="_delete")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(User $user, Request $request, UserRepository $repository): Response
    {
        $repository->remove($user, true);
        $request->getSession()->invalidate();
        $this->container->get('security.token_storage')->setToken(null);
        return $this->redirectToRoute('home');
    }

}
