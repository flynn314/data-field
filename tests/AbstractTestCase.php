<?php
declare(strict_types=1);

namespace Flynn314\DataField\Tests;

use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use Flynn314\DataField\Tests\Model\TestModel;

abstract class AbstractTestCase extends TestCase
{
    protected function getInitData(): array
    {
        return [
            'test1' => 'Test 1 data.',
            'test2' => 'Test 2 data.',
        ];
    }

    protected function getModel(array $data): TestModel
    {
        return new TestModel([
            'uuid' => Str::uuid()->toString(),
            'data' => $data,
        ]);
    }
}
