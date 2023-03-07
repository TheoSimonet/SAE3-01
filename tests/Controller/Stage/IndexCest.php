<?php

declare(strict_types=1);

namespace App\Tests\Controller\Stage;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function constainsWhenAdmin(ControllerTester $I)
    {
        $user = UserFactory::createOne([
            'password' => 'admin',
            'roles' => ['ROLE_ADMIN'],
            'firstname' => 'admin',
            'lastname' => 'admin']);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/stage');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Liste des stages');
    }

    public function redirectionWhenNotAuthenticated(ControllerTester $I)
    {
        $I->amOnPage('/stage');
        $I->seeResponseCodeIsSuccessful();
        $I->canSeePageRedirectsTo('/stage', '/login');
    }

    public function redirectionWhenUser(ControllerTester $I)
    {
        $user = UserFactory::createOne([
            'password' => 'teddy',
            'roles' => ['ROLE_USER'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/');
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/stage');
        $I->seeResponseCodeIs(403);
    }

    public function constainsWhenCompany(ControllerTester $I)
    {
        $user = UserFactory::createOne([
            'password' => 'admin',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'admin',
            'lastname' => 'admin']);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/stage');
        $I->seeResponseCodeIsSuccessful();
        $I->see('Créer', 'a');
    }
}
