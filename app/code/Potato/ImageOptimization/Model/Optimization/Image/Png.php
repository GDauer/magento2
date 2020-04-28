<?php

namespace Potato\ImageOptimization\Model\Optimization\Image;

use Potato\ImageOptimization\Api\OptimizationInterface;

class Png implements OptimizationInterface
{
    const IMAGE_TYPE = 'image/png';
    
    /** @var Png\Pngquant  */
    protected $pngManager;

    /**
     * @param Png\Pngquant $pngManager
     */
    public function __construct(
        Png\Pngquant $pngManager
    ) {
        $this->pngManager = $pngManager;
    }
    
    /**
     * @param string $imagePath
     * @return $this
     * @throws \Exception
     */
    public function optimize($imagePath)
    {
        $this->pngManager->optimize($imagePath);
        return $this;
    }

    /**
     * @return array
     */
    public function isLibAvailable()
    {
        return $this->pngManager->isAvailable();
    }
}
