<?php

namespace App\Tests\Api\Stage;

use App\Entity\Stage;
use App\Factory\StageFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class StagePostCest
{
    protected static function expectedProperties(): array
    {
        return [
            'titre' => 'string',
            'description' => 'string',
        ];
    }

    public function anonymousUserForbiddenToPutStage(ApiTester $I): void
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

        $I->sendPost('api/stages', [
            'titre' => 'Stage en développement web',
            'description' => 'A la recherche d\'un stagiaire en tant que développeur web',
        ]);

        $I->seeResponseCodeIs(403);
    }

    public function companyMemberUserCanPostStage(ApiTester $I): void
    {
        $userData = [
            'email' => 'user1@example.com',
            'firstname' => 'firstname1',
            'lastname' => 'lastname1',
            'roles' => ['ROLE_ENTREPRISE'],
        ];
        $user = UserFactory::createOne($userData);

        $connected = $user->object();

        $I->amLoggedInAs($connected);

        $I->sendPost('/api/stages', [
            'titre' => 'Stage en développement web',
            'description' => 'A la recherche d\'un stagiaire en tant que développeur web',
        ]);

        $I->seeResponseCodeIsSuccessful();
    }
}
