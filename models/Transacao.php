<?php

namespace app\models;

use PHPUnit\Util\Exception;
use yii\httpclient\Client;
use Yii;

/**
 *
 * @property int $id
 * @property string $data
 * @property int $remetente_id
 * @property int $destinatario_id
 * @property float $valor
 * @property int|null $notificado
 *
 * @property Usuario $remetenteId
 * @property Usuario $destinatarioId
 */
class Transacao extends Base
{
    public static function tableName()
    {
        return 'transacao';
    }

    public function rules()
    {
        return [
            [['data'], 'safe'],
            [['remetente_id', 'destinatario_id', 'valor'], 'required'],
            [['remetente_id', 'destinatario_id', 'notificado'], 'integer'],
            [['valor'], 'number'],
            [['destinatario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['destinatario_id' => 'id']],
            [['remetente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['remetente_id' => 'id']],
        ];
    }

    public function autorizada()
    {
        $transacao = $this;

        $urlNotificacao = 'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc';
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setUrl($urlNotificacao)
            ->setData($transacao->toArray())
            ->send();

        if (!$response->getData()['message'] == 'Autorizado') {
            throw new Exception('Transação não autorizada');
        }

        return true;
    }

    public function load($data, $formName = null)
    {
        if (isset($data['remetente'])) {
            $data['remetente_id'] = $data['remetente'];
            unset($data['remetente']);
        }

        if (isset($data['destinatario'])) {
            $data['destinatario_id'] = $data['destinatario'];
            unset($data['destinatario']);
        }

        return parent::load($data, $formName);
    }

}