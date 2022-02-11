<?php

namespace App\Controller;

use App\Entity\CommitmentContract;
use App\Entity\User;
use App\Form\CommitmentContractType;
use App\Repository\CommitmentContractRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commitment/contract", name="commitment_contract_")
 */
class CommitmentContractController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CommitmentContractRepository $commitmentContractRepository): Response
    {
        return $this->render('commitment_contract/index.html.twig', [
            'commitment_contracts' => $commitmentContractRepository->findAll(),
        ]);
    }

    /**
     * @Route("/user/{user}/new/", name="new_foruser", methods={"GET", "POST"})
     */
    public function newForUser(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        
        $commitmentContract = new CommitmentContract();
        $commitmentContract->setUser($user);

        $form = $this->createForm(CommitmentContractType::class, $commitmentContract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if( $currentContract = $user->getCurrentCommitmentContract() ) {
                $finish = clone($commitmentContract->getStart());
                $finish->modify( '-1 day' );
                $currentContract->setFinish( $finish );
                $entityManager->persist($currentContract);
            }
            

            $entityManager->persist($commitmentContract);
            $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $user->getId()]);
        }

        return $this->renderForm('commitment_contract/new.html.twig', [
            'commitment_contract' => $commitmentContract,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commitmentContract = new CommitmentContract();
        $form = $this->createForm(CommitmentContractType::class, $commitmentContract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commitmentContract);
            $entityManager->flush();

            return $this->redirectToRoute('commitment_contract_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commitment_contract/new.html.twig', [
            'commitment_contract' => $commitmentContract,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="commitment_contract_show", methods={"GET"})
     */
    public function show(CommitmentContract $commitmentContract): Response
    {
        return $this->render('commitment_contract/show.html.twig', [
            'commitment_contract' => $commitmentContract,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CommitmentContract $commitmentContract, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommitmentContractType::class, $commitmentContract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_show', ['id' => $commitmentContract->getUser()->getId()]);
        }

        return $this->renderForm('commitment_contract/edit.html.twig', [
            'commitment_contract' => $commitmentContract,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="commitment_contract_delete", methods={"POST"})
     */
    public function delete(Request $request, CommitmentContract $commitmentContract, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commitmentContract->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commitmentContract);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commitment_contract_index', [], Response::HTTP_SEE_OTHER);
    }
}
