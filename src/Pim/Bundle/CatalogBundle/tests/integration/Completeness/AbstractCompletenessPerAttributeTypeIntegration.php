<?php

namespace Pim\Bundle\CatalogBundle\tests\integration\Completeness;

use Pim\Component\Catalog\Model\ProductInterface;

/**
 * Abstract class to check that the completeness has been well calculated for each attribute type of the PIM.
 *
 * We test from the minimal catalog that contains only one channel with one activated locale.
 * For each attribute type, we create an attribute. Then, we create a family where the attribute is required.
 * We create two products of this family, one with the required attribute filled in, the other without.
 * Finally we test the completeness calculation of those two products.
 *
 * @author    Samir Boulil <samir.boulil@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class AbstractCompletenessPerAttributeTypeIntegration extends AbstractCompletenessIntegration
{
    /**
     * Here, the identifier and the attribute should be filled in.
     * Which means, there should be 0 missing, and 2 required.
     *
     * @param ProductInterface $product
     */
    protected function assertComplete(ProductInterface $product)
    {
        $completenesses = $product->getCompletenesses()->toArray();
        $this->assertNotNull($completenesses);
        $this->assertCount(1, $completenesses);

        $completeness = current($completenesses);

        $this->assertNotNull($completeness->getLocale());
        $this->assertEquals('en_US', $completeness->getLocale()->getCode());
        $this->assertNotNull($completeness->getChannel());
        $this->assertEquals('ecommerce', $completeness->getChannel()->getCode());
        $this->assertEquals(100, $completeness->getRatio());
        $this->assertEquals(2, $completeness->getRequiredCount());
        $this->assertEquals(0, $completeness->getMissingCount());
    }

    /**
     * Here, only the identifier should be filled in.
     * Which means, there should be 1 missing, and 2 required.
     *
     * @param ProductInterface $product
     */
    protected function assertNotComplete(ProductInterface $product)
    {
        $completenesses = $product->getCompletenesses()->toArray();
        $this->assertNotNull($completenesses);
        $this->assertCount(1, $completenesses);

        $completeness = current($completenesses);

        $this->assertNotNull($completeness->getLocale());
        $this->assertEquals('en_US', $completeness->getLocale()->getCode());
        $this->assertNotNull($completeness->getChannel());
        $this->assertEquals('ecommerce', $completeness->getChannel()->getCode());
        $this->assertEquals(50, $completeness->getRatio());
        $this->assertEquals(2, $completeness->getRequiredCount());
        $this->assertEquals(1, $completeness->getMissingCount());
    }
}
