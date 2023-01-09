<?php


namespace App\Tests\Controller\ProjetTER;

use App\Factory\ProjetTERFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class DeleteCest
{
    public function authorCanDelete(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ENSEIGNANT'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $projet = ProjetTERFactory::createOne(['author' => $user]);

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/projet_ter/'.$projet->getId());
        $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/projet_ter/'.$projet->getId().'/delete');
    }
}
