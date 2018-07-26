<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\PersonalizationModule\Component\Tracking;

class PageView extends \Econda\Tracking\PageView
{
    /**
     * @inheritdoc
     */
    public function getAsJavaScriptNode() {
        $globalData = $this->getGlobalDataLayer();
        $requestData = $this->getRequestDataLayer();

        $html = array(
            '<script type="text/javascript">',
            '  if(typeof window.emos3 !== "object" || window.emos3 === null) { window.emos3 = {}; }',
            '  (function(emos) { ',
            '      (typeof emos.defaults === "object" && typeof emos.defaults !== null) || (emos.defaults = {});',
            '      (typeof emos.stored === "object" && typeof emos.stored.push === "function") || (emos.stored = []);',
            '      (typeof emos.send === "function") || (emos.send = function(p){this.stored.push(p)});',
            '      var pageDefaults = ' . $this->jsonEncode($globalData) . ';',
            '      for(var p in pageDefaults) { emos.defaults[p] = pageDefaults[p]; }',
            '      var requestData = ' . ($requestData ? $this->jsonEncode($requestData) : '{}') . ';',
            '      if(econda.privacyprotection.getPermissionsFromLocalStorage().profile.state === "DENY") {',
            '          if(requestData.login) {',
            '              requestData.login = [econda.privacyprotection.emptyIfNotProfileOptIn(requestData.login[0]), requestData.login[1]]',
            '          }',
            '          if(requestData.register) {',
            '              requestData.register = [econda.privacyprotection.emptyIfNotProfileOptIn(requestData.register[0]), requestData.register[1]]',
            '          }',
            '          if(requestData.billing) {',
            '              requestData.billing = [',
            '                  econda.privacyprotection.anonymiseIfNotProfileOptIn(requestData.billing[0]),',
            '                  econda.privacyprotection.emptyIfNotProfileOptIn(requestData.billing[1]),',
            '                  requestData.billing[2],',
            '                  requestData.billing[3]',
            '              ]',
            '          }',
            '      }',
            '      if (emosProps) {',
            '          Object.assign(requestData, emosProps);',
            '      }',
            '      emos.send(requestData);',
            '  })(window.emos3);',
            '</script>',
        );
        return implode("\n", $html) . "\n";
    }
}
