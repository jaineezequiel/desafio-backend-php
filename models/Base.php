<?php

namespace app\models;
use Yii;

/**
 * Classe base com algums métodos para serem reutilizados pelos outros Models
 */
class Base extends \yii\db\ActiveRecord
{
    /**
     * Função responsável por formatar a label, caso o nome do atributo termine em _id (chave estrangeira)
     * ao gerar o label será mostrado o nome do atributo sem a palavra 'id'
     *
     * @param $name
     * @return string
     *
     */
    public function generateAttributeLabel($name)
    {
        if (substr($name, -3) === '_id') {
            $name = substr($name,0, -3);;
        }

        return parent::generateAttributeLabel($name);
    }

    /**
     * Converte as mensagem de erro das validações de array para string
     *
     * @param $model
     * @return string
     */
    public function formateErrorsToString($model)
    {
        $msgValidacao = '';

        if (!empty($model->errors)) {
            $erros = $model->errors;
            foreach ($erros as $key => $value) {
                $msgValidacao .=   str_replace('"',"",$value[0]) . ';';
            }
        }

        return $msgValidacao;
    }
}
