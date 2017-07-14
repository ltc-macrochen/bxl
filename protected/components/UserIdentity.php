<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    public $uid = 0;
    public $userName = "";
    
    //系统默认的超级管理员账户
    static $_InternalAdminList = array(
            "root" => "root",
    );
    
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $users = self::$_InternalAdminList;
        if (isset($users[$this->username])) {
            if ($this->password == $users[$this->username]) {
                $this->uid = 0;
                $this->userName = $this->username;
                $this->setUserInfo(array("roleId" => 1, "realName" => "系统管理员"));
                $this->errorCode = self::ERROR_NONE;
            } else {
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            }

            return !$this->errorCode;
        } else {
            $record = AdminUser::model()->checkUserPassword($this->username, $this->password);
            if ($record) {
                $this->uid = $record->id;
                $this->userName = $record->name;
                $this->setUserInfo(array("roleId" => $record->roleId, "realName" => $record->role->name));
                $ret = AdminUser::model()->updateByPk($record->id, array("lastLoginTime" => date('Y-m-d H:i:s'), "lastLoginIp" => Yii::app()->request->userHostAddress));
                $this->errorCode = self::ERROR_NONE;
            } else {
                $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
            }

            return !$this->errorCode;
        }
    }

    public function getId() {
        return $this->uid;
    }

    public function getName() {
        return $this->userName;
    }

    public function setUserInfo($info) {
        foreach ($info as $name => $value) {
            $this->setState($name, $value);
        }
    }

}