<?php

return [

    'whoops'              => 'Ops!',
    'hello'               => 'Bem vindo!',
    'salutation'          => 'Saudação,<br> :company_name',
    'subcopy'             => 'Se você estiver com problemas para clicar no botão ":text", copie e cole o URL abaixo em seu navegador da web: [:url](:url)',
    'reads'               => 'Ler|Lidas
',
    'read_all'            => 'Ler Tudo',
    'mark_read'           => 'Marcar como lido',
    'mark_read_all'       => 'Marcar todos como lidos',
    'new_apps'            => 'Novo Aplicativo|Novos Aplicativos
',
    'upcoming_bills'      => 'Próximas Contas',
    'recurring_invoices'  => 'Faturas recorrentes',
    'recurring_bills'     => 'Contas Recorrentes',

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

    'messages' => [

        'mark_read'             => ':type está lendo esta notificação!',
        'mark_read_all'         => ':type está lendo todas as notificações!',
        'new_app'               => ':type aplicativo publicado.',
        'export'                => 'Seu arquivo de exportação de <b>:type</b> está pronto para <a href=":url" target="_blank"><b>baixar</b></a>.',
        'import'                => 'Seus dados de <b>:type</b> alinhados <b>:count</b> foram importados com sucesso.',

    ],
];
