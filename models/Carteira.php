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

    public function sacar(float $valor) : void{

        if ($valor > $this->saldo) {
            throw new Exception( "Valor indisponivel");
        }

        $this->saldo -= $valor;

    }

    public function depositar(float $valor) : void{

        if ($valor <= 0) {
            throw new Exception( "Valor para depósito precisa ser maior que zero");
        }

        $this->saldo += $valor;

    }

    public static function transferir($carteiraOrigem, $carteiraDestino,  $valor) : void
    {
        $carteiraOrigem->sacar($valor);
        $carteiraOrigem->save();

        $carteiraDestino->depositar($valor);
        $carteiraDestino->save();
    }
}
