<?php

namespace Pim\Bundle\CatalogBundle\Factory;

use Symfony\Component\HttpFoundation\File\File;

/**
 * A Media object factory
 *
 * @author    Gildas Quemener <gildas@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class MediaFactory
{
    /** @var string */
    protected $mediaClass;

    /**
     * @param string $mediaClass
     */
    public function __construct($mediaClass)
    {
        $this->mediaClass = $mediaClass;
    }

    /**
     * @param File|null $file
     *
     * @return Media
     */
    public function createMedia(File $file = null)
    {
        $media = new $this->mediaClass();
        $media->setFile($file);

        return $media;
    }
}