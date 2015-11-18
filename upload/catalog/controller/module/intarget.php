<?php
class ControllerModuleIntarget extends Controller {
    public function index() {
        return html_entity_decode($this->config->get('intarget_code'), ENT_QUOTES, 'UTF-8');
    }
}
