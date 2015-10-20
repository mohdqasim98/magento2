<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\SalesRule\Model\ResourceModel\Rule;

/**
 * @magentoDataFixture Magento/SalesRule/_files/rules.php
 * @magentoDataFixture Magento/SalesRule/_files/coupons.php
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider setValidationFilterDataProvider()
     * @param string $couponCode
     * @param array $expectedItems
     */
    public function testSetValidationFilter($couponCode, $expectedItems)
    {
        $collection = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create(
            'Magento\SalesRule\Model\ResourceModel\Rule\Collection'
        );
        $items = array_values($collection->setValidationFilter(1, 0, $couponCode)->getItems());

        $ids = [];
        foreach ($items as $key => $item) {
            $this->assertEquals($expectedItems[$key], $item->getName());
            if (in_array($item->getId(), $ids)) {
                $this->fail('Item should be unique in result collection');
            }
            $ids[] = $item->getId();
        }
    }

    public function setValidationFilterDataProvider()
    {
        return [
            'Check type COUPON' => ['coupon_code', ['#1', '#5']],
            'Check type NO_COUPON' => ['', ['#2', '#5']],
            'Check type COUPON_AUTO' => ['coupon_code_auto', ['#4', '#5']],
            'Check result with auto generated coupon' => ['autogenerated_3_1', ['#3', '#5']],
            'Check result with non actual previously generated coupon' => [
                'autogenerated_2_1',
                ['#2', '#5'],
            ],
            'Check result with wrong code' => ['wrong_code', ['#5']]
        ];
    }
}
