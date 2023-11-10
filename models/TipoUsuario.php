<?php

namespace app\models;

use Yii;

/**
 *
 * @property int $id
 * @property string $descricao
 *
 * @property Usuario[] $usuarios
 */
class TipoUsuario extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'tipo_usuario';
    }

    public function rules()
    {
        return [
            [['descricao'], 'required', 'string', 'max' => 100],
            [['descricao'], 'unique'],
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarios()
    {
        return $this->hasMany(Usuario::class, ['tipo_usuario_id' => 'id']);
    }
}
