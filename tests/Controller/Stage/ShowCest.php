<?php


namespace App\Tests\Controller\Stage;

use App\Factory\StageFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class ShowCest
{
    public function containsInTitle(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $stage = StageFactory::createOne(['author' => $user]);

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/stage/'.$stage->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Visualisation du stage n°'.$stage->getId());
    }

    public function containsWhenAuthor(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $stage = StageFactory::createOne(['author' => $user]);

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/stage/'.$stage->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->seeElement('#edit');
        $I->seeElement('#delete');
    }
}
