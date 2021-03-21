<?php

return [

    'whoops'              => 'Ops!',
    'hello'               => 'Bem vindo!',
    'salutation'          => 'Saudação,<br> :company_name',
    'subcopy'             => 'Se você estiver com problemas para clicar no botão ":text", copie e cole o URL abaixo em seu navegador da web: [:url](:url)',

    'update' => [

        'mail' => [

            'subject' => '⚠️ A atualização falhou em :domain',
            'message' => 'A atualização do :alias de :current_version para :new_version falhou em <strong>:step</strong> passo com a seguinte mensagem: :error_message',

        ],

        'slack' => [

            'message' => '⚠️ A atualização falhou em :domain',

        ],

    ],

];
