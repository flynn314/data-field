<?php
declare(strict_types=1);

namespace Flynn314\DataField\Tests\Unit;

use Flynn314\DataField\Tests\AbstractTestCase;
use Flynn314\DataField\Tests\Model\TestModelWithAttribute;
use Illuminate\Support\Str;

/**
 * vendor/bin/phpunit tests/unit/CastsTest.php
 */
final class CastsTest extends AbstractTestCase
{
	public function testSet(): void
	{
        $initData = $this->getInitData();
        $model = new TestModelWithAttribute([
            'uuid' => Str::uuid()->toString(),
            'data' => $initData,
        ]);

        $model->name = 'Test';

        $this->assertArrayNotHasKey('name', $model->getAttributes());

        $this->assertEquals('Test', $model->name);
        $this->assertEquals('Test', $model->data['name']);
	}
}
