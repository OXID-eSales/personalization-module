<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Tracking\Modifiers;

use OxidEsales\PersonalizationModule\Application\Tracking\Helper\CategoryPathBuilder;
use OxidEsales\PersonalizationModule\Application\Tracking\ProductPreparation\ProductDataPreparator;
use OxidEsales\PersonalizationModule\Component\Tracking\ActivePageEntityInterface;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class responsible for adding data to entity.
 */
class EntityModifierByCurrentBasketAction
{
    /**
     * @var CategoryPathBuilder
     */
    private $categoryPathBuilder;

    /**
     * @var ProductDataPreparator
     */
    private $productDataPreparator;

    /**
     * @var ActivePageEntityInterface
     */
    private $pageEntity;

    /**
     * @param CategoryPathBuilder   $categoryPathBuilder
     * @param ProductDataPreparator $productDataPreparator
     */
    public function __construct($categoryPathBuilder, $productDataPreparator)
    {
        $this->categoryPathBuilder = $categoryPathBuilder;
        $this->productDataPreparator = $productDataPreparator;
    }

    /**
     * @param ActivePageEntityInterface $activePageEntity
     *
     * @return ActivePageEntityInterface
     */
    public function modifyEntity($activePageEntity)
    {
        $this->pageEntity = $activePageEntity;
        // get the last Call for special handling function "tobasket", "changebasket"
        if (($lastCall = Registry::getSession()->getVariable('aLastcall'))) {
            Registry::getSession()->deleteVariable('aLastcall');
        }

        // ADD To Basket and Remove from Basket
        if (is_array($lastCall) && count($lastCall)) {
            $callAction = key($lastCall);
            $callData = current($lastCall);

            switch ($callAction) {
                case 'changebasket':
                    $this->changeBasketAction($callData);
                    break;
                case 'tobasket':
                    $this->toBasketAction($callData);
                    break;
            }
        }

        return $this->pageEntity;
    }

    /**
     * @param array $callData
     */
    private function changeBasketAction($callData)
    {
        foreach ($callData as $itemId => $itemData) {
            $product = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            if ($product->load($itemData['aid'])) {
                if ($itemData['oldam'] > $itemData['am'] && $product->load($itemData['aid'])) {
                    //ECONDA FIX always use the main category
                    //$sPath = $this->_getDeepestCategoryPath( $product );
//                            $sPath = $this->_getBasketProductCatPath($product);
//                            $oEmos->removeFromBasket($this->_convProd2EmosItem($product, $sPath, ($itemData['oldam'] - $itemData['am'])));
//                            //$oEmos->appendPreScript($itemData['oldam'].'->'.$itemData['am'].':'.$product->load( $itemData['aid']));
                } elseif ($itemData['oldam'] < $itemData['am'] && $product->load($itemData['aid'])) {
                    $sPath = $this->categoryPathBuilder->getBasketProductCategoryPath($product);
                    $this->pageEntity->setProductToBasket($this->productDataPreparator->prepareForTransaction($product, $sPath, $itemData['am'] - $itemData['oldam']));
                }
            }
        }
    }

    /**
     * @param array $callData
     */
    private function toBasketAction($callData)
    {
        foreach ($callData as $itemId => $itemData) {
            // ECONDA FIX if there is a "add to basket" in the artcle list view, we do not have a product ID here
            $product = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
            if ($product->load($itemId)) {
                $path = $this->categoryPathBuilder->getBasketProductCategoryPath($product);
                $this->pageEntity->setProductToBasket($this->productDataPreparator->prepareForTransaction($product, $path, $itemData['am']));
            }
        }
    }
}
