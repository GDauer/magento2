<?php

namespace Itatiaia\Custom\Block\Product\View;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Product\Gallery\ImagesConfigFactoryInterface;
use Magento\Catalog\Model\Product\Image\UrlBuilder;
use Magento\Framework\Data\Collection;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Stdlib\ArrayUtils;
use Scandi\MagicZoom\Helper\Config;

class Gallery extends \Magento\Catalog\Block\Product\View\Gallery
{
    /**
     * @var Config
     */
    protected $_config;

    /**
     * @var \Magento\Framework\Config\View
     */
    protected $configView;

    /**
     * @var EncoderInterface
     */
    protected $jsonEncoder;

    /**
     * @var array
     */
    private $galleryImagesConfig;

    /**
     * @var ImagesConfigFactoryInterface
     */
    private $galleryImagesConfigFactory;

    /**
     * @var UrlBuilder
     */
    private $imageUrlBuilder;

    public function __construct(
        Context $context,
        ArrayUtils $arrayUtils,
        EncoderInterface $jsonEncoder,
        array $data = [],
        ImagesConfigFactoryInterface $imagesConfigFactory = null,
        array $galleryImagesConfig = [],
        UrlBuilder $urlBuilder = null,
        Config $config
    ) {
        parent::__construct($context, $arrayUtils, $jsonEncoder, $data, $imagesConfigFactory, $galleryImagesConfig, $urlBuilder);
        $this->jsonEncoder = $jsonEncoder;
        $this->galleryImagesConfigFactory = $imagesConfigFactory ?: ObjectManager::getInstance()
            ->get(ImagesConfigFactoryInterface::class);
        $this->galleryImagesConfig = $galleryImagesConfig;
        $this->imageUrlBuilder = $urlBuilder ?? ObjectManager::getInstance()->get(UrlBuilder::class);
        $this->_config = $config;
    }

    /**
     * Retrieve collection of gallery images
     *
     * @return Collection
     */
    public function getGalleryImages()
    {
        $product = $this->getProduct();
        $images = $product->getMediaGalleryImages();
        if (!$images instanceof \Magento\Framework\Data\Collection) {
            return $images;
        }

        foreach ($images as $image) {
            $galleryImagesConfig = $this->getGalleryImagesConfig()->getItems();
            foreach ($galleryImagesConfig as $imageConfig) {
                $image->setData(
                    $imageConfig->getData('data_object_key'),
                    $this->imageUrlBuilder->getUrl($image->getFile(), $imageConfig['image_id'])
                );
            }

            // $values = [
            //     'small_image_url',
            //     'medium_image_url',
            //     'large_image_url'
            // ];
            
            // $check = function($value) use (&$image)
            // {
            //     if (!$image->getData($value))
            //     {
            //         $image->setData(
            //             $value,
            //             $image->getData('url')
            //         );
            //     }
            // };

            // array_walk($values, $check);
        }

        return $images;
    }

    /**
     * Return magnifier options
     *
     * @return string
     */
    public function getMagnifier()
    {
        return $this->jsonEncoder->encode($this->getVar('magnifier'));
    }

    /**
     * Return breakpoints options
     *
     * @return string
     */
    public function getBreakpoints()
    {
        return $this->jsonEncoder->encode($this->getVar('breakpoints'));
    }

    // /**
    //  * Retrieve product images in JSON format
    //  *
    //  * @return string
    //  */
    // public function getGalleryImagesJson()
    // {
    //     $imagesItems = [];
    //     /** @var DataObject $image */
    //     foreach ($this->getGalleryImages() as $image) {
    //         $imageItem = new DataObject(
    //             [
    //                 'thumb' => $image->getData('small_image_url'),
    //                 'img' => $image->getData('medium_image_url'),
    //                 'full' => $image->getData('large_image_url'),
    //                 'caption' => ($image->getLabel() ?: $this->getProduct()->getName()),
    //                 'position' => $image->getData('position'),
    //                 'isMain'   => $this->isMainImage($image),
    //                 'type' => str_replace('external-', '', $image->getMediaType()),
    //                 'videoUrl' => $image->getVideoUrl(),
    //             ]
    //         );
    //         foreach ($this->getGalleryImagesConfig()->getItems() as $imageConfig) {
    //             $imageItem->setData(
    //                 $imageConfig->getData('json_object_key'),
    //                 $image->getData($imageConfig->getData('data_object_key'))
    //             );
    //         }
    //         $imagesItems[] = $imageItem->toArray();
    //     }
    //     if (empty($imagesItems)) {
    //         $imagesItems[] = [
    //             'thumb' => $this->_imageHelper->getDefaultPlaceholderUrl('thumbnail'),
    //             'img' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
    //             'full' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
    //             'caption' => '',
    //             'position' => '0',
    //             'isMain' => true,
    //             'type' => 'image',
    //             'videoUrl' => null,
    //         ];
    //     }
    //     return json_encode($imagesItems);
    // }

    /**
     * Retrieve product images in JSON format
     *
     * @return string
     */
    public function getGalleryImagesJson()
    {
        $imagesItems = [];
        foreach ($this->getGalleryImages() as $image) {
            $imagesItems[] = [
                'thumb' => $image->getData('small_image_url'),
                'img' => $image->getData('medium_image_url'),
                'full' => $image->getData('large_image_url'),
                'caption' => $image->getLabel(),
                'position' => $image->getPosition(),
                'isMain' => $this->isMainImage($image),
                'mediaType' => $image->getMediaType(),
                'videoUrl' => $image->getVideoUrl()
            ];
        }
        if (empty($imagesItems)) {
            $imagesItems[] = [
                'thumb' => $this->_imageHelper->getDefaultPlaceholderUrl('thumbnail'),
                'img' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'full' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'caption' => '',
                'position' => '0',
                'isMain' => true,
                'mediaType' => 'image',
                'videoUrl' => ''
            ];
        }
        return json_encode($imagesItems);
    }

    /**
     * Retrieve gallery url
     *
     * @param null|\Magento\Framework\DataObject $image
     * @return string
     */
    public function getGalleryUrl($image = null)
    {
        $params = ['id' => $this->getProduct()->getId()];
        if ($image) {
            $params['image'] = $image->getValueId();
        }
        return $this->getUrl('catalog/product/gallery', $params);
    }

    /**
     * Is product main image
     *
     * @param \Magento\Framework\DataObject $image
     * @return bool
     */
    public function isMainImage($image)
    {
        $product = $this->getProduct();
        return $product->getImage() == $image->getFile();
    }

    /**
     * Returns image attribute
     *
     * @param string $imageId
     * @param string $attributeName
     * @param string $default
     * @return string
     */
    public function getImageAttribute($imageId, $attributeName, $default = null)
    {
        $attributes = $this->getConfigView()
                ->getMediaAttributes('Magento_Catalog', Image::MEDIA_TYPE_CONFIG_NODE, $imageId);
        return $attributes[$attributeName] ?? $default;
    }

    /**
     * @param      $path
     * @param bool $asBoolString
     *
     * @return mixed|string
     */
    public function getConfigVal($path, $asBoolString = false)
    {
        $value = $this->_config->getConfigVal($path);
        if ($asBoolString) {
            return ($value) ? 'true' : 'false';
        }
        return $value;
    }

    /**
     * Retrieve config view
     *
     * @return \Magento\Framework\Config\View
     */
    private function getConfigView()
    {
        if (!$this->configView) {
            $this->configView = $this->_viewConfig->getViewConfig();
        }
        return $this->configView;
    }

    /**
     * Returns image gallery config object
     *
     * @return Collection
     */
    private function getGalleryImagesConfig()
    {
        if (false === $this->hasData('gallery_images_config')) {
            $galleryImageConfig = $this->galleryImagesConfigFactory->create($this->galleryImagesConfig);
            $this->setData('gallery_images_config', $galleryImageConfig);
        }

        return $this->getData('gallery_images_config');
    }
}
