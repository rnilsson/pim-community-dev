<?php

namespace Pim\Bundle\CatalogBundle\Updater\Setter;

use Pim\Bundle\CatalogBundle\Builder\ProductBuilderInterface;
use Pim\Bundle\CatalogBundle\Model\AttributeInterface;
use Pim\Bundle\CatalogBundle\Model\ProductInterface;
use Pim\Bundle\CatalogBundle\Exception\InvalidArgumentException;
use Pim\Bundle\CatalogBundle\Validator\AttributeValidatorHelper;

/**
 * Sets a date value in many products
 *
 * @author    Olivier Soulet <olivier.soulet@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class DateValueSetter extends AbstractValueSetter
{
    /**
     * @param ProductBuilderInterface  $productBuilder
     * @param AttributeValidatorHelper $attrValidatorHelper
     * @param array                    $supportedTypes
     */
    public function __construct(
        ProductBuilderInterface $productBuilder,
        AttributeValidatorHelper $attrValidatorHelper,
        array $supportedTypes
    ) {
        parent::__construct($productBuilder, $attrValidatorHelper);
        $this->supportedTypes = $supportedTypes;
    }

    /**
     * {@inheritdoc}
     */
    public function setValue(array $products, AttributeInterface $attribute, $data, $locale = null, $scope = null)
    {
        $this->checkLocaleAndScope($attribute, $locale, $scope, 'date');
        $data = $this->formatData($attribute, $data);

        foreach ($products as $product) {
            $this->setData($attribute, $product, $data, $locale, $scope);
        }
    }

    /**
     * Format data
     *
     * @param AttributeInterface $attribute
     * @param mixed              $data
     *
     * @return string
     */
    protected function formatData(AttributeInterface $attribute, $data)
    {
        if ($data instanceof \DateTime) {
            $data = $data->format('Y-m-d');
        } elseif (is_string($data)) {
            $this->validateDateFormat($attribute, $data);
        } elseif (null !== $data) {
            throw InvalidArgumentException::expected(
                $attribute->getCode(),
                'datetime or string',
                gettype($data),
                'setter',
                'date',
                $data
            );
        }

        return $data;
    }

    /**
     * Set the data into the product value
     *
     * @param AttributeInterface $attribute
     * @param ProductInterface   $product
     * @param mixed              $data
     * @param string             $locale
     * @param string             $scope
     */
    protected function setData(AttributeInterface $attribute, ProductInterface $product, $data, $locale, $scope)
    {
        $value = $product->getValue($attribute->getCode(), $locale, $scope);
        if (null === $value) {
            $value = $this->productBuilder->addProductValue($product, $attribute, $locale, $scope);
        }

        if (null !== $data) {
            $data = new \DateTime($data);
        }

        $value->setData($data);
    }

    /**
     * @param AttributeInterface $attribute
     * @param string             $data
     */
    protected function validateDateFormat(AttributeInterface $attribute, $data)
    {
        $dateValues = explode('-', $data);

        if (
            count($dateValues) !== 3
            || (!is_numeric($dateValues[0]) || !is_numeric($dateValues[1]) || !is_numeric($dateValues[2]))
            || !checkdate($dateValues[1], $dateValues[2], $dateValues[0])
        ) {
            throw InvalidArgumentException::expected(
                $attribute->getCode(),
                'a string with the format yyyy-mm-dd',
                'setter',
                'date',
                gettype($data),
                $data
            );
        }
    }
}
