<?php

/**
 * This is the model class for table "tbl_admin_user".
 *
 * The followings are the available columns in table 'tbl_admin_user':
 * @property string $id
 * @property integer $roleId
 * @property string $name
 * @property string $password
 * @property string $realName
 * @property string $mobile
 * @property string $email
 * @property integer $status
 * @property string $lastLoginIp
 * @property string $lastLoginTime
 */
class AdminUser extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_admin_user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, password, realName', 'required'),
            array('roleId, status', 'numerical', 'integerOnly' => true),
            array('name, password, realName, mobile, email', 'length', 'max' => 255),
            array('lastLoginIp', 'length', 'max' => 16),
            array('lastLoginTime', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, roleId, name, password, realName, mobile, email, status, lastLoginIp, lastLoginTime', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'role' => array(self::BELONGS_TO, 'AdminRole', 'roleId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => '用户ID',
            'roleId' => '角色',
            'name' => '用户名',
            'password' => '用户密码',
            'realName' => '用户真实姓名',
            'mobile' => '用户手机号',
            'email' => '用户邮箱',
            'status' => '审核状态',
            'lastLoginIp' => '用户最近一次登录IP',
            'lastLoginTime' => '用户最近一次登录时间',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id, true);

        $criteria->with = array('role');
        $criteria->compare('role.name', $this->roleId, true);

        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('realName', $this->realName, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('lastLoginIp', $this->lastLoginIp, true);
        $criteria->compare('lastLoginTime', $this->lastLoginTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AdminUser the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //MD5加密key
    private $_md5Key = '3EDC-5TGB-7UJM-(OL>';
    
    private $_aesKey = "jinding@9478844";

    /**
     * 用户密码加密
     */
    public function encryptPwd($pwd) {
        return $pwd;
        $aes = new AES($this->_aesKey);
        return base64_encode($aes->encrypt($pwd));
    }

    /**
     * 用户密码解密
     */
    public function decryptPwd($pwd) {
        return $pwd;
        $aes = new AES($this->_aesKey);
        return $aes->decrypt(base64_decode($pwd));
    }

    /**
     * 检查登录用户密码是否正确
     * @param type $name，      用户名
     * @param type $password，  输入的密码
     * @return boolean
     */
    public function checkUserPassword($name, $password) {
        $user = self::model()->with("role")->findAll(array(
            'select' => 'id, name, password, realName, roleId, status',
            'condition' => "t.name='{$name}'",
        ));

        if (!empty($user) && ($user[0]->password == $this->encryptPwd($password)) && $user[0]->status != AdminUser::USER_STATUS_DISABLED) {
            return $user[0];
        }

        return false;
    }

    //返回MD5加密字符串
    public function getMd5Str($str) {
        return md5($str . "_" . strval(time()) . $this->_md5Key);
    }

    //用户审核状态
//        const USER_STATUS_ENABLED = 0;    //已启用
    const USER_STATUS_INIT = 0;       //未绑定
    const USER_STATUS_BOUND = 1;      //已绑定
    const USER_STATUS_DISABLED = 2;   //已禁用

    //（下拉菜单使用）

    static $_USER_STATUS_LIST = array(
//            array("value"=> self::USER_STATUS_ENABLED, "show" => "已启用"),
        array("value" => self::USER_STATUS_INIT, "show" => "未绑定"),
        array("value" => self::USER_STATUS_BOUND, "show" => "已绑定"),
        array("value" => self::USER_STATUS_DISABLED, "show" => "已禁用"),
    );

}
