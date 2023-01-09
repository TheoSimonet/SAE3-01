<?php

declare(strict_types=1);

namespace App\Tests\Controller\Stage;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function constainsWhenAuthenticated(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'root@example.com',
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

    public function constainsWhenNotAuthenticated(ControllerTester $I)
    {
        $I->amOnPage('/stage');
        $I->seeResponseCodeIsSuccessful();
        $I->canSeePageRedirectsTo('/stage', '/login');
    }
}
