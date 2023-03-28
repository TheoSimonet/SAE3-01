<?php

namespace App\Tests\Api\Faq;

use App\Factory\FaqFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class FaqDeleteCest
{
    public function anonymousUserForbiddenToDeleteFaq(ApiTester $I): void
    {
        $userData = [
            'email' => 'user1@example.com',
            'firstname' => 'firstname1',
            'lastname' => 'lastname1',
            'roles' => ['ROLE_USER'],
        ];
        $user = UserFactory::createOne($userData);

        $connected = $user->object();

        $I->amLoggedInAs($connected);

        $FaqData = [
            'question' => 'Comment se connecter',
            'reponse' => 'il faut cliquer en bas sur se connecter',
        ];
        FaqFactory::createOne($FaqData);

        $I->sendDelete('/api/faqs/1');

        $I->seeResponseCodeIs(403);
    }

    public function enseignantUserForbiddenToDeleteFaq(ApiTester $I): void
    {
        $userData = [
            'email' => 'user1@example.com',
            'firstname' => 'firstname1',
            'lastname' => 'lastname1',
            'roles' => ['ROLE_ENSEIGNANT'],
        ];
        $user = UserFactory::createOne($userData);

        $connected = $user->object();

        $I->amLoggedInAs($connected);

        $FaqData = [
            'question' => 'Comment se connecter',
            'reponse' => 'il faut cliquer en bas sur se connecter',
        ];
        FaqFactory::createOne($FaqData);

        $I->sendDelete('/api/faqs/1');

        $I->seeResponseCodeIs(403);
    }

    public function AdminUserCanDeleteFaq(ApiTester $I): void
    {
        $userData = [
            'email' => 'user1@example.com',
            'firstname' => 'firstname1',
            'lastname' => 'lastname1',
            'roles' => ['ROLE_ADMIN'],
        ];
        $user = UserFactory::createOne($userData);

        $connected = $user->object();

        $I->amLoggedInAs($connected);

        $FaqData = [
            'question' => 'Comment se connecter',
            'reponse' => 'il faut cliquer en bas sur se connecter',
        ];
        $faq = FaqFactory::createOne($FaqData);

        $I->sendDelete('/api/faqs/'.$faq->getId());

        $I->seeResponseCodeIsSuccessful();
    }
}