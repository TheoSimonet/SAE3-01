<?php


namespace App\Tests\Controller\ProjetTER;

use App\Factory\ProjetTERFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class ShowCest
{
    public function containsInTitle(ControllerTester $I)
    {
        $user = UserFactory::createOne([
            'password' => 'teddy',
            'roles' => ['ROLE_ENSEIGNANT'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $projet = ProjetTERFactory::createOne(['author' => $user]);

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/projet_ter/'.$projet->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->seeInTitle('Visualisation du projet n°'.$projet->getId());
    }

    public function containsWhenAuthor(ControllerTester $I)
    {
        $user = UserFactory::createOne([
            'password' => 'teddy',
            'roles' => ['ROLE_ENSEIGNANT'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $projet = ProjetTERFactory::createOne(['author' => $user]);

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/projet_ter/'.$projet->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->seeElement('#edit');
        $I->seeElement('#delete');
    }

    public function containsWhenStudent(ControllerTester $I)
    {
        $user = UserFactory::createOne([
            'password' => 'teddy',
            'roles' => ['ROLE_ETUDIANT'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $projet = ProjetTERFactory::createOne();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/projet_ter/'.$projet->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->see('choisir', 'a');
    }
}
