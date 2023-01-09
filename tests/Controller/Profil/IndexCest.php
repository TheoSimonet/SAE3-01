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

    public function notWrongUserInformations(ControllerTester $I)
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
        $I->see($user->getLastname(), 'li');
        $I->see($user->getFirstname(), 'li');
        $I->see($user->getEmail(), 'li');
        $I->see($user->getPhone(), 'li');
    }

    public function notWrongUserRole(ControllerTester $I)
    {
        // administrateur
        $admin = UserFactory::createOne(['roles' => ['ROLE_ADMIN']]);
        $realAdmin = $admin->object();

        $I->amLoggedInAs($realAdmin);
        $I->amOnPage('/profil');
        $I->seeResponseCodeIsSuccessful();
        $I->see('administrateur', 'h2');

        // utilisateur
        $user = UserFactory::createOne(['roles' => ['ROLE_USER']]);
        $realUser = $user->object();

        $I->amLoggedInAs($realUser);
        $I->amOnPage('/profil');
        $I->seeResponseCodeIsSuccessful();
        $I->see('utilisateur', 'h2');

        // étudiant
        $student = UserFactory::createOne(['roles' => ['ROLE_ETUDIANT']]);
        $realStudent = $student->object();

        $I->amLoggedInAs($realStudent);
        $I->amOnPage('/profil');
        $I->seeResponseCodeIsSuccessful();
        $I->see('étudiant', 'h2');

        // enseignant
        $teacher = UserFactory::createOne(['roles' => ['ROLE_ENSEIGNANT']]);
        $realTeacher = $teacher->object();

        $I->amLoggedInAs($realTeacher);
        $I->amOnPage('/profil');
        $I->seeResponseCodeIsSuccessful();
        $I->see('enseignant', 'h2');

        // entreprise
        $company = UserFactory::createOne(['roles' => ['ROLE_ENTREPRISE']]);
        $realCompany = $company->object();

        $I->amLoggedInAs($realCompany);
        $I->amOnPage('/profil');
        $I->seeResponseCodeIsSuccessful();
        $I->see('entreprise', 'h2');
    }
}
