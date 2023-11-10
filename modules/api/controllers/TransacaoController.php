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

            $transacao = New Transacao();
            $transacao->load($post);

            $transacao->remetente_id = $post['remetente'];
            $transacao->destinatario_id = $post['destinatario'];
            $transacao->valor = abs($post['valor']);

            if (!$transacao->validate()) {

                $msgValidacao = '';

                if (!empty($transacao->errors)) {
                    $erros = $transacao->errors;
                    foreach ($erros as $key => $value) {
                        $msgValidacao .= $value[0] . PHP_EOL;
                    }
                }

                throw new \Exception($msgValidacao);
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

            // atualiza carteiras
            $carteiraOrigem = $usuarioOrigem->getCarteira()->one();
            $carteiraOrigem->saldo -= $transacao->valor;
            $carteiraOrigem->save();

            $carteiraDestino = $usuarioDestino->getCarteira()->one();
            $carteiraDestino->saldo += $transacao->valor;
            $carteiraDestino->save();

            // Notifica o usuário que recebeu o dinheiro
            if ($usuarioDestino->notifica($transacao)) {
                $transacao->notificado = 1;
            }

            // salva os dados da transação
            $transacao->save();

            $transaction->commit();

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
