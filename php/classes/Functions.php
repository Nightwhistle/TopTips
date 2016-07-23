<?php

class Functions {
    public static function fixTipName($tip) {
        switch ($tip) {
            case ('fr1') : $tip = '1'; break;
            case ('frx') : $tip = 'X'; break;
            case ('fr2') : $tip = '2'; break;
            
            case ('fh1') : $tip = 'FH1'; break;
            case ('fhx') : $tip = 'FHX'; break;
            case ('fh2') : $tip = 'FH2'; break;
            
            case ('sh1') : $tip = 'SH1'; break;
            case ('shx') : $tip = 'SHX'; break;
            case ('sh2') : $tip = 'SH2'; break;
            
            case ('go02') : $tip = '0-2'; break;
            case ('go23') : $tip = '2-3'; break;
            case ('go3p') : $tip = '3+'; break;
            case ('gg') : $tip = 'GG'; break;
            case ('gg3p') : $tip = 'GG3+'; break;
            
            case ('fh2p') : $tip = 'FH2+'; break;
            case ('sh2p') : $tip = 'SH2+'; break;
        }
        return $tip;
    }
}