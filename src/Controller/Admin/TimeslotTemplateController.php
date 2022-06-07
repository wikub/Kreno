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

use App\Entity\TimeslotTemplate;
use App\Entity\WeekTemplate;
use App\Form\TimeslotTemplateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/template/timeslot", name="admin_timeslot_template_")
 */
class TimeslotTemplateController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/week/{weekTemplate}/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, WeekTemplate $weekTemplate): Response
    {
        $timeslotTemplate = new TimeslotTemplate();
        $timeslotTemplate->setWeekTemplate($weekTemplate);

        $form = $this->createForm(TimeslotTemplateType::class, $timeslotTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timeslotTemplate);
            $entityManager->flush();

            return $this->redirectToRoute('admin_week_template_show', ['id' => $weekTemplate->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/timeslot_template/new.html.twig', [
            'timeslot_template' => $timeslotTemplate,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/clone/{id}", name="clone_new", methods={"GET", "POST"})
     */
    public function clone_new(Request $request, EntityManagerInterface $entityManager, TimeslotTemplate $timeslotTemplate): Response
    {
        $cloneTimeslotTemplate = clone $timeslotTemplate;

        $entityManager->persist($cloneTimeslotTemplate);
        $entityManager->flush();

        return $this->redirectToRoute('admin_timeslot_template_edit', ['id' => $cloneTimeslotTemplate->getId()]);
    }

    /**
     * @Route("/{id}", name="timeslot_template_show", methods={"GET"})
     */
    public function show(TimeslotTemplate $timeslotTemplate): Response
    {
        return $this->render('admin/timeslot_template/show.html.twig', [
            'timeslot_template' => $timeslotTemplate,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, TimeslotTemplate $timeslotTemplate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TimeslotTemplateType::class, $timeslotTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_week_template_show', ['id' => $timeslotTemplate->getWeekTemplate()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/timeslot_template/edit.html.twig', [
            'timeslot_template' => $timeslotTemplate,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, TimeslotTemplate $timeslotTemplate, EntityManagerInterface $entityManager): Response
    {
        $weekTemplateId = $timeslotTemplate->getWeekTemplate()->getId();
        if ($this->isCsrfTokenValid('delete'.$timeslotTemplate->getId(), $request->request->get('_token'))) {
            $entityManager->remove($timeslotTemplate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_week_template_show', ['id' => $weekTemplateId], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/enable", name="enable")
     */
    public function enable(TimeslotTemplate $timeslotTemplate, Request $request): Response
    {
        $timeslotTemplate->setEnabled(true);

        $this->em->persist($timeslotTemplate);
        $this->em->flush();

        $this->addFlash('success', 'Créneau modèle activé pour les prochains cycles générés');

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/{id}/disable", name="disable")
     */
    public function disable(TimeslotTemplate $timeslotTemplate, Request $request): Response
    {
        $timeslotTemplate->setEnabled(false);

        $this->em->persist($timeslotTemplate);
        $this->em->flush();

        $this->addFlash('success', 'Créneau modèle desactivé pour les prochains cycles générés');

        return $this->redirect($request->headers->get('referer'));
    }
}
