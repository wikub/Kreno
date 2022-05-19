<?php

/*
 * This file is part of the Kreno package.
 *
 * (c) Valentin Van Meeuwen <contact@wikub.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Event;

use App\Service\UserActivityAdd;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class JobSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            JobSubscribedEvent::NAME => 'onJobSubscription',
        ];
    }

    public function onJobSubscription(JobSubscribedEvent $event, UserActivityAdd $userActivityAdd, Security $security)
    {
        $userActivityAdd->add($security->getUser(), $this->userActivityAdd::ACTION_LOGIN, [$event->getJob()]);
    }
}
