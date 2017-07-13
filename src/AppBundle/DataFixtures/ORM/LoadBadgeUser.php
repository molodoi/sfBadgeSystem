<?php
/**
 * Created by PhpStorm.
 * User: m.lamerant
 * Date: 12/07/2017
 * Time: 13:04
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ticme\BadgeBundle\Entity\Badge;


class LoadBadgeUser extends AbstractFixture implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $badge = new Badge();
        $badge->setName('Timide');
        $badge->setActionName('comment');
        $badge->setActionCount(1);
        $manager->persist($badge);

        $badge = new Badge();
        $badge->setName('Papote');
        $badge->setActionName('comment');
        $badge->setActionCount(2);
        $manager->persist($badge);

        $manager->persist($badge);
        $manager->flush();
    }

}
