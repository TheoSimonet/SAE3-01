<?php

namespace App\Tests\Api\Stage;

use App\Entity\Stage;
use App\Factory\StageFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class StagePutCest
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

        $stageData = [
            'titre' => 'Stage en développement web',
            'description' => 'A la recherche d\'un stagiaire en tant que développeur web',
        ];
        StageFactory::createOne($stageData);

        $I->sendPut('/api/stages/1');

        $I->seeResponseCodeIs(403);
    }

    public function companyMemberUserForbiddenToPutOtherStage(ApiTester $I): void
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

        $stageData = [
            'titre' => 'Stage en développement web',
            'description' => 'A la recherche d\'un stagiaire en tant que développeur web',
        ];
        StageFactory::createOne($stageData);

        $I->sendPut('/api/stages/1');

        $I->seeResponseCodeIs(403);
    }

    public function companyMemberUserCanPutOwnStageData(ApiTester $I): void
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

        $stageData = [
            'titre' => 'Stage en développement web',
            'description' => 'A la recherche d\'un stagiaire en tant que développeur web',
            'author' => $user,
        ];
        $stage = StageFactory::createOne($stageData);

        $I->sendPut('/api/stages/'.$stage->getId());

        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(Stage::class, '/api/stages/'.$stage->getId());
    }
}
