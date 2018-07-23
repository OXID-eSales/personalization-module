<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

use OxidEsales\VisualCmsModule\Application\Model\VisualEditorShortcode;

use OxidEsales\Eshop\Core\Registry;

class oepersonalization_shortcode extends VisualEditorShortcode
{
    protected $_sTitle = 'OEPERSONALIZATION_VISUAL_EDITOR_SHORTCODE_ECONDA';

    protected $_sBackgroundColor = '#e74c3c';

    protected $_sIcon = 'fa-magic';

    public function install()
    {
        $this->setShortCode(basename(__FILE__, '.php'));

        $language = Registry::getLang();

        $this->setOptions(
            [
                'widgetId' => [
                    'type'        => 'text',
                    'label'       => $language->translateString('OEPERSONALIZATION_VISUAL_EDITOR_WIDGET_ECONDA_WIDGET_ID'),
                    'placeholder' => '6',
                    'value'       => '',
                    'preview'     => true
                ],
                'widgetTemplate' => [
                    'type'        => 'text',
                    'label'       => $language->translateString('OEPERSONALIZATION_VISUAL_EDITOR_WIDGET_ECONDA_WIDGET_TEMPLATE'),
                    'placeholder' => 'Component/views/vcms_banner.ejs.html',
                    'value'       => 'Component/views/vcms_banner.ejs.html',
                    'preview'     => false
                ],
                'chunkSize' => [
                    'type'        => 'text',
                    'label'       => $language->translateString('OEPERSONALIZATION_VISUAL_EDITOR_WIDGET_ECONDA_CHUNK_SIZE'),
                    'placeholder' => '4',
                    'value'       => '',
                    'preview'     => false
                ],
            ]
        );
    }

    public function parse($content = '', $parameters = [])
    {
        $output = '';

        if ($this->getViewConfig()->oePersonalizationEnableWidgets()) {

            $language = Registry::getLang();
            $lang_MORE_INFO = $language->translateString('MORE_INFO');

            $elementId = 'oepersonalization-widget-' . $parameters['widgetId'] . '-' . uniqid();
            $accountId = $this->getViewConfig()->oePersonalizationGetAccountId();

            $widgetId = $parameters['widgetId'];
            $widgetTemplate = $this->getViewConfig()->getModuleUrl(
                'oepersonalization',
                $parameters['widgetTemplate']
            );
            $chunkSize = $parameters['chunkSize'];

            $loadingText = $language->translateString('OEPERSONALIZATION_LOADING');
            $loadingImage = $this->getViewConfig()->getModuleUrl(
                'oepersonalization',
                'out/pictures/spinner.gif'
            );

            $output = <<<EOT
                <div id="{$elementId}">
                    <div class="text-center">
                        <img alt="{$loadingText}" src="{$loadingImage}" />
                    </div>
                </div>
                <script type="text/javascript">
                    var lang_MORE_INFO = '{$lang_MORE_INFO}';
                    var widget = new econda.recengine.Widget({
                        element: '#{$elementId}',
                        renderer: {
                            type: 'template',
                            uri: '{$widgetTemplate}'
                        },
                        accountId: '{$accountId}',
                        id: '{$widgetId}',
                        chunkSize: parseInt('{$chunkSize}', 10)
                    });
                    widget.render();
                </script>
EOT;
        }

        return $output;
    }
}
