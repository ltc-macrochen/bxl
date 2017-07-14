    <body class="gray-bg">
        <div class="middle-box text-center loginscreen animated fadeInDown" style="width:600px;max-width:none;">
            <div>
                <h3 style="margin-top:100px;">欢迎访问贝瓦统计系统+</h3>
                <p>&nbsp;</p>
                <?php $form=$this->beginWidget('bootstrap.widgets.BsActiveForm', array(
                    'id'=>'login-form',
                    'enableClientValidation'=>true,
                    'layout' => BsHtml::FORM_LAYOUT_HORIZONTAL,       
                    'clientOptions'=>array(
                            'validateOnSubmit'=>true,
                    ),
                    'action' => '/site/login',
                )); ?>

                    <?php echo $form->textFieldControlGroup($model,'username'); ?>
                    <?php echo $form->passwordFieldControlGroup($model,'password'); ?>

                    <?php if(CCaptcha::checkRequirements()): ?>
                        <?php
                            $verifyCodeImg = $this->widget('CCaptcha',array('showRefreshButton'=>false,'clickableImage'=>true,'imageOptions'=>array('alt'=>'点击换图','title'=>'点击换图','style'=>'cursor:pointer')),true); 

                            echo $form->textFieldControlGroup($model,'verifyCode',array("append"=>$verifyCodeImg,"appendOptions"=>array("addOnOptions"=>array("style"=>"padding:0 !important")))); 
                        ?>
                    <?php endif; ?>    

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-9" style="margin-left:12%">
                            <div class="checkbox">
                                <input type="checkbox" value="1" id="LoginForm_rememberMe" name="LoginForm[rememberMe]">
                                <label class="control-label " style="padding:0 0 0 5px;text-align:left;vertical-align:middle;">记住登录状态</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-9" style="margin-left:12%">
                            <button name="yt0" type="submit" class="btn btn-primary">登录</button>
                        </div>
                    </div>

                <?php $this->endWidget(); ?>
                
                <p>&nbsp;</p>
                <p class="m-t"> <small>北京芝兰玉树科技有限公司版权所有 &copy; 2010-2015</small> </p>
            </div>
        </div>
    </body>