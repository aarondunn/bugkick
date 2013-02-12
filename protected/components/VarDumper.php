<?php
/**
 * VarDumper
 *
 * @author f0t0n
 */
 class VarDumper extends CVarDumper {
	 
     public static function dd($var) {
         CVarDumper::dump($var, 100, true);
     }
 }