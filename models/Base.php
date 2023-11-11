<?php

namespace app\models;
use Yii;

/**
 * Classe base com algums métodos para serem reutilizados pelos outros Models
 */
class Base extends \yii\db\ActiveRecord
{
    /**
     * @param $name
     * @return string
     *
     * Caso o nome do atributo termine em _id (chave estrangeira)
     * ao gerar o label será mostrado o nome do atributo sem a palavra 'id'
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
     * @param $model
     * @return string
     * formatando as validações de array para string
     * para serem retornadas no json
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
