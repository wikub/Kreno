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

use App\Entity\CommitmentLog;
use App\Form\Admin\CommitmentLogType;
use App\Form\Model\AddCommitmentLogFormModel;
use App\Repository\CommitmentLogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/commitmentlog", name="admin_commitment_log_")
 */
class CommitmentLogController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(
        CommitmentLogRepository $commitmentLogRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        // $commitmentLogs = $commitmentLogRepository->findBy([], ['createdAt' => 'DESC']);

        $commitmentLogs = $paginator->paginate(
            $commitmentLogRepository->getQueryBuilder(),
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('admin/commitment_log/index.html.twig', [
            'commitmentLogs' => $commitmentLogs,
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET", "POST"})
     */
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $addCommitmentFormModel = new AddCommitmentLogFormModel();
        $form = $this->createForm(CommitmentLogType::class, $addCommitmentFormModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($addCommitmentFormModel->users as $user) {
                $commitmentLog = new CommitmentLog();
                $commitmentLog->setUser($user);
                $commitmentLog->setNbHour($addCommitmentFormModel->nbHour);
                $commitmentLog->setNbTimeslot($addCommitmentFormModel->nbTimeslot);
                $commitmentLog->setComment($addCommitmentFormModel->comment);
                $commitmentLog->setCreatedBy($this->getUser());

                $entityManager->persist($commitmentLog);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Ajout d\'engagement réalisé');

            return $this->redirectToRoute('admin_commitment_log_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/commitment_log/add.html.twig', [
            'form' => $form,
        ]);
    }
}
