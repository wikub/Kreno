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

use App\Entity\CommitmentLog;
use App\Entity\User;
use App\Form\Admin\CommitmentLogType;
use App\Repository\CommitmentLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/{user}/commitmentlog", name="admin_commitment_log_")
 */
class CommitmentLogController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CommitmentLogRepository $commitmentLogRepository, User $user): Response
    {
        $commitmentLogs = $commitmentLogRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);

        $sumNbTimeslot = $commitmentLogRepository->getSumNbTimeslot($user);
        $sumNbHour = $commitmentLogRepository->getSumNbHour($user);

        return $this->render('admin/commitment_log/index.html.twig', [
            'commitment_logs' => $commitmentLogs,
            'user' => $user,
            'sumNbTimeslot' => $sumNbTimeslot,
            'sumNbHour' => $sumNbHour,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $commitmentLog = new CommitmentLog();
        $commitmentLog->setUser($user);
        $form = $this->createForm(CommitmentLogType::class, $commitmentLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commitmentLog);
            $entityManager->flush();

            return $this->redirectToRoute('admin_commitment_log_index', ['user' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/commitment_log/new.html.twig', [
            'commitment_log' => $commitmentLog,
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(CommitmentLog $commitmentLog, User $user): Response
    {
        return $this->render('admin/commitment_log/show.html.twig', [
            'commitment_log' => $commitmentLog,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CommitmentLog $commitmentLog, EntityManagerInterface $entityManager, User $user): Response
    {
        $form = $this->createForm(CommitmentLogType::class, $commitmentLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_commitment_log_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/commitment_log/edit.html.twig', [
            'commitment_log' => $commitmentLog,
            'form' => $form,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, CommitmentLog $commitmentLog, EntityManagerInterface $entityManager, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commitmentLog->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commitmentLog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_commitment_log_index', [], Response::HTTP_SEE_OTHER);
    }
}
