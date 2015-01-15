<?php

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
    private $listOfTasks;
    
    function __construct($template) {
        processTemplate($template);
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
            
        }
    }
    
}
