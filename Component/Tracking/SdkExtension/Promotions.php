<?php
/**
 * Created by LoberonEE.
 * Autor: Tobias Matthaiou <tm@loberon.de>
 * Date: 10.12.19
 * Time: 17:09
 */

namespace OxidEsales\PersonalizationModule\Component\Tracking\SdkExtension;

use Econda\Tracking\TrackingItemInterface;
use Econda\Util\BaseObject;
use OxidEsales\EshopCommunity\Internal\Application\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Common\Database\QueryBuilderFactoryInterface;

/**
 * Class Promotions
 * @package OxidEsales\PersonalizationModule\Component\Tracking\SdkExtension
 * @see https://docs.econda.de/de/MONDE/data-services/data-model-management/promotions+und+gutscheine.html
 */
class Promotions extends BaseObject implements TrackingItemInterface
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @inheritDoc
     * @return array
     */
    public function getTrackingData()
    {
        $data = array_map([$this, 'convert'], $this->data);

        return [
            'promotions' => $data
        ];
    }

    /**
     * @param array $promotion
     */
    protected function convert($promotion)
    {
        $title = $this->getVoucherserieTitle($promotion['voucherId']);

        return $this->econdaFormat(
            $title,
            $promotion['voucherCode'],
            $promotion['discount']
        );
    }

    /**
     * @param string(64) $action Gutschein- oder Marketingaktion
     * @param string(64) $code   Eingelöster Gutscheincode
     * @param float      $wert   Eingelöster Gutscheinwert
     */
    protected function econdaFormat($action, $code, $wert)
    {
        $action = $this->truncate($action, 64);
        $code   = $this->truncate($code, 64);

        return [$action, $code, $wert];
    }

    /**
     * @param string  $string
     * @param integer $length
     * @return string
     */
    protected function truncate($string, $length)
    {
        if (strlen($string) > $length) {
            $string = substr($string, 0, $length - 3) . '...';
        }

        return $string;
    }

    /**
     * @param string $voucherId OXID of Voucher
     * @return string
     */
    protected function getVoucherserieTitle($voucherId)
    {
        $container = ContainerFactory::getInstance()->getContainer();

        $qb = $container->get(QueryBuilderFactoryInterface::class)->create()
            ->select('oxserienr')
            ->from(getViewName('oxvoucherseries'), 'vs')
            ->join('vs', 'oxvouchers', 'vo', 'vo.OXVOUCHERSERIEID = vs.OXID')
            ->where('vo.OXID = :oxid')
            ->setParameter('oxid', $voucherId);

        $name = $qb->execute()->fetchColumn(0);

        if (empty($name)) {
            return 'Voucher name no longer exists in the database';
        }

        return $name;
    }
}
