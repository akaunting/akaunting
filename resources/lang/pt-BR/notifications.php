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

    'import' => [

        'completed' => [
            'subject'           => 'Importação concluída',
            'description'       => 'A importação foi concluída e os registros estão disponíveis em seu painel.',
        ],

        'failed' => [
            'subject'           => 'Importação Falhou',
            'description'       => 'Não é possível importar o arquivo devido aos seguintes problemas:',
        ],
    ],

    'export' => [

        'completed' => [
            'subject'           => 'A exportação está pronta',
            'description'       => 'O arquivo de exportação está pronto para ser baixado a partir do seguinte link:',
        ],

        'failed' => [
            'subject'           => 'A exportação falhou',
            'description'       => 'Não é possível importar o arquivo devido aos seguintes problemas:',
        ],

    ],

];
