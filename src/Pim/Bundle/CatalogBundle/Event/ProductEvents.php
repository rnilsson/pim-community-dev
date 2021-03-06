<?php

namespace Pim\Bundle\CatalogBundle\Event;

/**
 * Catalog product events
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
final class ProductEvents
{
    /**
     * This event is thrown each time a product is created in the system.
     *
     * The event listener receives an
     * Pim\Bundle\CatalogBundle\Event\ProductEvent instance.
     *
     * @staticvar string
     */
    const CREATE = 'pim_catalog.create_product';

    /**
     * This event is thrown each time a product value is created in the system.
     *
     * The event listener receives an
     * Pim\Bundle\CatalogBundle\Event\ProductValueEvent instance.
     *
     * @staticvar string
     */
    const CREATE_VALUE = 'pim_catalog.create_product_value';

    /**
     * This event is thrown before a product is removed.
     *
     * The event listener receives an
     * Symfony\Component\EventDispatcher\GenericEvent instance.
     *
     * @staticvar string
     */
    const PRE_REMOVE = 'pim_catalog.pre_remove.product';

    /**
     * This event is thrown after a product has been removed.
     *
     * The event listener receives an
     * Symfony\Component\EventDispatcher\GenericEvent instance.
     *
     * @staticvar string
     */
    const POST_REMOVE = 'pim_catalog.post_remove.product';

    /**
     * This event is thrown before some products are removed
     *
     * The event listener receives an
     * Symfony\Component\EventDispatcher\GenericEvent instance.
     *
     * @staticvar string
     */
    const PRE_MASS_REMOVE = 'pim_catalog.pre_mass_remove.product';

    /**
     * This event is thrown after some products have been removed.
     *
     * The event listener receives an
     * Symfony\Component\EventDispatcher\GenericEvent instance.
     *
     * @staticvar string
     */
    const POST_MASS_REMOVE = 'pim_catalog.post_mass_remove.product';
}
