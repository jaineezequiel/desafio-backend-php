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

    public static function atualizaCarteira($carteiraOrigem, $carteiraDestino,  $valor) : void
    {

        if ($valor < 0) {
            throw new Exception('O Valor deve ser maior que zero!');
        }

        $carteiraOrigem = $carteira;
        $carteiraOrigem->saldo -= $valor;
        $carteiraOrigem->save();


        $carteiraDestino->saldo += $transacao->valor;

        $carteiraDestino->save();
    }
}
