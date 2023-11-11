<?php

namespace app\modules\api\controllers;

use app\controllers\Yii as Yii;
use app\models\Carteira;
use app\models\Transacao;
use app\models\Usuario;
use PHPUnit\Util\Exception;
use yii\httpclient\Client;
use function app\controllers\send;

class TransacaoController extends \yii\web\Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format = \YII\web\response::FORMAT_JSON;

        $transaction = \Yii::$app->db->beginTransaction();

        try {
            $request = \Yii::$app->request;
            $post = $request->post();

            //@TODO fazer a autenticação de usuário

            $transacao = New Transacao();
            $transacao->load($post, '');

            if (!$transacao->validate()) {
                throw new \Exception($transacao->formateErrorsToString($transacao));
            }

            $usuarioOrigem = Usuario::findOne($transacao->remetente_id);
            $usuarioDestino = Usuario::findOne($transacao->destinatario_id);

            // Validações
            if (!$usuarioOrigem) {
                throw new \Exception('Remetente não encontrado');
            }

            if (!$usuarioDestino) {
                throw new \Exception('Destinatário não existe');
            }

            // verifica se o usuário está apto a enviar dinheiro
            $usuarioOrigem->usuarioAptoTransacao($transacao);

            // verifica se a transacao está autorizada
            $transacao->autorizada();

            $transacao->valor = abs($transacao->valor);

            // atualiza carteiras
            $carteiraOrigem = $usuarioOrigem->getCarteira()->one();
            $carteiraOrigem->saldo -= $transacao->valor;
            $carteiraOrigem->save();

            $carteiraDestino = $usuarioDestino->getCarteira()->one();
            $carteiraDestino->saldo += $transacao->valor;
            $carteiraDestino->save();

            // salva os dados da transação
            $transacao->save();

            $transaction->commit();

            // Notifica o usuário que recebeu o dinheiro
            if ($usuarioDestino->notifica($transacao)) {
                $transacao->notificado = 1;
                $transacao->save();
            }

            $return = array(
                'success' => 'true'
            );

            return $return;

        } catch (\Exception $e ){
            $transaction->rollBack();

            $return = array(
                'success' => 'false',
                'message' => $e->getMessage()
            );

            return $return;
        }
    }

    public function beforeAction($action)
    {
        if ($action->id == 'index') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
}