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

use App\Entity\WeekTemplate;
use App\Form\WeekTemplateType;
use App\Repository\WeekTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/template", name="week_template_")
 */
class WeekTemplateController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(WeekTemplateRepository $weekTemplateRepository): Response
    {
        return $this->render('week_template/index.html.twig', [
            'week_templates' => $weekTemplateRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $weekTemplate = new WeekTemplate();
        $form = $this->createForm(WeekTemplateType::class, $weekTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($weekTemplate);
            $entityManager->flush();

            return $this->redirectToRoute('week_template_show', ['id' => $weekTemplate->getId()]);
        }

        return $this->renderForm('week_template/new.html.twig', [
            'week_template' => $weekTemplate,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(WeekTemplate $weekTemplate): Response
    {
        $weekdays = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche',
        ];

        $weekdaysTimeslotTemplates = [];
        for ($i = 1; $i <= 7; ++$i) {
            $weekdaysTimeslotTemplates[$i] = [];
        }

        foreach ($weekTemplate->getTimeslotTemplates() as $timeslotTemplate) {
            $weekdaysTimeslotTemplates[$timeslotTemplate->getDayWeek()][$timeslotTemplate->getStart()->format('Hi').$timeslotTemplate->getId()] = $timeslotTemplate;
        }
        // Tri jour et heure
        // ksort($weekdaysTimeslotTemplates);
        foreach ($weekdaysTimeslotTemplates as &$day) {
            ksort($day);
        }

        return $this->render('week_template/show.html.twig', [
            'weekdays' => $weekdays,
            'weekdaysTimeslotTemplates' => $weekdaysTimeslotTemplates,
            'week_template' => $weekTemplate,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, WeekTemplate $weekTemplate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WeekTemplateType::class, $weekTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('week_template_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('week_template/edit.html.twig', [
            'week_template' => $weekTemplate,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, WeekTemplate $weekTemplate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$weekTemplate->getId(), $request->request->get('_token'))) {
            $entityManager->remove($weekTemplate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('week_template_index', [], Response::HTTP_SEE_OTHER);
    }
}
