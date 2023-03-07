<?php


namespace App\Tests\Controller\Alternance;

use App\Factory\AlternanceFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class ShowCest
{
    public function containsInTitle(ControllerTester $I)
    {
        $user = UserFactory::createOne([
            'password' => 'teddy',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $alternance = AlternanceFactory::createOne(['author' => $user]);

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/alternance/'.$alternance->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle("Visualisation de l'alternance n°".$alternance->getId());
    }

    public function containsWhenAuthor(ControllerTester $I)
    {
        $user = UserFactory::createOne([
            'password' => 'teddy',
            'roles' => ['ROLE_ENTREPRISE'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $alternance = AlternanceFactory::createOne(['author' => $user]);

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/alternance/'.$alternance->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->seeElement('#edit');
        $I->seeElement('#delete');
    }
}
