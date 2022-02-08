<?php

namespace App\Controller;

use App\Entity\TimeslotTemplate;
use App\Entity\WeekTemplate;
use App\Form\TimeslotTemplateType;
use App\Repository\TimeslotTemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/template/timeslot", name="timeslot_template_")
 */
class TimeslotTemplateController extends AbstractController
{
    
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

            return $this->redirectToRoute('week_template_show', ['id' => $weekTemplate->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('timeslot_template/new.html.twig', [
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

        return $this->redirectToRoute('timeslot_template_edit', ['id' => $cloneTimeslotTemplate->getId()]);
        
    }

    /**
     * @Route("/{id}", name="timeslot_template_show", methods={"GET"})
     */
    public function show(TimeslotTemplate $timeslotTemplate): Response
    {
        return $this->render('timeslot_template/show.html.twig', [
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

            return $this->redirectToRoute('week_template_show', ['id' => $timeslotTemplate->getWeekTemplate()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('timeslot_template/edit.html.twig', [
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

        return $this->redirectToRoute('week_template_show', ['id' => $weekTemplateId], Response::HTTP_SEE_OTHER);
    }
}
