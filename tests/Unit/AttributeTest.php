<?php
declare(strict_types=1);

namespace Flynn314\DataField\Tests\Unit;

use Flynn314\DataField\Tests\AbstractTestCase;
use Flynn314\DataField\Tests\Model\TestModelWithAttribute;
use Illuminate\Support\Str;

/**
 * vendor/bin/phpunit tests/unit/AttributeTest.php
 */
final class AttributeTest extends AbstractTestCase
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

    public function testArraySet(): void
	{
        $initData = $this->getInitData();
        $model = new TestModelWithAttribute([
            'uuid' => Str::uuid()->toString(),
            'data' => $initData,
        ]);

        $model->array1 = ['test1', 'test2'];

        $this->assertArrayNotHasKey('name', $model->getAttributes());

        $values = array_flip($model->data->toArray()['array1']);

        $this->assertArrayHasKey('test1', $values);
        $this->assertArrayHasKey('test2', $values);
        $this->assertCount(2, $values);
	}

    public function testArraySetOnEmptyValue(): void
	{
        $model = new TestModelWithAttribute();
        $model->array2 = ['test1', 'test2'];

        $this->assertArrayNotHasKey('name', $model->getAttributes());

        $values = array_flip($model->data->toArray()['array2']);

        $this->assertArrayHasKey('test1', $values);
        $this->assertArrayHasKey('test2', $values);
        $this->assertCount(2, $values);
	}
}
