<?php


namespace App\Tests\Controller\ProjetTER;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class CreateCest
{
    public function teacherCanCreate(ControllerTester $I)
    {
        $user = UserFactory::createOne(['email' => 'teddy.ping@example.com',
            'password' => 'teddy',
            'roles' => ['ROLE_ENSEIGNANT'],
            'firstname' => 'teddy',
            'lastname' => 'ping']);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/projet_ter/create');
        $I->seeResponseCodeIsSuccessful();
    }
}
