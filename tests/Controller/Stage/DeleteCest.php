<?php


namespace App\Tests\Controller\Stage;

use App\Factory\StageFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class DeleteCest
{
    public function authorCanDelete(ControllerTester $I)
    {
        $user = UserFactory::createOne([
            'password' => 'teddy',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $stage = StageFactory::createOne(['author' => $user]);

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/stage/'.$stage->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/stage/'.$stage->getId().'/delete');
    }

    public function randomUserCannotDelete(ControllerTester $I)
    {
        $author = UserFactory::createOne([
            'password' => 'teddy',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);

        $randomUser = UserFactory::createOne([
            'password' => 'teddy',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'teddy',
            'lastname' => 'pong']);
        $realRandomUser = $randomUser->object();

        $stage = StageFactory::createOne(['author' => $author]);

        $I->amLoggedInAs($realRandomUser);
        $I->amOnPage('/stage/'.$stage->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/stage/'.$stage->getId().'/delete');
        $I->canSeePageRedirectsTo('/stage/'.$stage->getId().'/delete', '/stage');
    }
}
