<?php
	/*
	 * File which holds task class
	 */
	 
	 require_once('TDLDeadline.php');
	 
	 class TDLTaskClass {
	 	
	 	private $taskName;
	 	private $project;
	 	private $labels;
	 	private $deadline;
	 	private $repeat;
	 
	 	function __construct($rawToProcess, $rawDeadLine) {
                        $toProcess = explode(" ",$rawToProcess);
			$project = "";
			$labels = [];
			$taskName = "";
			for ($i = 0; $i < count($toProcess); $i++) {
				if (strpos($toProcess[$i], "#") === 0) {
					// labels			
					if (!is_numeric(substr($toProcess[$i], 1))) { // allowing to write "I'm #1"
	            	    $labels[] = substr($toProcess[$i], 1);
    	        	}
        		} else if (strpos($toProcess[$i], "@") === 0) {
					// project					
					$project = substr($toProcess[$i], 1);
				} else {
					$taskName .= " ".$toProcess[$i];
				}
			}	
			$deadline = new TDLDeadline();
			$deadline->fromForm($rawDeadLine);
			$this->taskName = $taskName;
			$this->project = $project;
			$this->labels = $labels;
			$this->deadline = $deadline->getDeadline();
			$this->repeat = $deadline->getRepeat();
	    }
	    
	    public function getTaskName() {
	    	return $this->taskName;
	    }
	    public function getProject() {
	    	return $this->project;
	    }
	    public function getLabels() {
	    	return $this->labels;
	    }
	    public function getDeadline() {
	    	return $this->deadline;
	    }
	    public function getRepeat() {
	    	return $this->repeat;
	    }
	    
	 }

?>