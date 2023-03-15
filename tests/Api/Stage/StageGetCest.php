<?php

namespace App\Tests\Api\Stage;

use App\Entity\Stage;
use App\Factory\StageFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class StageGetCest
{
    protected static function expectedProperties(): array
    {
        return [
            'titre' => 'string',
            'description' => 'string',
        ];
    }

    public function studentUserGetStageElement(ApiTester $I): void
    {
        $userData = [
            'email' => 'user1@example.com',
            'firstname' => 'firstname1',
            'lastname' => 'lastname1',
            'roles' => ['ROLE_ETUDIANT'],
        ];
        $user = UserFactory::createOne($userData);

        $connected = $user->object();

        $I->amLoggedInAs($connected);

        $stageData = [
            'titre' => 'Stage en développement web',
            'description' => 'A la recherche d\'un stagiaire en tant que développeur web',
        ];
        StageFactory::createOne($stageData);

        $I->sendGet('/api/stages/1');

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Stage::class, '/api/stages/1');
    }
}
