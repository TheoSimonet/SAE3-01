<?php


namespace App\Tests\Controller\ProjetTER;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function constainsWhenAdmin(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'root@example.com',
            'password' => 'admin',
            'roles' => ['ROLE_ADMIN'],
            'firstname' => 'admin',
            'lastname' => 'admin']);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/projet_ter');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Liste des projets TER');
    }

    public function redirectionWhenNotAuthenticated(ControllerTester $I)
    {
        $I->amOnPage('/projet_ter');
        $I->seeResponseCodeIsSuccessful();
        $I->canSeePageRedirectsTo('/projet_ter', '/login');
    }

    public function redirectionWhenUser(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_USER'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/projet_ter');
        $I->seeResponseCodeIs(403);
    }
}
