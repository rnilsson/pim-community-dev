<?php

namespace Pim\Bundle\CatalogBundle\Validator\Mapping;

use Doctrine\Common\Util\ClassUtils;
use Pim\Bundle\CatalogBundle\Model\ProductValueInterface;
use Pim\Bundle\CatalogBundle\Model\AttributeInterface;
use Pim\Bundle\CatalogBundle\Validator\ConstraintGuesserInterface;
use Symfony\Component\Validator\MetadataFactoryInterface;
use Symfony\Component\Validator\Exception\NoSuchMetadataException;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraint;

/**
 * Create a ClassMetadata instance for an ProductValueInterface instance
 * Constraints are guessed from the value's attribute
 *
 * @author    Gildas Quemener <gildas@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductValueMetadataFactory implements MetadataFactoryInterface
{
    /** @var ConstraintGuesserInterface */
    protected $guesser;

    /** @var ClassMetadataFactory */
    protected $factory;

    /**
     * Constructor
     *
     * @param ConstraintGuesserInterface $guesser
     * @param ClassMetadataFactory|null  $factory
     */
    public function __construct(ConstraintGuesserInterface $guesser, ClassMetadataFactory $factory = null)
    {
        $this->guesser = $guesser;
        $this->factory = $factory ?: new ClassMetadataFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataFor($value)
    {
        if (!$value instanceof ProductValueInterface) {
            throw new NoSuchMetadataException();
        }

        $metadata = $this->createMetadata($value);

        return $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function hasMetadataFor($value)
    {
        if ($value instanceof ProductValueInterface) {
            return true;
        }

        return false;
    }

    /**
     * @param ProductValueInterface $value
     *
     * @return ClassMetadata
     */
    protected function createMetadata(ProductValueInterface $value)
    {
        $class = ClassUtils::getClass($value);
        $metadata = $this->factory->createMetadata($class);
        $attribute = $value->getAttribute();

        foreach ($this->guesser->guessConstraints($attribute) as $constraint) {
            $this->addConstraint($metadata, $constraint, $attribute);
        }

        return $metadata;
    }

    /**
     * @param ClassMetadata      $metadata
     * @param Constraint         $constraint
     * @param AttributeInterface $attribute
     */
    protected function addConstraint(ClassMetadata $metadata, Constraint $constraint, AttributeInterface $attribute)
    {
        $target = $constraint->getTargets();
        if (is_array($target)) {
            throw new \LogicException('No support provided for constraint on many targets');
        } elseif (Constraint::PROPERTY_CONSTRAINT === $target) {
            $metadata->addPropertyConstraint($attribute->getBackendType(), $constraint);
        } elseif (Constraint::CLASS_CONSTRAINT === $target) {
            $metadata->addConstraint($constraint);
        }
    }
}
