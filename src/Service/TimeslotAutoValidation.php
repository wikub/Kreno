<?php

namespace App\Service;

use App\Repository\JobDoneTypeRepository;
use App\Repository\TimeslotRepository;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class TimeslotAutoValidation
{
    private $em;
    private $repo;
    private $timeslotWorkflow;
    private $flash;

    public function __construct(
        EntityManagerInterface $entityManager,
        TimeslotRepository $timeslotRepository,
        FlashBagInterface $flash,
        WorkflowInterface $timeslotWorkflow,
        JobDoneTypeRepository $jobDoneTypeRepository
    ) {
        $this->em = $entityManager;
        $this->repo = $timeslotRepository;
        $this->timeslotWorkflow = $timeslotWorkflow;
        $this->flash = $flash;
        $this->repoJobDoneType = $jobDoneTypeRepository;
    }

    public function timeslotAutoValidation()
    {
        if( !($timeslots = $this->repo->findForValidation24h()) ){
            $this->flash->add('notice','Aucun créneau à auto valider');
            return;
        };
        
        if( !($jobDone = $this->repoJobDoneType->findDefaultValue()) ) {
            $this->flash->add('notice','Aucune valeur par défaut définit');
            return;
        }

        $now = (new \DateTime())->format('d/m/Y H:i');

        foreach($timeslots as $timeslot) {
            foreach($timeslot->getJobs() as $job) {
                if( $job->getUser() == null ) continue;
                
                $job->setJobDone($jobDone);
                $this->em->persist($job);
            }

            $timeslot->setCommentValidation('Auto validation '.$now);

            try {
                $this->timeslotWorkflow->apply($timeslot, 'to_admin_validated');
            } catch (LogicException $exception) {
                $this->flash->add('error', 'L\'opération ne peut pas être réalisée [workflow]');
                continue;
            }

            $this->em->persist($timeslot);
            $this->em->flush();

            $this->flash->add('notice','Le créneau '.$timeslot->getDisplayName().' '.$timeslot->getDisplayDateInterval().' a été validé');
        }
    }
}