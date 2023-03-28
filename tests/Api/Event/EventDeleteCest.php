<?php

namespace App\Tests\Api\Event;

use App\Factory\EventFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class EventDeleteCest
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

        $EventData = [
            'title' => 'Cours de Mr.ADMIN',
            'text' => 'voici le cours de Mr.ADMIN qui sera consacré au maths',
        ];
        EventFactory::createOne($EventData);

        $I->sendDelete('/api/events/1');

        $I->seeResponseCodeIs(403);
    }

    public function enseignantUserCanDeleteFaq(ApiTester $I): void
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

        $EventData = [
            'title' => 'Cours de Mr.ADMIN',
            'text' => 'voici le cours de Mr.ADMIN qui sera consacré au maths',
        ];
        EventFactory::createOne($EventData);

        $I->sendDelete('/api/events/1');

        $I->seeResponseCodeIsSuccessful();
    }
}