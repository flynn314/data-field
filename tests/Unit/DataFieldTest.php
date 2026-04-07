<?php
declare(strict_types=1);

namespace Flynn314\DataField\Tests\Unit;

use Flynn314\DataField\Tests\AbstractTestCase;

/**
 * vendor/bin/phpunit tests/unit/DataFieldTest.php
 */
final class DataFieldTest extends AbstractTestCase
{
	public function testSetGet(): void
	{
        $initData = $this->getInitData();
        $model = $this->getModel($initData);

        $model->setData('test3', 'Simple test value.');
        $this->assertArrayHasKey('test3', $model->data);

        $model->unsetData('test2');
        $this->assertArrayNotHasKey('test2', $model->data);

        $this->assertEquals($initData['test1'], $model->getData('test1'));
	}

	public function testRawUpdate(): void
	{
        $initData = $this->getInitData();
        $model = $this->getModel($initData);

        $model->data->test3 = 'Simple test value.';
        $this->assertEquals('Simple test value.', $model->getData('test3'));
        $this->assertEquals('Simple test value.', $model->data->test3);
	}
}
