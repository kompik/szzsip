<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\helpers;

/**
 * Description of TimeHelper
 *
 * @author piqs
 */
class TimeHelper {
    
    public static function HourMinSec($timestamp){
        $s = sprintf("%02d", $timestamp % 60);
        $i = sprintf("%02d", ($timestamp - $s) / 60);
        $H = sprintf("%02d", intval($i / 60));
        return $H . ':' . $i . ':'. $s;
    }
}
