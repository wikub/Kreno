<?php

namespace App\Controller\Admin;

use App\Entity\Timeslot;
use App\Entity\Week;
use App\Form\Admin\TimeslotType;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;

/**
 * @Route("/admin/timeslot", name="admin_timeslot_")
 */
class TimeslotController extends AbstractController
{
    private $workflows;
 
    public function __construct(Registry $workflows)
    {
        $this->workflows = $workflows;
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

        return $this->renderForm('admin_timeslot/edit.html.twig', [
            'timeslot' => $timeslot,
            'form' => $form,
        ]);
    }

    

    /**
     * @Route("/{id}/enable", name="enable", methods={"GET"})
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

        return $this->redirectToRoute('admin_timeslot_show', ['id' => $timeslot->getId()]);
    }

    /**
     * @Route("/{id}/disable", name="disable", methods={"GET"})
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
        
        return $this->redirectToRoute('admin_timeslot_show', ['id' => $timeslot->getId()]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Timeslot $timeslot, EntityManagerInterface $entityManager): Response
    {
        $week = $timeslot->getWeek();
        if ($this->isCsrfTokenValid('delete'.$timeslot->getId(), $request->request->get('_token'))) {
            $entityManager->remove($timeslot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_week_show', ['id' => $week->getId()]);
    }
}
