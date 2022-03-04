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

use App\Entity\Job;
use App\Entity\User;
use App\Form\Admin\UserJobType;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/{user}/job", name="admin_user_job_")
 */
class UserJobController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(JobRepository $jobRepository, User $user): Response
    {
        return $this->render('admin/user_job/index.html.twig', [
            'jobs' => $jobRepository->findAllOrderByTimeslotStart($user),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        $job = new Job();
        $form = $this->createForm(UserJobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($job);
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user_job/new.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_job_show", methods={"GET"})
     */
    public function show(Job $job, User $user): Response
    {
        return $this->render('admin/user_job/show.html.twig', [
            'job' => $job,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_user_job_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Job $job, EntityManagerInterface $entityManager, User $user): Response
    {
        $form = $this->createForm(UserJobType::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_user_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user_job/edit.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_user_job_delete", methods={"POST"})
     */
    public function delete(Request $request, Job $job, EntityManagerInterface $entityManager, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$job->getId(), $request->request->get('_token'))) {
            $entityManager->remove($job);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_job_index', [], Response::HTTP_SEE_OTHER);
    }
}
