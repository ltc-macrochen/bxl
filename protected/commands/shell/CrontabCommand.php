<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
0 * * * * /var/www/beva/wenda.m.beva.com/trunk/protected/yiic crontab markquestionexpired
0 1 * * * /var/www/beva/wenda.m.beva.com/trunk/protected/yiic crontab notifyquestionunanswered
* 2 * * * /var/www/beva/wenda.m.beva.com/trunk/protected/yiic crontab calcUserIncome
30 3 * * * /var/www/beva/wenda.m.beva.com/trunk/protected/yiic crontab payUserIncome
0 4 * * * /var/www/beva/wenda.m.beva.com/trunk/protected/yiic crontab refundUserPay
1 0 * * * /var/www/beva/wenda.m.beva.com/trunk/protected/yiic crontab userAndQuestionTotalStat
 * 
 */

/**
 * Description of CleanUp
 *
 * @author kevinwang
 */
class CrontabCommand extends CConsoleCommand {
    
    /**
     * 标注已经过期的问题并进行退款操作
     * 设置自动化脚本定时跑，也可以手动触发
     * 
     * 每天晚上1点到2点，每分钟执行一次
     * [ * 1 * * * /$PATH/protected/yiic crontab markquestionexpired ]
     * 每5分钟执行一次
     * [ * /5 * * * * /$PATH/protected/yiic crontab markquestionexpired ]
     */
    public function ActionMarkQuestionExpired() {
        $batchProcess = new BatchProcess();
        $batchProcess->markQuestionExpired();
    }
    
    /**
     * 通知用户即将过期的问题
     * 设置自动化脚本定时跑，也可以手动触发
     * 
     * 每天晚上1点执行一次
     * [ 0 1 * * * /$PATH/protected/yiic crontab notifyquestionunanswered ]
     */
    public function  ActionNotifyQuestionUnanswered() {
        $batchProcess = new BatchProcess();
        $batchProcess = $batchProcess->notifyQuestionUnanswered();
    }    
    
    /**
     * 计算用户每日收益
     * 设置自动化脚本定时跑，也可以手动触发
     * 
     * 每天晚上2点到3点，每分钟执行一次
     * [ * 2 * * * /$PATH/protected/yiic crontab calcUserIncome ]
     */
    public function  ActionCalcUserIncome() {
        $batchProcess = new BatchProcess();
        $batchProcess->calcUserIncome();
    }

    /**
     * 支付用户每日收益
     * 设置自动化脚本定时跑，也可以手动触发
     * 
     * 每天晚上3点30执行一次
     * [ 30 3 * * * /$PATH/protected/yiic crontab payUserIncome ]
     */
    public function  ActionPayUserIncome() {
        $batchProcess = new BatchProcess();
        $batchProcess->payUserIncome();
    }
     
    /**
     * 对退款失败的记录进行再退款
     * 设置自动化脚本定时跑，也可以手动触发
     * 
     * 每天晚上4点执行一次
     * [ 0 4 * * * /$PATH/protected/yiic crontab refundUserPay ]
     */
    public function  ActionRefundUserPay() {
        $batchProcess = new BatchProcess();
        $batchProcess->refundUserPay();
    }  
    
    /**
     * 用户、问题数据累计统计
     * 设置自动化脚本定时跑，也可以手动触发
     * 
     * 每天晚上0点 1分 执行一次
     * [ 1 0 * * * /$PATH/protected/yiic crontab userAndQuestionTotalStat ]
     */
    public function  ActionUserAndQuestionTotalStat() {
        $batchProcess = new BatchProcess();
        $batchProcess->userAndQuestionTotalStat();
    } 
}