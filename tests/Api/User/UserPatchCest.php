<?php

namespace App\Tests\Api\User;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use Codeception\Util\HttpCode;

class UserPatchCest
{
    protected static function expectedProperties(): array
    {
        return [
            'id' => 'integer',
            'email' => 'string',
            'firstname' => 'string',
            'lastname' => 'string',
            'phone' => 'string',
        ];
    }

    public function anonymousUserForbiddenToPatchUser(ApiTester $I): void
    {
        // 1. 'Arrange'
        UserFactory::createOne();

        // 2. 'Act'
        $I->sendPatch('/api/users/1');

        // 3. 'Assert'
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedUserForbiddenToPatchOtherUser(ApiTester $I): void
    {
        // 1. 'Arrange'
        /** @var $user User */
        $user = UserFactory::createOne()->object();
        UserFactory::createOne();
        $I->amLoggedInAs($user);

        // 2. 'Act'
        $I->sendPatch('/api/users/2');

        // 3. 'Assert'
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function authenticatedUserCanPatchOwnData(ApiTester $I): void
    {
        // 1. 'Arrange'
        $dataInit = [
            'lastname' => 'lastname1',
            'firstname' => 'firstname1',
            'email' => 'user1@example.com',
        ];

        /** @var $user User */
        $user = UserFactory::createOne($dataInit)->object();
        $I->amLoggedInAs($user);

        // 2. 'Act'
        $dataPatch = [
            'lastname' => 'lastname2',
            'firstname' => 'firstname2',
            'email' => 'user2@example.com',
        ];
        $I->sendPatch('/api/users/1', $dataPatch);

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(User::class, '/api/users/1');
        $I->seeResponseIsAnItem(self::expectedProperties(), $dataPatch);
    }

    public function authenticatedUserCanChangeHisPassword(ApiTester $I): void
    {
        // 1. 'Arrange'
        $dataInit = [
            'email' => 'user1@example.com',
            'password' => 'password',
        ];
        /** @var $user User */
        $user = UserFactory::createOne($dataInit)->object();
        $I->amLoggedInAs($user);

        // 2. 'Act'
        $dataPatch = ['password' => 'new password'];
        $I->sendPatch('/api/users/1', $dataPatch);

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(User::class, '/api/users/1');
        $I->seeResponseIsAnItem(self::expectedProperties());

        $I->amOnPage('/logout');
        // Don't check response code since homepage is not configured (404)
        // $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/login');
        $I->seeResponseCodeIsSuccessful();
        $I->submitForm(
            'form',
            ['email' => 'user1@example.com', 'password' => 'new password'],
            'Authentification'
        );
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/');
    }

    #[DataProvider('invalidDataLeadsToUnprocessableEntityProvider')]
    public function invalidDataLeadsToUnprocessableEntity(ApiTester $I, Example $example): void
    {
        // 1. 'Arrange'
        /** @var $user User */
        $user = UserFactory::createOne()->object();
        $I->amLoggedInAs($user);

        // 2. 'Act'
        $dataPut = [
            $example['property'] => $example['value'],
        ];
        $I->sendPut('/api/users/1', $dataPut);

        // 3. 'Assert'
        $I->seeResponseCodeIs(422);
        $I->seeResponseIsJson();
    }

    protected function invalidDataLeadsToUnprocessableEntityProvider(): array
    {
        return [
            ['property' => 'firstname', 'value' => '<&">'],
            ['property' => 'lastname', 'value' => '<&">'],
            ['property' => 'email', 'value' => 'badmail'],
        ];
    }
}
