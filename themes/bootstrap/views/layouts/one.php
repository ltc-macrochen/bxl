<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>    

        <?php 
        
        $cs        = Yii::app()->clientScript;
        $themePath = Yii::app()->theme->baseUrl;
        
        // 基础CSS
        $cs->registerCssFile($themePath . '/css/bootstrap.min.css');
        $cs->registerCssFile($themePath . '/font-awesome/css/font-awesome.css');
        
        // 主题本身的CSS
        $cs->registerCssFile($themePath . '/css/animate.css');
        $cs->registerCssFile($themePath . '/css/style.css');
        
        $cs->registerScriptFile($themePath . '/js/jquery-1.9.1.min.js');
        $cs->registerScriptFile($themePath . '/js/bootstrap.min.js', CClientScript::POS_END);
        
        ?>
        
    </head>
    
    <?php echo $content; ?>

</html>