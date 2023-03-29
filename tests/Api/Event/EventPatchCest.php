<?php

namespace App\Tests\Api\Event;

use App\Entity\Event;
use App\Factory\EventFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class EventPatchCest
{
    protected static function expectedProperties(): array
    {
        return [
            'title' => 'string',
            'text' => 'string',
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

        $EventData = [
            'title' => 'Cours de Mr.ADMIN',
            'text' => 'voici le cours de Mr.ADMIN qui sera consacré au maths',
        ];
        EventFactory::createOne($EventData);

        $I->sendPatch('/api/events/1');

        $I->seeResponseCodeIs(403);
    }

    public function EnseignantUserCanPatchtFaq(ApiTester $I): void
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
        $events = EventFactory::createOne($EventData);

        $I->sendPut('/api/events/1');

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Event::class, '/api/events/'.$events->getId());
    }
}