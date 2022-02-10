<?php

namespace App\Controller;


use App\Form\TaskWeekTimeslotGeneratorType;
use App\Repository\WeekTemplateRepository;
use App\Service\WeekTimeslotGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/task", name="task_")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }

    /**
     * @Route("/week/gen", name="week_generator")
     */
    public function weekGenerator(Request $request, WeekTimeslotGenerator $generator): Response
    {
        $defaultData = [
            'start' => new \DateTime(),
            'finish' => (new \DateTime())->modify('last day of this year')
        ];
        $form = $this->createForm(TaskWeekTimeslotGeneratorType::class, $defaultData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            
            $generator->generate($data['start'], $data['finish']);
        }

        return $this->renderForm('task/week_generator.html.twig', [
            'form' => $form,
        ]);
    }
}
