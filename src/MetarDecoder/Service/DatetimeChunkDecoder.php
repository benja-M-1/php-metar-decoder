<?php

namespace MetarDecoder\Service;

use \DateTime;
use \DateTimeZone;

class DatetimeChunkDecoder extends MetarChunkDecoder implements MetarChunkDecoderInterface
{
    
    public function isMandatory()
    {
        return true;
    }
    
    public function getRegexp()
    {
        return '#^([0-9]{2})([0-9]{2})([0-9]{2})Z #';
    }
    
    public function parse($remaining_metar)
    {
        $found = $this->applyRegexp($remaining_metar);
        
        // handle the case where nothing has been found
        if($found == null){
            $result = null;
        }else{// retrieve found params and check them
            $day = $found[1];
            $hour = $found[2];
            $minute = $found[3];
            if($this->checkValidity($day,$hour,$minute)){
                $result = array(
                    'day' => $found[1],
                    'time' => DateTime::createFromFormat('H:i',$found[2].':'.$found[3],new DateTimeZone('UTC'))
                );
            }else{
                $result = null;
            }
            
        }
        
        // return result + remaining metar
        return array(
            'result' => $result,
            'remaining_metar' => $this->getRemainingMetar($remaining_metar)
        );
    }
    
    /**
     * Check the validity of the decoded information for date time
     * @return true if valid, false if not
     */
    private function checkValidity($day,$hour,$minute)
    {
        // convert as integers
        $day_int = intval($day);
        $hour_int = intval($hour);
        $minute_int = intval($minute);
        
        // check value range
        if($day_int  < 1 || $day_int > 31) return false;
        if($hour_int   > 23) return false;
        if($minute_int > 59) return false;
        
        return true;
    }
}
