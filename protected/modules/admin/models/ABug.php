<?php
/**
 * ABug
 *
 * @author f0t0n
 */
class ABug extends Bug {

    public function defaultScope() {
        $scope = parent::defaultScope();
        $scope['condition'] = '';
        return $scope;
    }
}