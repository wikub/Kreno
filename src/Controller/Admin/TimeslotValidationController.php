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
use App\Form\Admin\TimeslotValidationType;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\WorkflowInterface;

/**
 * @Route("/admin/timeslot-validation", name="admin_timeslot_validation_")
 */
class TimeslotValidationController extends AbstractController
{
    private $timeslotWorkflow;
    private $em;

    public function __construct(WorkflowInterface $timeslotWorkflow, EntityManagerInterface $entityManager)
    {
        $this->timeslotWorkflow = $timeslotWorkflow;
        $this->em = $entityManager;
    }

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
    public function process(Request $request, Timeslot $timeslot): Response
    {
        $timeslot->setValidationAt(new \DateTimeImmutable());
        $timeslot->setUserValidation($this->getUser());

        $form = $this->createForm(TimeslotValidationType::class, $timeslot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->timeslotWorkflow->apply($timeslot, 'to_admin_validated');
            } catch (LogicException $exception) {
                $this->addFlash('error', 'L\'opération ne peut pas être réalisée [workflow]');

                return $this->redirect($request->headers->get('referer'));
            }

            $this->em->persist($timeslot);
            $this->em->flush();

            $this->addFlash('success', 'Le créneau a été validé et cloturé');

            return $this->redirectToRoute('admin_timeslot_validation_index');
        }

        return $this->renderForm('admin/timeslot_validation/process.html.twig', [
            'timeslot' => $timeslot,
            'form' => $form,
        ]);
    }
}
