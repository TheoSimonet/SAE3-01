<?php


namespace App\Tests\Controller\Alternance;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class CreateCest
{
    public function companyCanCreate(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/alternance/create');
        $I->seeResponseCodeIsSuccessful();
    }

    public function userCannotCreate(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ETUDIANT'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/alternance/create');
        $I->canSeeResponseCodeIs(403);
    }
}
