<?php

namespace App\Tests\Api\Event;

use App\Entity\Event;
use App\Factory\EventFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class EventGetCest
{
    protected static function expectedProperties(): array
    {
        return [
            'title' => 'string',
            'text' => 'string',
        ];
    }

    public function StudentUserCanGetEventElement(ApiTester $I): void
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

        $I->sendGet('/api/events/1');

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Event::class, '/api/events/1');
    }

    public function EnseignantUserCanGetEventElement(ApiTester $I): void
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

        $I->sendGet('/api/events/1');

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Event::class, '/api/events/1');
    }
}