<?php
namespace Ticme\BadgeBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Ticme\BadgeBundle\Entity\BadgeUnlock;

class BadgeUnlockEvent extends Event
{
    const NAME = 'badge.unlock';

    /**
     * @var BadgeUnlock
     */
    private $badgeUnlock;


    public function __construct(BadgeUnlock $badgeUnlock)
    {
        $this->badgeUnlock = $badgeUnlock;
    }

    /**
     * @return BadgeUnlock
     */
    public function getBadgeUnlock()
    {
        return $this->badgeUnlock;
    }

    public function getBadge(){
        return $this->badgeUnlock->getBadge();
    }

    public function getUser(){
        return $this->badgeUnlock->getUser();
    }

    /**
     * @param BadgeUnlock $badgeUnlock
     */
    public function setBadgeUnlock($badgeUnlock)
    {
        $this->badgeUnlock = $badgeUnlock;
    }
}