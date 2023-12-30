<?php

namespace tests\unit;

use app\models\Base;
use Codeception\Test\Unit;

class TransacaoTeste extends Unit
{
    public function enviaDinheiroAction()
    {
       // Definir o cenário : Trasnferencia de dinheiro entre usuários

        $dados = array(
            'remetente' => '',
            'destinatario' => '',
            'valor' => ''
        );

        // valor suficiente
        // valor maior que o saldo
        // valor negativo
        // valor não numérico

        // Executar a ação
        $url = 'http://localhost:8000';
        $response = Base::apiRequest($url, $dados);

        // Verificar se o resultado é o esperado

        if (true) {
            echo 'TESTE ok';
        } else {
            echo 'TESTE FALHOU';
        }
    }

}
