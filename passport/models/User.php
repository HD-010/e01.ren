<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $ID
 * @property string $TEL
 * @property string $QQ
 * @property string $WECHAT
 * @property string $PSWD
 * @property string $UNAME
 * @property string $TYPE
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[], 'required'],
            [['ID'], 'integer'],
            [['TEL'], 'string', 'max' => 11],
            [['QQ', 'WECHAT'], 'string', 'max' => 13],
            [['PSWD', 'UNAME'], 'string', 'max' => 64],
            [['TYPE'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'TEL' => 'Tel',
            'QQ' => 'Qq',
            'WECHAT' => 'Wechat',
            'PSWD' => 'Pswd',
            'UNAME' => 'Uname',
            'TYPE' => 'Type',
        ];
    }
    
    /**
     * 根据给到的ID查询身份。
     *
     * @param string|integer $id 被查询的ID
     * @return IdentityInterface|null 通过ID匹配到的身份对象
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    
    /**
     * 根据 token 查询身份。
     *
     * @param string $token 被查询的 token
     * @return IdentityInterface|null 通过 token 得到的身份对象
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }
    
    /**
     * @return int|string 当前用户ID
     */
    public function getId()
    {
        return $this->ID;
    }
    
    /**
     * @return string 当前用户的（cookie）认证密钥
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    
    /**
     * @param string $authKey
     * @return boolean if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    
}
