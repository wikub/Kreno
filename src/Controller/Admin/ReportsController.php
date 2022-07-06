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

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/reports", name="admin_reports_")
 */
class ReportsController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('admin/reports/index.html.twig', [
        ]);
    }

    /**
     * @Route("/members", name="members")
     */
    public function members(ManagerRegistry $entityManager): Response
    {
        $conn = $entityManager->getConnection();
        $sql = 'SELECT  u.id, u.firstname, u.name, cc.name c_name, sum(cl.nb_timeslot) nb_timeslot, sum(cl.nb_hour) nb_hour
        FROM `user` u
        INNER JOIN (
            SELECT cc.user_id, ct.name, cycle_start.`start`, cycle_finish.finish
            FROM commitment_contract cc
            INNER JOIN commitment_type ct ON ct.id = cc.type_id
            INNER JOIN cycle cycle_start ON cycle_start.id = cc.start_cycle_id
            LEFT JOIN cycle cycle_finish ON cycle_finish.id = cc.finish_cycle_id
            WHERE cycle_start.`start` <= NOW() AND (cycle_finish.finish IS NULL or cycle_finish.finish >= NOW())
        ) cc ON cc.user_id = u.id
        LEFT JOIN commitment_log cl ON cl.user_id = u.id
        GROUP BY u.name, u.firstname
        ORDER BY u.name, u.firstname
        ';

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery();

        return $this->render('admin/reports/members.html.twig', [
            'result' => $result->fetchAll(),
        ]);
    }
}
