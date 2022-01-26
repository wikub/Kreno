<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\Timeslot;
use App\Form\TimeslotType;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/timeslot")
 */
class TimeslotController extends AbstractController
{
    /**
     * @Route("/", name="timeslot_index", methods={"GET"})
     */
    public function index(TimeslotRepository $timeslotRepository): Response
    {
        return $this->render('timeslot/index.html.twig', [
            'timeslots' => $timeslotRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="timeslot_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $timeslot = new Timeslot();
        $form = $this->createForm(TimeslotType::class, $timeslot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timeslot);
            $entityManager->flush();

            return $this->redirectToRoute('timeslot_show', ['id' => $timeslot->getId()]);
        }

        return $this->renderForm('timeslot/new.html.twig', [
            'timeslot' => $timeslot,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="timeslot_show", methods={"GET"})
     */
    public function show(Timeslot $timeslot): Response
    {
        return $this->render('timeslot/show.html.twig', [
            'timeslot' => $timeslot,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="timeslot_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Timeslot $timeslot, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TimeslotType::class, $timeslot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('timeslot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('timeslot/edit.html.twig', [
            'timeslot' => $timeslot,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/enable", name="timeslot_enable", methods={"GET"})
     */
    public function enable(Timeslot $timeslot, EntityManagerInterface $entityManager): Response
    {
        $timeslot->setEnabled(true);
        $entityManager->persist($timeslot);
        $entityManager->flush();
    
        $this->addFlash(
            'notice',
            'Le créneau a été activé'
        );

        return $this->redirectToRoute('timeslot_show', ['id' => $timeslot->getId()]);
    }

    /**
     * @Route("/{id}/disable", name="timeslot_disable", methods={"GET"})
     */
    public function disable(Timeslot $timeslot, EntityManagerInterface $entityManager): Response
    {
        $timeslot->setEnabled(false);
        $entityManager->persist($timeslot);
        $entityManager->flush();
        $this->addFlash(
            'notice',
            'Le créneau a été desactivé'
        );
        
        return $this->redirectToRoute('timeslot_show', ['id' => $timeslot->getId()]);
    }

    /**
     * @Route("/{id}", name="timeslot_delete", methods={"POST"})
     */
    public function delete(Request $request, Timeslot $timeslot, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$timeslot->getId(), $request->request->get('_token'))) {
            $entityManager->remove($timeslot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('timeslot_index', [], Response::HTTP_SEE_OTHER);
    }
}
