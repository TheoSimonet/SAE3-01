<?php

namespace App\Tests\Api\Event;

use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class EventPostCest
{
    protected static function expectedProperties(): array
    {
        return [
            'title' => 'string',
            'text' => 'string',
        ];
    }

    public function anonymousUserForbiddenToPostEvent(ApiTester $I): void
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

        $I->sendPost('api/events', [
            'title' => 'Cours de Mr.ADMIN',
            'text' => 'voici le cours de Mr.ADMIN qui sera consacré au maths',
        ]);

        $I->seeResponseCodeIs(403);
    }

    public function enseignantMemberUserCanPostFaq(ApiTester $I): void
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

        $I->sendPost('/api/events', [
            'title' => 'Cours de Mr.ADMIN',
            'text' => 'voici le cours de Mr.ADMIN qui sera consacré au maths',
        ]);

        $I->seeResponseCodeIs(403);
}}