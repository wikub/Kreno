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

use App\Repository\CommitmentContractRepository;
use App\Repository\CommitmentLogRepository;
use App\Repository\JobRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="index_")
 */
class IndexController extends AbstractController
{
    /**
     * @Route("", name="index")
     */
    public function index(
        JobRepository $jobRepository,
        CommitmentContractRepository $commitmentContractRepository,
        CommitmentLogRepository $commitmentLogRepository
    ): Response {
        // Get the next user jobs for next 45 days
        $nextJobs = $jobRepository->findNextForUser($this->getUser());

        // Get the current user Contract
        $currentCommitmentContract = $commitmentContractRepository->getCurrentContractForUser($this->getUser());

        // Get balance nb timeslot and hour
        $sumNbTimeslot = $commitmentLogRepository->getSumNbTimeslot($this->getUser());
        $sumNbHour = $commitmentLogRepository->getSumNbHour($this->getUser());

        return $this->render('index/index.html.twig', [
            'user' => $this->getUser(),
            'nextJobs' => $nextJobs,
            'currentCommitmentContract' => $currentCommitmentContract,
            'sumNbTimeslot' => $sumNbTimeslot,
            'sumNbHour' => $sumNbHour,
        ]);
    }
}
