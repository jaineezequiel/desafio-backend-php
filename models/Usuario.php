<?php

namespace app\models;

use PHPUnit\Util\Exception as Exception;
use Yii;
use yii\httpclient\Client;
use function PHPUnit\Framework\throwException;

/**
 *
 * @property int $id
 * @property string $nome
 * @property int $cpf_cnpj
 * @property string $email
 * @property string $senha
 * @property int $tipo_usuario_id
 *
 * @property TipoUsuario $tipoUsuario
 * @property Carteira $carteira
 */
class Usuario extends \yii\db\ActiveRecord
{
    const USUARIO_COMUM = 1;
    const USUARIO_LOGISTA = 2;

    public static function tableName()
    {
        return 'usuario';
    }

    public function rules()
    {
        return [
            [['nome', 'cpf_cnpj', 'email', 'senha', 'tipo_usuario_id'], 'required'],
            [['cpf_cnpj', 'tipo_usuario_id'], 'integer'],
            [['nome'], 'string', 'max' => 200],
            [['email'], 'string', 'max' => 100],
            [['senha'], 'string', 'max' => 45],
            [['cpf_cnpj'], 'unique'],
            [['email'], 'unique'],
            [['tipo_usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => TipoUsuario::class, 'targetAttribute' => ['tipo_usuario_id' => 'id']],
        ];
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTipoUsuario()
    {
        return $this->hasOne(TipoUsuario::class, ['id' => 'tipo_usuario_id']);
    }

    /**
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCarteira()
    {
        return $this->hasOne(Carteira::class, ['usuario_id' => 'id']);
    }

    public function usuarioAptoTransacao($transacao){
        $usuario = $this;

        if ($usuario->tipo_usuario_id == Usuario::USUARIO_LOGISTA) {
            throw new Exception('Logistas não podem enviar dinheiro');
        }

        return $usuario->saldoSuficiente($transacao->valor) ? true : false;
    }

    public function saldoSuficiente($valorTransacao)
    {
        $usuario = $this;

        $carteira = Carteira::find()
            ->where(['usuario_id' => $usuario->id])
            ->one();

        if (!$carteira ||
            $carteira->saldo <= 0 ||
            (($carteira->saldo - $valorTransacao) < 0)
        ) {
            throw new Exception('Saldo insuficiente para esta operação');
        }

        return true;
    }

    public function notifica($transacao)
    {
        $urlNotificacao = 'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($urlNotificacao)
            ->setData([
                'email' => $this->email,
                'valor' => $transacao->valor
            ])
            ->send();

        if ($response->getData()['message']) {
            return true;
        } else {
            return false;
        }
    }
}
