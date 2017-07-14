<?php

/**
 * This is the model class for table "tbl_admin_role".
 *
 * The followings are the available columns in table 'tbl_admin_role':
 * @property string $id
 * @property string $name
 * @property string $description
 */
class AdminRole extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 't_admin_role';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 255),
            array('description', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, description', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => '角色ID',
            'name' => '角色名称',
            'description' => '角色描述',
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AdminRole the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function getDataToList() {
        //查询全部数据
        $category = self::model()->findAll();
        $data = array();

        foreach ($category as $cat) {
            array_push($data, array("value" => $cat->id, "show" => $cat->name));
        }
        return $data;
    }
    
    const ROLE_GUEST = 0;
    const ROLE_ADMINISTRATOR = 1;
    const ROLE_FINANCE = 2;

}
