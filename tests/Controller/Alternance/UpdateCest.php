<?php


namespace App\Tests\Controller\Alternance;

use App\Factory\AlternanceFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class UpdateCest
{
    public function authorCanUpdate(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $alternance = AlternanceFactory::createOne(['author' => $user]);

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/alternance/'.$alternance->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/alternance/'.$alternance->getId().'/update');
    }

    public function randomUserCannotUpdate(ControllerTester $I)
    {
        $author = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);

        $randomUser = UserFactory::createOne(['email' => 'teddy.pong@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'teddy',
            'lastname' => 'pong']);
        $realRandomUser = $randomUser->object();

        $alternance = AlternanceFactory::createOne(['author' => $author]);

        $I->amLoggedInAs($realRandomUser);
        $I->amOnPage('/alternance/'.$alternance->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/alternance/'.$alternance->getId().'/update');
        $I->canSeePageRedirectsTo('/alternance/'.$alternance->getId().'/update', '/alternance');
    }
}
