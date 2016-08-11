<?php
/**
 * 2016 inTarget
 * @author    inTarget Team <https://intarget.ru/>
 * @copyright 2015 inTarget
 * @license   GNU General Public License, version 3
 */

class ControllerModuleIntarget extends Controller {
    public function index() {
        return html_entity_decode($this->config->get('intarget_code'), ENT_QUOTES, 'UTF-8');
    }
}
