<?php

namespace app\models;

use Yii;

class Carteira extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'carteira';
    }

    public function rules()
    {
        return [
            [['saldo'], 'number'],
            [['usuario_id'], 'required'],
            [['usuario_id'], 'integer'],
            [['usuario_id'], 'unique'],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['usuario_id' => 'id']],
        ];
    }

    public function getUsuario()
    {
        return $this->hasOne(Usuario::class, ['id' => 'usuario_id']);
    }
}
