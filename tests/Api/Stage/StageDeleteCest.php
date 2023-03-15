<?php

namespace App\Tests\Api\Stage;

use App\Factory\StageFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class StageDeleteCest
{
    public function anonymousUserForbiddenToDeleteStage(ApiTester $I): void
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

        $stageData = [
            'titre' => 'Stage en développement web',
            'description' => 'A la recherche d\'un stagiaire en tant que développeur web',
        ];
        StageFactory::createOne($stageData);

        $I->sendDelete('/api/stages/1');

        $I->seeResponseCodeIs(403);
    }
}
