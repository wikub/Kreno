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

use App\Repository\CycleRepository;
use App\Repository\SettingRepository;
use App\Service\CommitmentContratDebitLogApply;
use App\Service\CycleGenerator;
use App\Service\TimeslotAutoValidation;
use App\Service\UserTimeslotApproachNotification;
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
    public function weekGenerator(Request $request, CycleGenerator $generator, SettingRepository $settingRepository, CycleRepository $cycleRepository): Response
    {
        // Get the settings
        $settingCycleStart = $settingRepository->findOneByCode('CYCLE_START');
        $settingCycleNbWeeks = $settingRepository->findOneByCode('CYCLE_NB_WEEKS');

        // Find the last cycle
        $lastCycle = $cycleRepository->findLast();

        // Find next Cycles to generate
        $lastStartDate = new \DateTime($settingCycleStart->getValue());
        if (null !== $lastCycle) {
            $lastStartDate = $lastCycle->getFinish()->modify('+ 1 day');
        }
        $nextStartDate = (clone $lastStartDate)->modify('+'.$settingCycleNbWeeks->getValue().' weeks');
        $nextFinishDate = (clone $nextStartDate)->modify('+'.$settingCycleNbWeeks->getValue().' weeks - 1 day');
        if ($this->isCsrfTokenValid('generate-cycle'.$nextStartDate->format('Ymd'), $request->request->get('_token'))) {
            $generator->generate($nextStartDate, $nextFinishDate);
        }

        return $this->renderForm('admin/task/week_generator.html.twig', [
            'nextStartDate' => $nextStartDate,
            'nextFinishDate' => $nextFinishDate,
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

    /**
     * @Route("/timeslot/notification", name="timeslot_notification")
     */
    public function timeslotNotification(UserTimeslotApproachNotification $service): Response
    {
        $service->send();

        return $this->redirectToRoute('admin_task_index');
    }
}
