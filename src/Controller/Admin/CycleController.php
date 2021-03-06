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
}
