<?php

namespace App\Tests\Api\Event;

use App\Entity\Event;
use App\Factory\EventFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class EventPutCest
{
    protected static function expectedProperties(): array
    {
        return [
            'title' => 'string',
            'text' => 'string',
        ];
    }

    public function anonymousUserForbiddenToPutEvent(ApiTester $I): void
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

        $I->sendPut('/api/events/1');

        $I->seeResponseCodeIs(403);
    }

    public function EnseignantUserCanPutEvent(ApiTester $I): void
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
        $event = EventFactory::createOne($EventData);

        $I->sendPut('/api/events/1');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Event::class, '/api/events/'.$event->getId());
    }
}