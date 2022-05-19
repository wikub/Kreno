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

use App\Entity\Job;
use App\Entity\Timeslot;
use App\Event\JobSubscribedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/timeslot", name="timeslot_")
 */
class TimestlotController extends AbstractController
{
    private $dispatcher;

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @Route("/timestlot/{id}", name="show")
     */
    public function show(Timeslot $timeslot): Response
    {
        $userAlreadySubscribe = \count($timeslot->getUserJobs($this->getUser())) > 0 ? true : false;

        return $this->render('timestlot/show.html.twig', [
            'timeslot' => $timeslot,
            'userAlreadySubscribe' => $userAlreadySubscribe,
        ]);
    }

    /**
     * @Route("/timestlot/job/{job}/subscribe", name="subscribe")
     */
    public function subscribe(Job $job, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$job->getTimeslot()->isSubscribable()) {
            $this->addFlash('error', 'Il n\'est pas possible de s\'inscrire à un créneau déjà débuté');

            return $this->redirect($request->headers->get('referer'));
        }

        if (!$job->getTimeslot()->isOpen()) {
            $this->addFlash('error', 'Il n\'est pas possible de s\'inscrire à un créneau non ouvert');

            return $this->redirect($request->headers->get('referer'));
        }

        if (\count($job->getTimeslot()->getUserJobs($this->getUser())) > 0) {
            $this->addFlash('error', 'Il n\'est pas possible de s\'inscrire à un créneau où l\'on est déjà inscrit');

            return $this->redirect($request->headers->get('referer'));
        }

        if (null !== $job->getUser()) {
            $this->addFlash('error', 'Il n\'est pas possible de s\'inscrire à un poste déjà occupé');

            return $this->redirect($request->headers->get('referer'));
        }

        $job->setUser($this->getUser());
        $entityManager->persist($job);
        $entityManager->flush();

        // Create an event job subscription
        $event = new JobSubscribedEvent($job);
        $this->dispatcher->dispatch($event, JobSubscribedEvent::NAME);

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/timestlot/job/{job}/unsubscribe", name="unsubscribe")
     */
    public function unsubscribe(Job $job, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$job->getTimeslot()->isUnsubscribable()) {
            $this->addFlash('error', 'Il n\'est pas possible de se desinscrire d\'un créneau qui commence dans moins de 48h. Veuillez prendre contact avec le responsable.');

            return $this->redirect($request->headers->get('referer'));
        }

        if (!$job->getTimeslot()->isOpen()) {
            $this->addFlash('error', 'Il n\'est pas possible de se desinscrire d\'un créneau non ouvert');

            return $this->redirect($request->headers->get('referer'));
        }

        $job->setUser(null);
        $entityManager->persist($job);
        $entityManager->flush();

        return $this->redirect($request->headers->get('referer'));
    }
}
