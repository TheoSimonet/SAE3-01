<?php


namespace App\Tests\Controller\Profil;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function seeUserFirstnameInTitle(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ADMIN'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/profil');
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Page de profil de '.$user->getFirstname());
    }
}
