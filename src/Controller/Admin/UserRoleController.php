<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/{user}/role", name="admin_user_role_")
 */
class UserRoleController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(User $user): Response
    {
        return $this->render('admin/user_role/index.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserRoleType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_role_index', ['user' => $user->getId()]);
        }

        return $this->renderForm('admin/user_role/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
