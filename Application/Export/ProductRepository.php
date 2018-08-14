<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Application\Export;

use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\TableViewNameGenerator;
use OxidEsales\PersonalizationModule\Application\Export\Helper\SqlGenerator;

/**
 * Class responsible for retrieving product related data for export.
 */
class ProductRepository
{
    /**
     * @var SqlGenerator
     */
    private $sqlFenerator;

    /**
     * @param SqlGenerator $sqlGenerator
     */
    public function __construct($sqlGenerator)
    {
        $this->sqlFenerator = $sqlGenerator;
    }

    /**
     * @param int   $exportLanguage
     * @param bool  $shouldExportVariants
     * @param array $categoriesIds
     * @param int   $minimumQuantityInStock
     *
     * @return array
     */
    public function findProductsToExport($exportLanguage, $shouldExportVariants, $categoriesIds, $minimumQuantityInStock): array
    {
        $product = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        $product->setLanguage($exportLanguage);

        $objectToCategoryTableName = Registry::get(TableViewNameGenerator::class)->getViewName('oxobject2category', $exportLanguage);
        $articleTableName = Registry::get(TableViewNameGenerator::class)->getViewName("oxarticles", $exportLanguage);

        $query = "select {$articleTableName}.oxid, {$articleTableName}.oxparentid from {$articleTableName}, {$objectToCategoryTableName} as oxobject2category where ";
        $query .= $product->getSqlActiveSnippet();

        if (!$shouldExportVariants) {
            $query .= " and {$articleTableName}.oxid = oxobject2category.oxobjectid and {$articleTableName}.oxparentid = '' ";
        } else {
            $query .= " and ( {$articleTableName}.oxid = oxobject2category.oxobjectid or {$articleTableName}.oxparentid = oxobject2category.oxobjectid ) ";
        }

        if (!is_null($categoriesIds)) {
            $categoriesIdsQuery = $this->sqlFenerator->makeCategoriesQueryPart($categoriesIds);
            $query .= $categoriesIdsQuery;
        }

        $database = DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

        $query .= " and {$articleTableName}.oxstock >= " . $database->quote($minimumQuantityInStock);
        $query .= " group by {$articleTableName}.oxid";
        $resultSet = $database->select($query);

        $allResults = $resultSet->fetchAll();

        return $allResults;
    }

    /**
     * Get the title of a product in all available active languages.
     *
     * @param string $id
     *
     * @return array
     */
    public function findTitles($id): array
    {
        $titles = [];

        $viewNameGenerator = Registry::get(TableViewNameGenerator::class);

        $sql = "SELECT oxtitle FROM :table WHERE oxid=':oxid'";

        $languages = Registry::getLang()->getLanguageArray(null, true, true);
        foreach ($languages as $language) {
            $titles[] = DatabaseProvider::getDb()->getOne(
                strtr(
                    $sql,
                    [
                        ':table' => $viewNameGenerator->getViewName('oxarticles', $language->id),
                        ':oxid' => $id
                    ]
                )
            );
        }

        return $titles;
    }

    /**
     * Get the description of a product in all available active languages.
     *
     * @param string $id
     *
     * @return array
     */
    public function findDescriptions($id): array
    {
        $descriptions = [];

        $viewNameGenerator = Registry::get(TableViewNameGenerator::class);

        $sql = "SELECT oxshortdesc FROM :table WHERE oxid=':oxid'";

        $languages = Registry::getLang()->getLanguageArray(null, true, true);
        foreach ($languages as $language) {
            $descriptions[] =
                DatabaseProvider::getDb()->getOne(
                    strtr($sql, [
                        ':table' => $viewNameGenerator->getViewName('oxarticles', $language->id),
                        ':oxid' => $id
                    ])
                );
        }

        return $descriptions;
    }

    /**
     * Get the links of a article in all available active languages.
     *
     * @param Article $product
     *
     * @return array
     */
    public function findLinks($product): array
    {
        $links = [];

        $languages = Registry::getLang()->getLanguageArray(null, true, true);
        foreach ($languages as $language) {
            $links[] = $product->getLink($language->id);
        }

        return $links;
    }
}
