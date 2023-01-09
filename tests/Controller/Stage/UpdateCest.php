<?php


namespace App\Tests\Controller\Stage;

use App\Tests\Support\ControllerTester;

class UpdateCest
{
    public function userCannotUpdate(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_USER'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/stage/.');
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/stage/./update');
        $I->canSeePageRedirectsTo('/stage/./update', '/stage/.');
    }
}
