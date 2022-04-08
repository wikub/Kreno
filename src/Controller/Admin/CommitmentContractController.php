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

use App\Entity\CommitmentContract;
use App\Entity\User;
use App\Form\Admin\CommitmentContractType;
use App\Repository\CommitmentContractRepository;
use App\Repository\CycleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/{user}/commitmentcontract", name="admin_commitment_contract_")
 */
class CommitmentContractController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CommitmentContractRepository $commitmentContractRepository, User $user): Response
    {
        return $this->render('admin/commitment_contract/index.html.twig', [
            'commitment_contracts' => $commitmentContractRepository->findBy(['user' => $user]),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, User $user, CycleRepository $cycleRepository): Response
    {
        $commitmentContract = new CommitmentContract();
        $commitmentContract->setUser($user);

        $form = $this->createForm(CommitmentContractType::class, $commitmentContract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // if ($currentContract = $user->getCurrentCommitmentContract()) {
            //     $finish = clone $commitmentContract->getStart();
            //     $finish->modify('-1 day');
            //     $currentContract->setFinish($finish);
            //     $entityManager->persist($currentContract);
            // }

            // Add regular
            foreach ($commitmentContract->getRegularTimeslots() as $regular) {
                $regular->setStart($commitmentContract->getStartCycle()->getStart());
            }

            $entityManager->persist($commitmentContract);
            $entityManager->flush();

            return $this->redirectToRoute('admin_commitment_contract_index', ['user' => $user->getId()]);
        }

        return $this->renderForm('admin/commitment_contract/new.html.twig', [
            'commitment_contract' => $commitmentContract,
            'form' => $form,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(CommitmentContract $commitmentContract, User $user): Response
    {
        return $this->render('admin/commitment_contract/show.html.twig', [
            'commitment_contract' => $commitmentContract,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CommitmentContract $commitmentContract, EntityManagerInterface $entityManager, User $user, CycleRepository $cycleRepository): Response
    {
        $form = $this->createForm(CommitmentContractType::class, $commitmentContract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Add regular
            foreach ($commitmentContract->getRegularTimeslots() as $regular) {
                if (null === $regular->getStart()) {
                    $regular->setStart($commitmentContract->getStartCycle()->getStart());
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('admin_commitment_contract_index', ['user' => $user->getId()]);
        }

        return $this->renderForm('admin/commitment_contract/edit.html.twig', [
            'commitment_contract' => $commitmentContract,
            'form' => $form,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, CommitmentContract $commitmentContract, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commitmentContract->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commitmentContract);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_commitment_contract_index', [], Response::HTTP_SEE_OTHER);
    }
}
