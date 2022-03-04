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

use App\Entity\Timeslot;
use App\Entity\Week;
use App\Form\Admin\TimeslotType;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

/**
 * @Route("/admin/timeslot", name="admin_timeslot_")
 */
class TimeslotController extends AbstractController
{
    private $timeslotWorkflow;
    private $em;

    public function __construct(WorkflowInterface $timeslotWorkflow, EntityManagerInterface $entityManager)
    {
        $this->timeslotWorkflow = $timeslotWorkflow;
        $this->em = $entityManager;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(TimeslotRepository $timeslotRepository): Response
    {
        return $this->render('admin/timeslot/index.html.twig', [
            'timeslots' => $timeslotRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/forweek/{week}", name="new_forweek", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, Week $week): Response
    {
        $timeslot = new Timeslot();
        $timeslot->setWeek($week);

        $form = $this->createForm(TimeslotType::class, $timeslot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timeslot);
            $entityManager->flush();

            try {
                $this->timeslotWorkflows->apply($timeslot, 'to_open');
            } catch (LogicException $exception) {
            }

            return $this->redirectToRoute('admin_timeslot_show', ['id' => $timeslot->getId()]);
        }

        return $this->renderForm('admin/timeslot/new.html.twig', [
            'timeslot' => $timeslot,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Timeslot $timeslot): Response
    {
        return $this->render('admin/timeslot/show.html.twig', [
            'timeslot' => $timeslot,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Timeslot $timeslot, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TimeslotType::class, $timeslot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_timeslot_show', ['id' => $timeslot->getId()]);
        }

        return $this->renderForm('admin/timeslot/edit.html.twig', [
            'timeslot' => $timeslot,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/close", name="close")
     */
    public function close(Timeslot $timeslot, Request $request): Response
    {
        //Jobs are still occuped ?
        foreach ($timeslot->getJobs() as $job) {
            if (null !== $job->getUser()) {
                $this->addFlash('warning', 'Il y a encore au moins un poste occupé.');

                return $this->redirect($request->headers->get('referer'));
            }
        }

        try {
            $this->timeslotWorkflow->apply($timeslot, 'to_closed');
        } catch (LogicException $exception) {
            $this->addFlash('error', 'L\'opération ne peut pas être réalisée [workflow]');

            return $this->redirect($request->headers->get('referer'));
        }

        $this->em->persist($timeslot);
        $this->em->flush();

        $this->addFlash('success', 'Créneau fermé');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/{id}/reopen", name="reopen")
     */
    public function reopen(Timeslot $timeslot, Request $request): Response
    {
        try {
            $this->timeslotWorkflow->apply($timeslot, 'to_close');
        } catch (LogicException $exception) {
            $this->addFlash('error', 'L\'opération ne peut pas être réalisée [workflow]');

            return $this->redirect($request->headers->get('referer'));
        }

        $this->em->persist($timeslot);
        $this->em->flush();

        $this->addFlash('success', 'Créneau fermé');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Timeslot $timeslot, EntityManagerInterface $entityManager): Response
    {
        $week = $timeslot->getWeek();
        if ($this->isCsrfTokenValid('delete'.$timeslot->getId(), $request->request->get('_token'))) {
            //Jobs are still occuped ?
            foreach ($timeslot->getJobs() as $job) {
                if (null !== $job->getUser()) {
                    $this->addFlash('error', 'L\'opération ne peut pas être réalisée [workflow]');

                    return $this->redirect($request->headers->get('referer'));
                }
            }

            $entityManager->remove($timeslot);
            $entityManager->flush();

            $this->addFlash('success', 'Créneau supprimé');
        }

        return $this->redirectToRoute('admin_week_show', ['id' => $week->getId()]);
    }
}
