<?php

require_once("TDLTaskClass.php");

/**
 * Class for parsing task/project templates
 */
class TDLTemplate {
    
    /**
     *
     * @var String
     */
    private $type;
    /**
     *
     * @var String
     */
    private $projectName = "";
    /**
     *
     * @var array
     */
    private $listOfTasks = array();
    
    function __construct($template) {
        $this->processTemplate($template);
    }
    /**
     * 
     * @param string $template
     */
    private function processTemplate($template) {
        $splitLines = split("\n", $template);
        if (count($splitLines) > 0) {
            $pieces = split(" ", $splitLines[0]);
            if ($pieces[0] == "new") {
                for ($index = 1; $index < count($pieces); $index++) {
                    $this->projectName = $pieces[$index];
                }                
                $this->type = "project";
            } else {
                $this->type = "set of tasks";
            }
            for (strlen($this->projectName) >= 0 ? $i = 1: $i = 0; $i < count($splitLines); $i++) {
                $this->listOfTasks[] = new TDLTaskClass($splitLines[$i]);
            }
        }
    }
    
    /**
     * 
     * @return array<TDLTaskClass>
     */
    public function getListOfTasks() {
        return $this->listOfTasks;
    }
    
}
