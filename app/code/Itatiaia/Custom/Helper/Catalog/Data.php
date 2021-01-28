<?php
namespace Itatiaia\Custom\Helper\Catalog;

class Data extends \Magento\Catalog\Helper\Data
{
    /**
     * Return current category path or get it from current category
     * and creating array of categories|product paths for breadcrumbs
     *
     * @return array
     */
    public function getBreadcrumbPath()
    {
        $isProductView = $this->getProduct();
        // \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface')->info('CRUMBS V2', [$isProductView]);

        if (!$this->_categoryPath) {
            $path = [];
            $category = $this->getCategory();
            if ($category) {
                $pathInStore = $category->getPathInStore();
                $pathIds = array_reverse(explode(',', $pathInStore));

                $categories = $category->getParentCategories();

                // add category path breadcrumb
                foreach ($pathIds as $categoryId) {
                    if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
                        $path['category' . $categoryId] = [
                            'label' => $categories[$categoryId]->getName(),
                            'link' => $this->_isCategoryLink($categoryId) ? $categories[$categoryId]->getUrl() : ''
                        ];
                    }
                }
            }

            // custom breadcrumbs para produtos acessados diretamente
            if(!count($path) && $this->getProduct()){
                $categoryIds = $this->getProduct()->getCategoryIds();
                // \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface')->info('CRUMBS CATEGORY IDS', [$categoryIds]);

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $lastLevelCategory = null;
                $lastLevel = 0;
                foreach($categoryIds as $cid){
                    $category = $objectManager->get('\Magento\Catalog\Model\CategoryFactory')->create()->load($cid);
                    if((int)$category->getLevel() > $lastLevel){
                        $lastLevel = (int)$category->getLevel();
                        $lastLevelCategory = $category;
                    }
                }

                if($lastLevelCategory){
                    $pathInStore = $lastLevelCategory->getPathInStore();
                    $pathIds = array_reverse(explode(',', $pathInStore));

                    $categories = $lastLevelCategory->getParentCategories();

                    // add category path breadcrumb
                    foreach ($pathIds as $categoryId) {
                        if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
                            $path['category' . $categoryId] = [
                                'label' => $categories[$categoryId]->getName(),
                                'link' => $this->_isCategoryLink($categoryId) ? $categories[$categoryId]->getUrl() : ''
                            ];
                        }
                    }
                }

//                \Magento\Framework\App\ObjectManager::getInstance()->get('\Psr\Log\LoggerInterface')->info('CRUMBS CATEGORY LAST', [$lastLevelCategory->getName()]);
            }

            if ($this->getProduct()) {
                $path['product'] = [
                    'label' => $this->getProduct()->getName(),
                    'link' => $this->getProduct()->getProductUrl()
                ];
            }

            $this->_categoryPath = $path;
        }

        return $this->_categoryPath;
    }

    public function getProductMainCategory($_product)
    {
        $categoryIds = $_product->getCategoryIds();

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $lastLevelCategory = null;
        $lastLevel = 0;
        foreach($categoryIds as $cid){
            $category = $objectManager->get('\Magento\Catalog\Model\CategoryFactory')->create()->load($cid);
            if((int)$category->getLevel() > $lastLevel){
                $lastLevel = (int)$category->getLevel();
                $lastLevelCategory = $category;
            }
        }

        if($lastLevelCategory){
            $pathInStore = $lastLevelCategory->getPathInStore();
            $pathIds = array_reverse(explode(',', $pathInStore));

            $categories = $lastLevelCategory->getParentCategories();

            // add category path breadcrumb
            foreach ($pathIds as $categoryId) {
                if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
                    return $categories[$categoryId];
                }
            }
        }

        return null;
    }
}
