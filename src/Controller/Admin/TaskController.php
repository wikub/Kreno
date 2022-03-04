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

use App\Form\Admin\TaskWeekTimeslotGeneratorType;
use App\Service\CommitmentContratDebitLogApply;
use App\Service\TimeslotAutoValidation;
use App\Service\WeekTimeslotGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/task", name="admin_task_")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('admin/task/index.html.twig', [
        ]);
    }

    /**
     * @Route("/week/gen", name="week_generator")
     */
    public function weekGenerator(Request $request, WeekTimeslotGenerator $generator): Response
    {
        $defaultData = [
            'start' => new \DateTime(),
            'finish' => (new \DateTime())->modify('last day of this year'),
        ];
        $form = $this->createForm(TaskWeekTimeslotGeneratorType::class, $defaultData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $generator->generate($data['start'], $data['finish'], $data['ifWeekExist']);
        }

        return $this->renderForm('admin/task/week_generator.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/timeslot/autovalidation", name="timeslot_autovalidation")
     */
    public function autoValidation(TimeslotAutoValidation $timeslotAutoValidation): Response
    {
        $timeslotAutoValidation->timeslotAutoValidation();

        return $this->redirectToRoute('admin_task_index');
    }

    /**
     * @Route("/timeslot/cycledebit", name="cycle_debit")
     */
    public function cycleDebit(CommitmentContratDebitLogApply $service): Response
    {
        $service->applyDebit();

        return $this->redirectToRoute('admin_task_index');
    }
}
