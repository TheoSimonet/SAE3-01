<?php

namespace App\Tests\Api\Stage;

class StageGetCest
{
    protected static function expectedProperties(): array
    {
        return [
            'titre' => 'string',
            'description' => 'string',
        ];
    }
}
