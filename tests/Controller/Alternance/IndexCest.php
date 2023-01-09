<?php


namespace App\Tests\Controller\Alternance;

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
        $I->amOnPage('/alternance');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Liste des alternances');
    }

    public function redirectionWhenNotAuthenticated(ControllerTester $I)
    {
        $I->amOnPage('/alternance');
        $I->seeResponseCodeIsSuccessful();
        $I->canSeePageRedirectsTo('/alternance', '/login');
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
        $I->amOnPage('/alternance');
        $I->seeResponseCodeIs(403);
    }

    public function constainsWhenCompany(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'root@example.com',
            'password' => 'admin',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'admin',
            'lastname' => 'admin']);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/alternance');
        $I->seeResponseCodeIsSuccessful();
        $I->see('Créer', 'a');
    }
}
