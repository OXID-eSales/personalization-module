<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Export;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Component\Export\ColumnNameVariationsGenerator;

/**
 * Loads and appends additional data to given products array for export.
 */
class ProductDataPreparator
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ColumnNameVariationsGenerator
     */
    private $columnNameVariationsGenerator;

    /**
     * @param ProductRepository             $productRepository
     * @param ColumnNameVariationsGenerator $columnNameVariationsGenerator
     */
    public function __construct($productRepository, $columnNameVariationsGenerator)
    {
        $this->productRepository = $productRepository;
        $this->columnNameVariationsGenerator = $columnNameVariationsGenerator;
    }

    /**
     * @param array` $productsIdsForExport
     *
     * @return array
     */
    public function appendDataForExport($productsIdsForExport): array
    {
        $dataToExport = [];

        $dataToExport = $this->addHeaders($dataToExport);

        foreach ($productsIdsForExport as $productData) {
            $product = oxNew(Article::class);
            if ($product->load($productData['OXID'])) {
                $articleForDescription = $product;

                if ($product->isVariant()) {
                    $articleForDescription = $product->getParentArticle();
                }

                $oldPrice = 0;
                if ($product->getTPrice()) {
                    $oldPrice = $product->getTPrice()->getBruttoPrice();
                }
                $productDataToExport = [
                    (isset($product->oxarticles__oxartnum->value) && $product->oxarticles__oxartnum->value) ? $product->oxarticles__oxartnum->value : $product->getId(),
                ];
                $productDataToExport = array_merge(
                    $productDataToExport,
                    $this->productRepository->findTitles($articleForDescription->oxarticles__oxid->value),
                    $this->productRepository->findDescriptions($articleForDescription->oxarticles__oxid->value),
                    $this->productRepository->findLinks($product),
                    [
                        $product->getPictureUrl(1),
                        $product->getPrice()->getBruttoPrice(),
                        $oldPrice,
                        '0',
                        $product->oxarticles__oxstock->value,
                        $product->oxarticles__oxean->value,
                        ($product->getManufacturer()) ? $product->getManufacturer()->oxmanufacturers__oxtitle->value : '',
                    ],
                    $product->getCategoryIds()
                );


                $dataToExport[] = $productDataToExport;
            }
        }
        return $dataToExport;
    }

    /**
     * @param array $dataToExport
     *
     * @return array
     */
    private function addHeaders($dataToExport)
    {
        $header = array_merge(
            ['ID'],
            $this->columnNameVariationsGenerator->generateNames('Name'),
            $this->columnNameVariationsGenerator->generateNames('Description'),
            $this->columnNameVariationsGenerator->generateNames('ProductUrl'),
            [
                'ImageUrl',
                'Price',
                'OldPrice',
                'New',
                'Stock',
                'EAN',
                'Brand',
                'ProductCategory'
            ]
        );

        return [array_merge($header, $dataToExport)];
    }
}
