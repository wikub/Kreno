<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/me", name="myaccount_")
 */
class MyAccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("", name="index")
     */
    public function index(): Response
    {
        return $this->render('my_account/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @Route("/change-password", name="change_password")
     */
    public function changePassword(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if old password is valid
            if (!$passwordHasher->isPasswordValid($user, $form->get('oldPassword')->getData())) {
                $this->addFlash('danger', 'L\'ancien mot de passe n\'est pas correct');

                return $this->render('my_account/change_password.html.twig', [
                    'changePasswordForm' => $form->createView(),
                ]);
            }

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le mot de passe a été mis à jour');

            return $this->redirectToRoute('myaccount_index');
        }

        return $this->render('my_account/change_password.html.twig', [
            'changePasswordForm' => $form->createView(),
        ]);
    }
}
