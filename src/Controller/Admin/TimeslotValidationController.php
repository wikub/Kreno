<?php

namespace App\Controller\Admin;

use App\Entity\Timeslot;
use App\Form\Admin\TimeslotValidationType;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
     * @Route("/admin/timeslot-validation", name="admin_timeslot_validation_")
     */
class TimeslotValidationController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(TimeslotRepository $timeslotRepository): Response
    {
        $timeslots = $timeslotRepository->findForValidation();

        return $this->render('admin/timeslot_validation/index.html.twig', [
            'timeslots' => $timeslots,
        ]);
    }

    /**
     * @Route("/{id}/process", name="process", methods={"GET", "POST"})
     */
    public function process(Request $request, EntityManagerInterface $entityManager, Timeslot $timeslot): Response
    {
        $timeslot->setValidationAt(new \DateTimeImmutable());
        $timeslot->setUserValidation($this->getUser());

        $form = $this->createForm(TimeslotValidationType::class, $timeslot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timeslot);
            $entityManager->flush();

            $this->addFlash('notice', 'Le créneau a été validé et cloturé');

            return $this->redirectToRoute('admin_timeslot_validation_index');
        }

        return $this->renderForm('admin/timeslot_validation/process.html.twig', [
            'timeslot' => $timeslot,
            'form' => $form,
        ]);
    }
}
