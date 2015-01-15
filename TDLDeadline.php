<?php

/*
 *  File which holds Deadline class which works out deadlines
 */

class TDLDeadline {
    /**
     * Variable which holds calculated deadline
     *
     * @var long
     */
    private $deadline = 0;
    
    /**
     * Variable which holds value for repeated deadlines
     *
     * @var String
     */
    private $repeat = "";

    function __construct() {
        $this->deadline = -1;
    }
    
    /**
     * This function selects proper function to parse input to create deadline
     * 
     * @param string $rawDeadLine
     */
    public function fromForm($rawDeadLine) {    	
        $rawDeadLine = trim($rawDeadLine);
        if (preg_match("/\d:\d?\d today/", $rawDeadLine)) {
            $this->today($rawDeadLine, true);
        } else if (preg_match("/today/", $rawDeadLine)) {
            $this->today($rawDeadLine);
        } else if (preg_match("/\d:\d?\d tomorrow/", $rawDeadLine)) {
            $this->tomorrow($rawDeadLine, true);
        } else if (preg_match("/tomorrow/", $rawDeadLine)) {
            $this->tomorrow($rawDeadLine);
        } else if (preg_match("/\d:\d\d \d?\d [[:alpha:]]* \d\d\d?\d?/", $rawDeadLine)) {
            // 6:00 19 November 1989
            $this->namedMonth($rawDeadLine, true);
        } else if (preg_match("/\d?\d [[:alpha:]]* \d\d\d?\d?/", $rawDeadLine)) {
            // 19 November 1989
            $this->namedMonth($rawDeadLine);
        } else if (preg_match('/\d\d?\:\d\d \d\d?\. \d\d?\. \d\d\d?\d?/', $rawDeadLine)) {
            // 6:00 1. 1. 1090
            $this->europeanMonth($rawDeadLine, true);
        } else if (preg_match('/\d\d?\. \d\d?. \d\d\d?\d?/', $rawDeadLine)) {
            // 11. 11. 1090
            $this->europeanMonth($rawDeadLine);
        } else if (preg_match('/\d\d?\:\d\d \d\d?\/\d\d?\/\d\d/', $rawDeadLine)) {
            // 6:00 11/11/11
            $this->americanMonth($rawDeadLine, true);
        } else if (preg_match('/\d\d?\/\d\d?\/\d\d/', $rawDeadLine)) {
            // 11/11/11
            $this->americanMonth($rawDeadLine);
        } else if (preg_match('/eve?r?y? [[:alpha:]]* @ \d?\d\:\d\d/', $rawDeadLine)) {
            // every Monday @ 6:00
            $this->everyDay($rawDeadLine, true);
        } else if (preg_match('/eve?r?y? [[:alpha:]]*\z/', $rawDeadLine)) {
            // every Monday
            $this->everyDay($rawDeadLine);
        } else if (preg_match('/eve?r?y? \d\d* days @ \d?\d\:\d\d/', $rawDeadLine)) {
            // every 33 days @ 6:00
            $this->everyNumberOfDays($rawDeadLine, true);
        } else if (preg_match('/eve?r?y? \d\d* days/', $rawDeadLine)) {
            // every 33 days
            $this->everyNumberOfDays($rawDeadLine);
        } else if (preg_match('/eve?r?y? \d\d? @ \d?\d\:\d\d/', $rawDeadLine)) {
            // every 25 @ 6:00            
            $this->monthlyDeadline($rawDeadLine, true);
        } else if (preg_match('/eve?r?y? \d\d?\z/', $rawDeadLine)) {
            // every 25
            $this->monthlyDeadline($rawDeadLine);
        } else {
            $this->deadline = -1;
        }
    }
    
    /**
     * Getter for deadline
     * 
     * @return long
     */
    public function getDeadline() {
        return $this->deadline;
    }
    
    /**
     * Getter for repeat
     * 
     * @return String
     */
    public function getRepeat() {
        return $this->repeat;
    }
    
    
    private function today($rawDeadLine, $time = false) {
        if ($time) {
            $pieces = explode(" ", $rawDeadLine);
            $subpieces = explode(":",$pieces[0]);
            $this->deadline = mktime($subpieces[0], $subpieces[1]);
        } else {
            $this->deadline = mktime(23, 59, 59);
        }
    }


    /**
     * Special function for calculating tomorrow's deadline
     * 
     * @param String $rawDeadLine
     * @param boolean $time
     */    
    private function tomorrow($rawDeadLine, $time = false) {
        if ($time) {
            $pieces = explode(" ", $rawDeadLine);
            $subpieces = explode(":",$pieces[0]);
            $this->deadline = mktime($subpieces[0], $subpieces[1])+(24*60*60);
        } else {
            $this->deadline = mktime(23, 59, 59)+(24*60*60);
        }
    }
    
    /**
     * namedMonth function calculates deadline for strings where name of the month appears
     * 
     * @param String $rawDeadLine
     * @param boolean $time
     */
    private function namedMonth($rawDeadLine, $time = false) {
        $pieces = explode(" ", $rawDeadLine);
        $dlPrep = null;
        if ($time) {
            if (strlen($pieces[2]) == 3) {
                if (strlen($pieces[3]) == 4) {
                    $dlPrep = strptime($rawDeadLine, "%k:%M %e %b %Y"); // 6:00 1 Nov 2014
                } else {
                    $dlPrep = strptime($rawDeadLine, "%k:%M %e %b %y"); // 6:00 1 Nov 14
                }
            } else {
                if (strlen($pieces[3]) == 4) {
                    $dlPrep = strptime($rawDeadLine, "%k:%M %e %B %Y"); // 6:00 1 November 2014
                } else {
                    $dlPrep = strptime($rawDeadLine, "%k:%M %e %B %y"); // 6:00 1 November 14
                }
            }
            $this->makeDeadlineWithTime($dlPrep['tm_hour'], $dlPrep['tm_min'], 0, $dlPrep['tm_mon']+1, $dlPrep['tm_mday'], $dlPrep['tm_year'] + 1900);
        } else {
            if (strlen($pieces[2]) == 3) {
                if (strlen($pieces[2]) == 4) {
                    $dlPrep = strptime($rawDeadLine, "%e %b %Y"); // 1 Nov 2014
                } else {
                    $dlPrep = strptime($rawDeadLine, "%e %b %y"); // 1 Nov 14
                }
            } else {
                if (strlen($pieces[2]) == 4) {
                    $dlPrep = strptime($rawDeadLine, "%e %B %Y"); // 1 November 2014
                } else {
                    $dlPrep = strptime($rawDeadLine, "%e %B %y"); // 1 November 14
                }
            }
            $this->makeDeadlineWithTime(23, 59, 59, $dlPrep['tm_mon']+1, $dlPrep['tm_mday'], $dlPrep['tm_year'] + 1900);
        }
    }
    
    /**
     * europeanMonth calculates deadline for strings where appears d. m. (yy)yy
     * 
     * @param String $rawDeadLine
     * @param boolean $time
     */
    private function europeanMonth($rawDeadLine, $time = false) {
        $pieces = explode(" ", $rawDeadLine);
        $dlPrep = null;
        if ($time) {
            if (strlen($pieces[2]) == 2) {
            	$pieces[2] = "0". $pieces[2];
                $rawDeadLine = implode(" ", $pieces);
            }
            $dlPrep = strptime($rawDeadLine, "%k:%M %e. %m. %Y"); // 6:00 1. 11. 2014
            $this->makeDeadlineWithTime($dlPrep['tm_hour'], $dlPrep['tm_min'], 0, $dlPrep['tm_mon']+1, $dlPrep['tm_mday'], $dlPrep['tm_year'] + 1900);
        } else {
            $dlPrep = strptime($rawDeadLine, "%e. %m. %Y"); // 1. 11. 2014
            $this->makeDeadlineWithTime(23, 59, 59, $dlPrep['tm_mon']+1, $dlPrep['tm_mday'], $dlPrep['tm_year'] + 1900);
        }
    }
    
    /**
     * americanMonth is for strings where m/d/y appears
     * 
     * @param String $rawDeadLine
     * @param boolean $time
     */
    private function americanMonth($rawDeadLine, $time = false) {
        if ($time) {
            $pieces = explode(" ", $rawDeadLine);
            $subPieces = explode("/", $pieces[1]);
            if (strlen($subPieces[1]) == 2) {
                $rawDeadLine = $pieces[0] . " " . implode("/", $subPieces);
            }
            $time = explode(":", $pieces[0]);
            print_r($subPieces);
            $this->makeDeadlineWithTime($time[0], $time[1], 0, $subPieces[0], $subPieces[1], $subPieces[2]+2000);
        } else {
            $subPieces = explode("/", $rawDeadLine);
            if (strlen($subPieces[1]) == 2) {
                $rawDeadLine = implode("/", $subPieces);
            }
            $this->makeDeadlineWithTime(23, 59, 59, $subPieces[0], $subPieces[1], $subPieces[2]+2000);
        }
    }
    
    /**
     * everyDay deals with strings in format ev(ery) Mon(day) and similar
     * 
     * @param String $rawDeadLine
     * @param boolean $time
     * @param long $base
     */    
    private function everyDay($rawDeadLine, $time = false, $base = -1) {
        if ($base == -1) {
            $base = mktime(0,0,0);
        }
        $this->repeat = $rawDeadLine;
        $pieces = explode(" ", $rawDeadLine);
        $difference = $this->getDayDifference(strtolower($pieces[1]), $base);
        $nextNamedDay = date("j", $base) + $difference;
        $this->makeRepeatDeadline($nextNamedDay, $pieces, $time);
    }

    /**
     * Similar to everyDay but instead it's every number of days
     * 
     * @param String $rawDeadLine
     * @param boolean $time
     * @param long $base
     */    
    private function everyNumberOfDays($rawDeadLine, $time = false, $base = -1) {
        if ($base == -1) {
            $base = mktime(0,0,0);
        }
        $this->repeat = $rawDeadLine;
        $pieces = explode(" ", $rawDeadLine);
        if ($time) {
        	$time = $this->getTimeArray($pieces);
        	if (count($time) > 0) {
	        	$this->deadline = $base + (24 * 60 * 60 * $pieces[1])+(intval($time[0])*60*60)+intval($time[1]);
	        }
        } else {
        	$this->deadline = $base + (24 * 60 * 60 * ($pieces[1]+1));        	
        }
        $this->repeat = $rawDeadLine;
    }
    
    /**
     * Variation on everyDay excepts this is every month on certain day or last day of the month for 29, 30, 31
     * 
     * 
     * @param String $rawDeadLine
     * @param boolean $time
     * @param long $base
     * 
     */    
    private function monthlyDeadline($rawDeadLine, $time = false, $base = -1) {
        if ($base == -1) {
            $base = mktime(0,0,0);
        }
        $this->repeat = $rawDeadLine;
        $pieces = explode(" ", $rawDeadLine);
        print_r($pieces);
        if (date("j", $base) <= $pieces[1]) {
            $month = date("n", $base) + 1;
            $year = date("Y", $base);
            if ($month > 12) {
                $month = 1;
                $year = date("Y") + 1;
            }
            if ($pieces[1] > date("t", $base)) {
                $pieces[1] = date("t", $base);
            }
            if ($time) {
                $subPieces = explode(":", $pieces[3]);
                if (($this->isDateValid($month, $pieces[1], $year)) && ($this->isTimeValid($subPieces[0], $subPieces[1]))) {
                    $this->deadline = mktime($subPieces[0], $subPieces[1], 0, $month, $pieces[1], $year);
                }
            } else {
                if ($this->isDateValid(date("n", $base), $pieces[1], date("Y", $base))) {
                    $this->deadline = mktime(23, 59, 59, $month, $pieces[1], $year);
                }
            }
        } else {
            if ($pieces[1] > date("t", $base)) {
                $pieces[1] = date("t", $base);
            }
            if ($time) {
                $subPieces = explode(":", $pieces[3]);
                if (($this->isDateValid(date("n", $base), $pieces[1], date("Y", $base))) && ($this->isTimeValid($subPieces[0], $subPieces[1]))) {
                    $this->deadline = mktime($subPieces[0], $subPieces[1], 0, date("n", $base), $pieces[1], date("Y", $base));
                }
            } else {
                if ($this->isDateValid(date("n", $base), $pieces[1], date("Y", $base))) {
                    $this->deadline = mktime(23, 59, 59, date("n", $base), $pieces[1], date("Y", $base));
                }
            }
        }
    }
    
    /**
     * Calculates difference between now and desired day
     * 
     * @param String $desiredDay
     * @param long $base
     * @return int
     */    
    private function getDayDifference($desiredDay, $base) {
        if ($base != mktime()) {
            $diff = intval(($base - mktime()) / (24*60*60));
        } else {
            $diff = 0;
        }
        $pos = 1;
        $found = false;
        $listOfDays = array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
        foreach ($listOfDays as $day) {
            if (strpos($day, $desiredDay) !== false) {
                $found = true;
                break;
            }
            $pos++;
        }
        if (($pos - date("N")) >= 0) {          
            return $pos - date("N") + $diff;
        } else {
            return 7 - abs($pos - date("N")) + $diff;
        }
    }
    
    /**
     * Checks if it is last day of month
     * 
     * @param int $month
     * @param int $day
     * @param int $year
     * @return boolean
     */
    private function isLastDayOfMonth($month, $day, $year) {
        switch ($month) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                if ($day == 31) {
                    return true;
                }
                break;
            case 2:
                if (date("L", mktime(0, 0, 0, $month, $day, $year)) == 1) {
                    if ($day == 29) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    if ($day == 28) {
                        return true;
                    } else {
                        return false;
                    }
                }
                break;
            default:
                if ($day == 30) {
                    return true;
                }
                break;
        }
        return false;
    }
    
    /**
     * Checks if the date is valid
     * 
     * @param int $month
     * @param int $day
     * @param int $year
     * @return boolean
     */    
    private function isDateValid($month, $day, $year) {
        switch ($month) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                if ($day > 31) {                	
                    return false;
                }
                break;
            case 2:            	
                if (date("L", mktime(0, 0, 0, $month, $day, $year)) == 1) {
                    if ($day > 29) {
                        return false;
                    }
                } else {
                    if ($day > 28) {
                        return false;
                    }
                }
                break;
            default:
                if ($day > 30) {
                    return false;
                }
                break;
        }
        if ($day < 1) {
            return false;
        }
        if ($month < 1) {
            return false;
        }
        if ($month > 12) {
            return false;
        }
        return true;
    }
    
    /**
     * Checks if the time is valid
     * 
     * @param int $hour
     * @param int $minute
     * @return boolean
     */
    private function isTimeValid($hour, $minute) {
        if ($hour > 23) {
            return false;
        }
        if ($hour < 0) {
            return false;
        }
        if ($minute > 59) {
            return false;
        }
        if ($minute < 0) {
            return false;
        }
        return true;
    }
    
    /**
     * Creates deadline
     * 
     * @param int $hour
     * @param int $minute
     * @param int $seconds
     * @param int $month
     * @param int $day
     * @param int $year
     * @return void
     */    
    private function makeDeadlineWithTime($hour, $minute, $seconds, $month, $day, $year) {
        if (!($this->isDateValid($month, $day, $year) && $this->isTimeValid($hour, $minute) )) {
            $this->deadline = -1;
            return;
        }
        $this->deadline = mktime($hour, $minute, $seconds, $month, $day, $year);
    }
    
    /**
     * Gets array with time as it's values
     * 
     * @param array<String> $pieces
     * @return array
     */    
    private function getTimeArray($pieces) {
    	for ($i = 0; $i < count($pieces); $i++) {
    		if ($pieces[$i] == "@") {
    			return explode(":", $pieces[$i+1]);
    		}
    	}
        $this->deadline = -1;
        return array();
    }
    
    /**
     * Handles repeating deadlines
     * 
     * @param String $nextNamedDay
     * @param array<String> $pieces
     * @param long $time
     */
    private function makeRepeatDeadline($nextNamedDay, $pieces, $time) {
        $month = date("n");
        $year = date("Y");
        while (!$this->isDateValid($month, $nextNamedDay, $year)) {
            switch ($month) {
                case 1:
                case 3:
                case 5:
                case 7:
                case 8:
                case 10:
                case 12:
                    if ($nextNamedDay > 31) {
                        $nextNamedDay -= 31;
                        $month++;
                    }
                    break;
                case 2:
                    if (date("L", mktime(0, 0, 0, $month, $nextNamedDay, $year)) == 1) {
                        if ($nextNamedDay > 29) {
                            $nextNamedDay -= 29;
                            $month++;
                        }
                    } else {
                        if ($nextNamedDay > 28) {
                            $nextNamedDay -= 28;
                            $month++;
                        }
                    }
                    break;
                case 2:
                case 4:
                case 6:
                case 9:
                case 11:
                    if ($nextNamedDay > 30) {
                        $nextNamedDay -= 30;
                        $month++;
                    }
                    break;
                default:
                    break;
            }
            if ($month > 12) {
                $month -= 12;
                $year++;
            }
        }
        if ($time) {
            $time = $this->getTimeArray($pieces);
            if (count($time) == 2) {
                $this->makeDeadlineWithTime($time[0], $time[1], 0, $month, $nextNamedDay, $year);
            }
        } else {
            $this->makeDeadlineWithTime(23, 59, 59, $month, $nextNamedDay, $year);
        }
    }
    
    /**
     * Gets next deadline
     * 
     * @param String $rawDeadLine
     * @param long $deadline
     * @return long
     */    
    function getNextDeadline($rawDeadLine, $deadline) {
        if (preg_match('/eve?r?y? [[:alpha:]]* @ \d?\d\:\d\d/', $rawDeadLine)) {
            // every Monday @ 6:00
            $this->everyDay($rawDeadLine, true, $deadline);
        } else if (preg_match('/eve?r?y? [[:alpha:]]*\z/', $rawDeadLine)) {
            // every Monday
            $this->everyDay($rawDeadLine, false, $deadline);
        } else if (preg_match('/eve?r?y? \d\d* days @ \d?\d\:\d\d/', $rawDeadLine)) {
            // every 33 days @ 6:00
            $this->everyNumberOfDays($rawDeadLine, true, $deadline);
        } else if (preg_match('/eve?r?y? \d\d* days/', $rawDeadLine)) {
            // every 33 days
            $this->everyNumberOfDays($rawDeadLine, false, $deadline);
        } else if (preg_match('/eve?r?y? \d\d? @ \d?\d\:\d\d/', $rawDeadLine)) {
            // every 25 @ 6:00            
            $this->monthlyDeadline($rawDeadLine, true, $deadline);
        } else if (preg_match('/eve?r?y? \d\d?\z/', $rawDeadLine)) {
            // every 25
            $this->monthlyDeadline($rawDeadLine, false, $deadline);
        } else {
            $this->deadline = -1;
        }
        return $this->deadline;
    }

}

?>