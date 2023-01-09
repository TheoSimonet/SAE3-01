<?php

declare(strict_types=1);

namespace App\Tests\Controller\Stage;

use App\Tests\Support\ControllerTester;

class IndexCest
{
    public function constains(ControllerTester $I)
    {
        $I->amOnPage('/stage');
        $I->seeResponseCodeIsSuccessful();
    }
}
