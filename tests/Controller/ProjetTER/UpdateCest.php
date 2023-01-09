<?php


namespace App\Tests\Controller\ProjetTER;

use App\Factory\ProjetTERFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class UpdateCest
{
    public function authorCanUpdate(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ENSEIGNANT'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $projet = ProjetTERFactory::createOne(['author' => $user]);

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/projet_ter/'.$projet->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/projet_ter/'.$projet->getId().'/update');
    }

    public function randomUserCannotUpdate(ControllerTester $I)
    {
        $author = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ENSEIGNANT'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);

        $randomUser = UserFactory::createOne(['email' => 'teddy.pong@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ENSEIGNANT'],
            'firstname' => 'teddy',
            'lastname' => 'pong']);
        $realRandomUser = $randomUser->object();

        $projet = ProjetTERFactory::createOne(['author' => $author]);

        $I->amLoggedInAs($realRandomUser);
        $I->amOnPage('/projet_ter/'.$projet->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/projet_ter/'.$projet->getId().'/update');
        $I->canSeePageRedirectsTo('/projet_ter/'.$projet->getId().'/update', '/projet_ter');
    }
}
