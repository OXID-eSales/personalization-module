<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Export;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\PersonalizationModule\Component\Export\ColumnNameVariationsGenerator;

/**
 * Loads and appends additional data to given categories array for export.
 */
class CategoryDataPreparator
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * @var ColumnNameVariationsGenerator
     */
    private $columnNameVariationsGenerator;

    /**
     * @param CategoryRepository            $categoryRepository
     * @param ColumnNameVariationsGenerator $columnNameVariationsGenerator
     */
    public function __construct($categoryRepository, $columnNameVariationsGenerator)
    {
        $this->categoryRepository = $categoryRepository;
        $this->columnNameVariationsGenerator = $columnNameVariationsGenerator;
    }

    /**
     * @param array $categoriesIds
     *
     * @return array
     */
    public function prepareDataForExport($categoriesIds): array
    {
        $dataToExport = [];

        $dataToExport = $this->addHeaders($dataToExport);

        $languages = Registry::getLang()->getLanguageArray(null, true, true);

        if (empty($categoriesIds)) {
            $categories = $this->categoryRepository->getAllCategories();
        } else {
            $categories = $this->categoryRepository->findCategoriesByIds($categoriesIds);
        }
        foreach ($categories as $category) {
            $parentId = "ROOT";
            $parent = $category->getParentCategory();
            if ($parent != null) {
                $parentId = $parent->getId();
            }

            $categoryTitles = [];
            foreach ($languages as $language) {
                $category->loadInLang($language->id, $category->getId());
                $categoryTitles[] = $category->getTitle();
            }
            $categoryDataToExport = array_merge(
                [
                    $category->getId(),
                    $parentId,
                ],
                $categoryTitles
            );

            $dataToExport[] = $categoryDataToExport;
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
            ['ID', 'ParentId'],
            $this->columnNameVariationsGenerator->generateNames('Name')
        );

        return [array_merge($header, $dataToExport)];
    }
}
