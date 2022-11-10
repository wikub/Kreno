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

use App\Entity\Cycle;
use App\Repository\CycleRepository;
use App\Service\CommitmentContratDebitLogApply;
use App\Service\CycleGenerator;
use App\Service\GetParam;
use App\Service\Notification\CycleStartNotification;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/cycle", name="admin_cycle_")
 */
class CycleController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CycleRepository $cycleRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $cycles = $paginator->paginate(
            $cycleRepository->getQueryBuilder(),
            $request->query->getInt('page', 1),
            30
        );

        $lastOpenCycle = $cycleRepository->getLastOpen();

        return $this->render('admin/cycle/index.html.twig', [
            'cycles' => $cycles,
            'lastOpenCycle' => $lastOpenCycle,
        ]);
    }

    /**
     * @Route("/{id}/apply", name="apply_commit",  methods={"GET","POST"})
     */
    public function applyCommimentContracts(Cycle $cycle, CycleRepository $cycleRepository, Request $request, CommitmentContratDebitLogApply $service): Response
    {
        $lastOpenCycle = $cycleRepository->getLastOpen();
        if ($lastOpenCycle !== $cycle) {
            $this->addFlash('warning', 'Vous devez d\'abord faire cette action sur les cycles précédents');

            return $this->redirectToRoute('admin_cycle_index');
        }

        if ('POST' === $request->getMethod()) {
            if ($this->isCsrfTokenValid('apply'.$cycle->getId(), $request->request->get('_token'))) {
                $service->applyCommitmentContracts($cycle);
                $this->addFlash('success', 'L\'application des engagements sur le cycle '.$cycle->getStart()->format('d/m/Y').' au '.$cycle->getFinish()->format('d/m/Y').' a été réalisé');

                return $this->redirectToRoute('admin_cycle_index');
            }
        }

        return $this->render('admin/cycle/apply.html.twig', [
            'cycle' => $cycle,
        ]);
    }

    /**
     * @Route("/{id}/notification/send", name="send_notification",  methods={"GET","POST"})
     */
    public function sendNotification(Cycle $cycle, CycleStartNotification $cycleStartNotification): Response
    {
        try {
            $cycleStartNotification->send($cycle);
            $this->addFlash('success', 'La notification d\'ouverture du créneau a été envoyé');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Il y a eu un problème lors de l\'envoi de la notification');
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('admin_cycle_index');
    }

    /**
     * @Route("/week/gen", name="week_generator")
     */
    public function weekGenerator(
        Request $request,
        CycleGenerator $generator,
        GetParam $getParam,
        CycleRepository $cycleRepository
    ): Response {
        $paramCycleStart = \DateTimeImmutable::createFromFormat('Y-m-d', $getParam->get('CYCLE_START'));

        if (!$paramCycleStart) {
            $this->addFlash('error', 'Le pamatrètre CYCLE_START n\'a pas été trouvé ou n\'est pas au bon format AAAA-MM-DD');

            return $this->redirectToRoute('admin_cycle_index');
        }

        $paramCycleNbWeeks = (int) $getParam->get('CYCLE_NB_WEEKS');

        if (!($paramCycleNbWeeks > 0)) {
            $this->addFlash('error', 'Le pamatrètre CYCLE_NB_WEEKS n\'a pas été trouvé ou n\'est pas supérieur à 0');

            return $this->redirectToRoute('admin_cycle_index');
        }

        $lastCycleFound = $cycleRepository->findLast();

        // Find next Cycles to generate
        $lastFinishDate = $paramCycleStart->modify('-1 day');
        if (null !== $lastCycleFound) {
            $lastFinishDate = $lastCycleFound->getFinish();
        }
        $nextStartDate = $lastFinishDate->modify('+1 day');
        $nextFinishDate = $nextStartDate->modify('+'.$paramCycleNbWeeks.' weeks - 1 day');

        if ($this->isCsrfTokenValid('generate-cycle'.$nextStartDate->format('Ymd'), $request->request->get('_token'))) {
            try {
                $generator->generate($nextStartDate, $nextFinishDate);
                $this->addFlash('success', 'Le nouveau cycle a été généré');

                return $this->redirectToRoute('admin_cycle_index');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderForm('admin/cycle/week_generator.html.twig', [
            'nextStartDate' => $nextStartDate,
            'nextFinishDate' => $nextFinishDate,
        ]);
    }
}
