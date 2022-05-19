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
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user", name="admin_user_")
 */
class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $paginator->paginate(
            $userRepository->getQueryBuilder(),
            $request->query->getInt('page', 1),
            30
        );

        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->generatePassword());

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_show', ['id' => $user->getId()]);
        }

        return $this->renderForm('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/enable", name="enable", methods={"GET"})
     */
    public function enable(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user->setEnabled(true);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Le membre a été activé');

        if (null === $request->headers->get('referer')) {
            return $this->redirectToRoute('admin_user_show', ['id' => $user->getId()]);
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/{id}/disable", name="disable", methods={"GET"})
     */
    public function disable(User $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $error = 0;

        if ($user->getFuturJobs()->count() > 0) {
            $this->addFlash('error', 'Le membre a encore des postes à venir');
            ++$error;
        }

        if (null !== $user->getCurrentCommitmentContract()) {
            $this->addFlash('error', 'Le membre a encore au moins un engagement d\'ouvert');
            ++$error;
        }

        if (0 === $error) {
            $user->setEnabled(false);
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Le membre a été desactivé');
        }

        if (null === $request->headers->get('referer')) {
            return $this->redirectToRoute('admin_user_show', ['id' => $user->getId()]);
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_index', [], Response::HTTP_SEE_OTHER);
    }

    private function generatePassword()
    {
        $length = 15;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#!?-&@';
        $charactersLength = mb_strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * @Route("/{id}/new-calendar-token", name="new_calendar_token")
     */
    public function generateNewCalendarToken(User $user): Response
    {
        $user->setCalendarToken(hash('sha256', random_bytes(255)));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->addFlash('success', 'Le token a été regénéré');

        return $this->redirectToRoute('admin_user_show', ['id' => $user->getId()]);
    }
}
