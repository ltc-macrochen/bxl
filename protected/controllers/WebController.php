<?php
/**
 * Created by PhpStorm.
 * User: macrochen
 * Date: 2017/7/17
 * Time: 11:24
 */
class WebController extends Controller {

    public $layout = '//layouts/one';

    public function actionIndex(){
        $this->render('index');
    }
}