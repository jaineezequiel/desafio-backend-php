# Resumo do desafio

Objetivo: 
Temos 2 tipos de usuários, os comuns e lojistas, ambos têm carteira com dinheiro e realizam transferências entre eles. Vamos nos atentar somente ao fluxo de transferência entre dois usuários.

Requisitos:

Para ambos tipos de usuário, precisamos do Nome Completo, CPF, e-mail e Senha. CPF/CNPJ e e-mails devem ser únicos no sistema. Sendo assim, seu sistema deve permitir apenas um cadastro com o mesmo CPF ou endereço de e-mail.

Usuários podem enviar dinheiro (efetuar transferência) para lojistas e entre usuários.

Lojistas só recebem transferências, não enviam dinheiro para ninguém.

Validar se o usuário tem saldo antes da transferência.

Antes de finalizar a transferência, deve-se consultar um serviço autorizador externo, use este mock para simular (https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc).

A operação de transferência deve ser uma transação (ou seja, revertida em qualquer caso de inconsistência) e o dinheiro deve voltar para a carteira do usuário que envia.

No recebimento de pagamento, o usuário ou lojista precisa receber notificação (envio de email, sms) enviada por um serviço de terceiro e eventualmente este serviço pode estar indisponível/instável. Use este mock para simular o envio (https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6).

Este serviço deve ser RESTFul.


# desafio-backend-php
Desafio Backend PHP

Para este projeto foi escolhido o Yii Framework para facilitar e agilizar o desenvolvimento e para que o foco maior seja nas regras de negócio da aplicação em si , e não em itens mais básicos e gerais.

# Estrutura do projeto (pastas e arquivos mais importantes)
<code>/modules/api/controllers/TransacaoController</code>  Onde fica a ação de realizar a transação de transferência de dinheiro

<code>/models/</code> onde foi criado um model Base para funções que poderia ser reutilização nos demais models

<code>/tests/unit/</code> Onde ficarão os testes automatizados 

# Modelagem BD
![modelagem-img](https://github.com/jaineezequiel/desafio-backend-php/assets/42620697/9d2f9d39-e871-4570-a66a-c925240f8e85)

# Como rodar na minha maquina? 

Fazer o clone do projeto 

na pasta /desafio-backend-php  <code>composer update</code>

Script para geração do banco de dados <code>/desafio-backend-php/scriptBDMysql.sql</code>

utilizei o postman para realizar os testes

utilizando o PHP instalado localmente, vá ate a pasta /desafio-backend-php\web
e rodar o comando <code>php -S localhost:8000</code>

Payload

POST http://localhost:8000/api/transacao

<code>
{
    "valor": 100.0,
    "remetente": 4,
    "destinatario": 3
}
</code>

# Melhorias futuras
- Implementar autenticação
- Identificar o usuário com alguma chave que não seja o id . Ex: (hash, @apelido, cpf)
- Implementar funcionalidade de estorno
- Verificar se a transação está sendo feita de forma duplicada




