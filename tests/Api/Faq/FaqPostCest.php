<?php

namespace App\Tests\Api\Faq;

use App\Factory\FaqFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class FaqPostCest
{
    protected static function expectedProperties(): array
    {
        return [
            'question' => 'string',
            'reponse' => 'string',
        ];
    }

    public function anonymousUserForbiddenToPutFaq(ApiTester $I): void
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

        $I->sendPost('api/faqs', [
            'reponse' => 'Comment se connecter ?',
            'question' => 'il faut cliquer en bas sur se connecter',
        ]);

        $I->seeResponseCodeIs(403);
    }

    public function enseignantMemberUserCanPostFaq(ApiTester $I): void
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

        $I->sendPost('/api/faqs', [
            'reponse' => 'Comment se connecter ?',
            'question' => 'il faut cliquer en bas sur se connecter',
        ]);

        $I->seeResponseCodeIsSuccessful();
    }
}