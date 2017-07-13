<?php

namespace Ticme\BadgeBundle\Manager;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Ticme\BadgeBundle\Entity\BadgeUnlock;
use Ticme\BadgeBundle\Event\BadgeUnlockEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BadgeManager {

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(ObjectManager $manager, EventDispatcherInterface $dispatcher)
    {
        $this->em = $manager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Check if a badge exists for this action and action occurence and unlock it for the user
     *
     * @param User $user
     * @param string $action
     * @param int $action_count
     */
    public function checkAndUnlock(User $user, string $action, int $action_count): void {
        // Vérifier si on a un badge qui correspond à action et action count
        try {
            $badge = $this->em
                ->getRepository('TicmeBadgeBundle:Badge')
                ->findWithUnlockForAction($user->getId(), $action, $action_count);
            // Vérifier si l'utilisateur a déjà ce badge
            if ($badge->getUnlocks()->isEmpty()) {
                // Débloquer le badge pour l'utilisateur en question
                $unlock = new BadgeUnlock();
                $unlock->setBadge($badge);
                $unlock->setUser($user);
                $this->em->persist($unlock);
                $this->em->flush();
                // Emetter un évènement pour informer l'application du déblocage de bage
                $this->dispatcher->dispatch(BadgeUnlockEvent::NAME, new BadgeUnlockEvent($unlock));
            }
        } catch (NoResultException $e) {

        }
    }

    /**
     * Get Badges unlocked for the current user
     *
     * @param User $user
     * @return array
     */
    public function getBadgeFor (User $user): array {
        return $this->em->getRepository('TicmeBadgeBundle:Badge')->findUnlockedFor($user->getId());
    }

}