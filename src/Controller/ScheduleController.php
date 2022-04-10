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

use App\Entity\Cycle;
use App\Repository\CycleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/schedule", name="schedule_")
 */
class ScheduleController extends AbstractController
{
    /**
     * @Route("/", name="current", methods={"GET"})
     */
    public function current(CycleRepository $cycleRepository): Response
    {
        $currentCycle = $cycleRepository->findCurrent();

        if (null !== $currentCycle) {
            return $this->redirectToRoute('schedule_show', ['cycle' => $currentCycle->getId()]);
        }

        $this->addFlash('notice', 'Aucun cycle n\'a été trouvé !');

        return $this->redirectToRoute('schedule_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("{cycle}/next", name="next", methods={"GET"})
     */
    public function next(Cycle $cycle, CycleRepository $cycleRepository): Response
    {
        $nextCycle = $cycleRepository->findNext($cycle);

        if (null !== $nextCycle) {
            return $this->redirectToRoute('schedule_show', ['cycle' => $nextCycle->getId()]);
        }

        $this->addFlash('notice', 'Aucun cycle n\'a été trouvé !');

        return $this->redirectToRoute('schedule_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("{cycle}/previous", name="previous", methods={"GET"})
     */
    public function previous(Cycle $cycle, CycleRepository $cycleRepository): Response
    {
        $previousCycle = $cycleRepository->findPrevious($cycle);

        if (null !== $previousCycle) {
            return $this->redirectToRoute('schedule_show', ['cycle' => $previousCycle->getId()]);
        }

        $this->addFlash('notice', 'Aucune semaine n\'a été trouvé !');

        return $this->redirectToRoute('admin_week_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/list", name="index", methods={"GET"})
     */
    public function index(CycleRepository $cycleRepository): Response
    {
        return $this->render('schedule/index.html.twig', [
            'cycles' => $cycleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{cycle}", name="show", methods={"GET"})
     */
    public function show(Cycle $cycle): Response
    {
        return $this->render('schedule/show.html.twig', [
            'cycle' => $cycle,
        ]);
    }
}
