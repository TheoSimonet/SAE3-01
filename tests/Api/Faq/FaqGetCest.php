<?php

namespace App\Tests\Api\Faq;

use App\Entity\Faq;
use App\Factory\FaqFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class FaqGetCest
{
    protected static function expectedProperties(): array
    {
        return [
            'question' => 'string',
            'reponse' => 'string',
        ];
    }

    public function AnonymousUserGetFaqElement(ApiTester $I): void
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

        $I->sendGet('/api/faqs/1');

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Faq::class, '/api/faqs/1');
    }

    public function EnseignantUserCanGetStageElement(ApiTester $I): void
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

        $faqData = [
            'question' => 'comment se connecter en tant qu enseignant',
            'reponse' => 'Même procédure que pour un étudiant',
        ];
        FaqFactory::createOne($faqData);

        $I->sendGet('/api/faqs/1');

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Faq::class, '/api/faqs/1');
    }
}