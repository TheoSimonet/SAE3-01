<?php

namespace App\Tests\Api\Faq;

use App\Entity\Faq;
use App\Factory\FaqFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class FaqPutCest
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

        $FaqData = [
            'reponse' => 'Comment se connecter ?',
            'question' => 'il faut cliquer en bas sur se connecter',
        ];
        FaqFactory::createOne($FaqData);

        $I->sendPut('/api/faqs/1');

        $I->seeResponseCodeIs(403);
    }

    public function EnseignantUserCanPutFaq(ApiTester $I): void
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
            'reponse' => 'Comment se connecter ?',
            'question' => 'il faut cliquer en bas sur se connecter',
        ];
        $faqs = FaqFactory::createOne($FaqData);

        $I->sendPut('/api/faqs/1');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Faq::class, '/api/faqs/'.$faqs->getId());
    }

    public function AdminUserCanPutFaq(ApiTester $I): void
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
            'reponse' => 'Comment se connecter ?',
            'question' => 'il faut cliquer en bas sur se connecter',
        ];
        $faqs = FaqFactory::createOne($FaqData);

        $I->sendPut('/api/faqs/1');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Faq::class, '/api/faqs/'.$faqs->getId());
    }

}