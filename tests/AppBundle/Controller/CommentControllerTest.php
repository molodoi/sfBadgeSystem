<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadUserData;
use AppBundle\DataFixtures\ORM\LoadBadgeData;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class CommentControllerTest extends WebTestCase{

    public function testUnauthorized(){
        $client = $this->makeClient();
        $client->request('GET', '/create');
        $this->assertStatusCode('302', $client);
    }

    public function testPostComment(){
        $em = $this->getContainer()->get('doctrine')->getManager();
        $references = $this->loadFixtures([
            LoadUserData::class,
            LoadBadgeData::class
        ])->getReferenceRepository();
        $user = $references->getReference('user');
        $this->loginAs($user, 'main');
        $client = $this->makeClient();
        $crawler = $client->request('GET', '/create');
        $this->assertStatusCode('200', $client);

        //poste un commentaire
        $form = $crawler->selectButton('Commenter')->form();
        $form->setValues([
            'appbundle_comment[content]' => 'Hello world'
        ]);
        $client->submit($form);
        $this->assertStatusCode('200', $client);
        $this->assertCount(1, $em->getRepository('AppBundle:Comment')->findAll());
        $this->assertCount(1, $em->getRepository('TicmeBadgeBundle:BadgeUnlock')->findAll());
    }
}