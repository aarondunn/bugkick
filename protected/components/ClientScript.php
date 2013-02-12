<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ClientScript
 *
 * @author f0t0n
 */
class ClientScript extends CClientScript {
    public function registerScriptFile($url, $position = self::POS_END) {
        return parent::registerScriptFile($url, $position);
    }
}