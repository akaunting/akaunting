<?php

return [

    'whoops'              => 'Ops!',
    'hello'               => 'Bem vindo!',
    'salutation'          => 'Saudação,<br> :company_name',
    'subcopy'             => 'Se você estiver com problemas para clicar no botão ":text", copie e cole o URL abaixo em seu navegador da web: [:url](:url)',
    'mark_read'           => 'Marcar como lido',
    'mark_read_all'       => 'Marcar todos como lidos',
    'empty'               => 'Woohoo, zero notificações!',
    'new_apps'            => ':app está disponível. <a href=":url">Confira agora</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ A atualização falhou em :domain',
            'description'   => 'A atualização do :alias de :current_version para :new_version falhou em <strong>:step</strong> passo com a seguinte mensagem: :error_message',

        ],

        'slack' => [

            'description'   => 'A atualização falhou em :domain',

        ],

    ],

    'download' => [

        'completed' => [

            'title'         => 'Download pronto',
            'description'   => 'O arquivo está pronto para download no seguinte link:',

        ],

        'failed' => [

            'title'         => 'Falha no download',
            'description'   => 'Não foi possível criar o arquivo devido ao seguinte problema:',

        ],

    ],

    'import' => [

        'completed' => [

            'title'         => 'Importação concluída',
            'description'   => 'A importação foi concluída e os registros estão disponíveis em seu painel.',

        ],

        'failed' => [

            'title'         => 'A importação falhou',
            'description'   => 'Não é possível importar o arquivo devido aos seguintes problemas:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'A exportação está pronta',
            'description'   => 'O arquivo de exportação está pronto para ser baixado a partir do seguinte link:',

        ],

        'failed' => [

            'title'         => 'A exportação falhou',
            'description'   => 'Não é possível importar o arquivo devido aos seguintes problemas:',

        ],

    ],

    'email' => [

        'invalid' => [

            'title'         => 'E-mail :type inválido',
            'description'   => 'O endereço de e-mail :email foi relatado como inválido e a pessoa foi desativada. Verifique a seguinte mensagem de erro e corrija o endereço de e-mail:',

        ],

    ],

    'menu' => [

        'download_completed' => [

            'title'         => 'Download pronto',
            'description'   => 'Seu arquivo <strong>:type</strong> está pronto para <a href=":url" target="_blank"><strong>download</strong></a>.',
        ],

        'download_failed' => [

            'title'         => 'Falha no download',
            'description'   => 'Não foi possível criar o arquivo devido a vários problemas. Confira seu e-mail para os detalhes.',
        ],

        'export_completed' => [

            'title'         => 'A exportação está pronta',
            'description'   => 'Seu arquivo de exportação <strong>:type</strong> está pronto para <a href=":url" target="_blank"><strong>baixar</strong></a>.',
        ],

        'export_failed' => [

            'title'         => 'A exportação falhou',
            'description'   => 'Não é possível importar o arquivo devido aos seguintes problemas: :issues',
        ],

        'import_completed' => [

            'title'         => 'Importação concluída',
            'description'   => 'Seu <strong>:type</strong> em linha <strong>:count</strong> dados estão importados com sucesso.',
        ],

        'import_failed' => [

            'title'         => 'Importação falhou',
            'description'   => 'Não foi possível importar o arquivo devido a vários problemas. Confira seu e-mail para os detalhes.',

        ],

        'new_apps' => [

            'title'         => 'Novo app',
            'description'   => '<strong>:name</strong> app esgotado. <a href=":url">Clique aqui</a> para ver os detalhes.',
        ],

        'invoice_new_customer' => [

            'title'         => 'Nova fatura',
            'description'   => '<strong>:invoice_number</strong> fatura foi criada. <a href=":invoice_portal_link">Clique aqui</a> para ver os detalhes e prosseguir com o pagamento.',
        ],

        'invoice_remind_customer' => [

            'title'         => 'Fatura atrasada',
            'description'   => '<strong>:invoice_number</strong> fatura foi vencida em <strong>:invoice_due_date</strong>. <a href=":invoice_portal_link">Clique aqui</a> para ver os detalhes e prosseguir com o pagamento.',
        ],

        'invoice_remind_admin' => [

            'title'         => 'Fatura atrasada',
            'description'   => '<strong>:invoice_number</strong> fatura venceu em <strong>:invoice_due_date</strong>. <a href=":invoice_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nova fatura recorrente',
            'description'   => '<strong>:invoice_number</strong> fatura é criada com base no seu ciclo recorrente. <a href=":invoice_portal_link">Clique aqui</a> para ver os detalhes e prosseguir com o pagamento.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nova fatura recorrente',
            'description'   => '<strong>:invoice_number</strong> fatura é criada com base no <strong>:customer_name</strong> ciclo recorrente. <a href=":invoice_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Fatura visualizada',
            'description'   => '<strong>:customer_name</strong> visualizou a fatura <strong>:invoice_number</strong> . <a href=":invoice_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Pagamento recebido',
            'description'   => 'Obrigado pelo pagamento da fatura <strong>:invoice_number</strong> . <a href=":invoice_portal_link">Clique aqui</a> para ver os detalhes.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Pagamento recebido',
            'description'   => 'Obrigado pelo pagamento da fatura <strong>:invoice_number</strong> . <a href=":invoice_portal_link">Clique aqui</a> para ver os detalhes.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Pagamento recebido',
            'description'   => ':customer_name registrou pagamento para fatura <strong>:invoice_number</strong>. <a href=":invoice_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Conta atrasada',
            'description'   => '<strong>:bill_number</strong> conta vencerá em <strong>:bill_due_date</strong>. <a href=":bill_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nova conta recorrente',
            'description'   => '<strong>:bill_number</strong> conta é criada com base no <strong>:vendor_name</strong> ciclo recorrente. <a href=":bill_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'invalid_email' => [

            'title'         => 'E-mail :type inválido',
            'description'   => 'O endereço de e-mail <strong>:email</strong> foi relatado como inválido e a pessoa foi desativada. Verifique e corrija o endereço de e-mail.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type está lendo esta notificação!',
        'mark_read_all'         => ':type está lendo todas as notificações!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Configuração de ícones do Firefox',
            'description'  => '<span class="font-medium">Se seus ícones não aparecerem, por favor;</span> <br /> <span class="font-medium">Permita que as páginas escolham suas próprias fontes, em vez de suas seleções acima</span> <br /><br /> <span class="font-bold"> Configurações (Preferências) > Fontes > Avançado </span>',

        ],

    ],

];
