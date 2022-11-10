<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\EmailLog as EntityEmailLog;
use App\Repository\EmailLogRepository;
use Symfony\Component\Mime\Email;

class EmailLog
{
    private EmailLogRepository $emailLogRepository;

    public function __construct(EmailLogRepository $emailLogRepository)
    {
        $this->emailLogRepository = $emailLogRepository;
    }

    public function log(Email $email): void
    {
        $to = $email->getTo()[0];
        $emailLog = (new EntityEmailLog())
            ->setEmailTo($to->getAddress())
            ->setNameTo($to->getName())
            ->setSubject($email->getSubject())
            ->setHtmlContent($email->getHtmlBody())
            ->setTextContent($email->getTextBody())
            ->setSendedAt(new \DateTimeImmutable())
        ;

        $this->emailLogRepository->add($emailLog, true);
    }
}
