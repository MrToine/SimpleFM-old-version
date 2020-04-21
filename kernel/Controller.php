<?php
/*#################################################################################################################
#                                                                                                                 #
# Author : VIOLET Anthony                                                                                         #
# Created : `Date.today.strftime('%D')`                                                                           #
# Updated : `Date.today.strftime('%D')`                                                                           #
# Licence : General Public License (GPL)                                                                          #
#                                                                                                                 #
#################################################################################################################*/
class Controller {

    public $request;
    public $vars = array();
    public $layout = 'default';
    private $rendered = false;

    function __construct($request) {
        $this->request = $request;
    }

    public function render($view) {
        if($this->rendered){
            return false;
        }
        extract($this->vars);
        if(!empty(ConfigApp::$dir_views)){
            ConfigApp::$dir_views = ConfigApp::$dir_views.DS;
        }
        if(strpos($view, '/')===0){
            $view = ROOT.DS.ConfigApp::$dir_views.'views'.$view.'.php';
        }else{
            $view = ROOT.DS.ConfigApp::$dir_views.'views'.DS.$this->request->controller.DS.$view.'.php';
        }
        ob_start();
        require($view);
        $output = ob_get_clean();
        require ROOT.DS.'views'.DS.'layout'.DS.$this->layout.'.php';
        $this->rendered = true;
    }

    public function set($key, $value = null) {
        if(is_array($key)) {
            $this->vars += $key;
        }else{
            $this->vars[$key] = $value;
        }
    }

    public function loadModel($model) {
        if(!empty(ConfigApp::$dir_models)){
            ConfigApp::$dir_models = ConfigApp::$dir_models.DS;
        }
        $file = ROOT.DS.ConfigApp::$dir_models.'models'.DS.$model.'.php';
        require_once($file);
        if(!isset($this->$model)){
            $this->$model = new $model();
        }
    }

    public function request($controller, $action) {
        if(!empty(ConfigApp::$dir_controllers)){
            ConfigApp::$dir_controllers = ConfigApp::$dir_controllers.DS;
        }
        $controller .= 'Controller';
        require_once ROOT.DS.ConfigApp::$dir_controllers.'controllers'.DS.controller.'.php';
        $c = new $controller();
        return $c->$action;
    }
}
