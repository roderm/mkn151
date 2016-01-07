<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of view
 *
 * @author Roman
 */
class view {
    
    private $model;
    private $template;
    private $path = 'views';
    
    public function setModel($model){
        $this->model = $model;
        if(property_exists($model,'template')){
            $this->template = $model->template;
        }
    }
    
    public function setTemplate($template){
        $this->template = $template;
    }
    
    public function loadTemplate($tmodel = NULL){
        if($tmodel!==NULL){
            $this->setModel($tmodel);
        }
        // Pfad zum Template erstellen & überprüfen ob das Template existiert.  
        $file = $this->path . DIRECTORY_SEPARATOR . $this->template . '.php';  
        $exists = file_exists($file);  
        if ($exists){
            // Der Output des Scripts wird n einen Buffer gespeichert, d.h.  
            // nicht gleich ausgegeben.  
            ob_start();
            // Das Template-File wird eingebunden und dessen Ausgabe in   
            // $output gespeichert.  
            include $file;  
            $output = ob_get_contents();
            ob_end_clean();  
            // Output zurückgeben.  
            return (string)$output;  
        }  
        else {  
            // Template-File existiert nicht-> Fehlermeldung.  
            return 'could not find template';  
        }  
    }
}

?>
