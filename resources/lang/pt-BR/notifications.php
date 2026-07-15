<?php

return [

    'whoops'              => 'Ops!',
    'hello'               => 'Olá!',
    'salutation'          => 'Atenciosamente,<br> :company_name',
    'subcopy'             => 'Se você estiver com problemas para clicar no botão ":text", copie e cole a URL abaixo no seu navegador da web: [:url](:url)',
    'mark_read'           => 'Marcar como lida',
    'mark_read_all'       => 'Marcar todas como lidas',
    'empty'               => 'Eba, zero notificações!',
    'new_apps'            => ':app está disponível. <a href=":url">Confira agora</a>!',

    'update' => [

        'mail' => [

            'title'         => '⚠️ A atualização falhou em :domain',
            'description'   => 'A atualização de :alias de :current_version para :new_version falhou na etapa <strong>:step</strong> com a seguinte mensagem: :error_message',

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

            'title'         => 'Falha na importação',
            'description'   => 'Não foi possível importar o arquivo devido aos seguintes problemas:',

        ],
    ],

    'export' => [

        'completed' => [

            'title'         => 'Exportação pronta',
            'description'   => 'O arquivo de exportação está pronto para download no seguinte link:',

        ],

        'failed' => [

            'title'         => 'Falha na exportação',
            'description'   => 'Não foi possível criar o arquivo de exportação devido ao seguinte problema:',

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

            'title'         => 'Exportação pronta',
            'description'   => 'Seu arquivo de exportação <strong>:type</strong> está pronto para <a href=":url" target="_blank"><strong>download</strong></a>.',
        ],

        'export_failed' => [

            'title'         => 'Falha na exportação',
            'description'   => 'Não foi possível criar o arquivo de exportação devido a vários problemas. Confira seu e-mail para os detalhes.',

        ],

        'import_completed' => [

            'title'         => 'Importação concluída',
            'description'   => 'Seu <strong>:type</strong> com <strong>:count</strong> dados foi importado com sucesso.',

        ],

        'import_failed' => [

            'title'         => 'Falha na importação',
            'description'   => 'Não foi possível importar o arquivo devido a vários problemas. Confira seu e-mail para os detalhes.',

        ],

        'new_apps' => [

            'title'         => 'Novo app',
            'description'   => 'O app <strong>:name</strong> foi lançado. <a href=":url">Clique aqui</a> para ver os detalhes.',

        ],

        'invoice_new_customer' => [

            'title'         => 'Nova fatura',
            'description'   => 'A fatura <strong>:invoice_number</strong> foi criada. <a href=":invoice_portal_link">Clique aqui</a> para ver os detalhes e prosseguir com o pagamento.',

        ],

        'invoice_remind_customer' => [

            'title'         => 'Fatura atrasada',
            'description'   => 'A fatura <strong>:invoice_number</strong> venceu em <strong>:invoice_due_date</strong>. <a href=":invoice_portal_link">Clique aqui</a> para ver os detalhes e prosseguir com o pagamento.',

        ],

        'invoice_remind_admin' => [

            'title'         => 'Fatura atrasada',
            'description'   => 'A fatura <strong>:invoice_number</strong> venceu em <strong>:invoice_due_date</strong>. <a href=":invoice_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'invoice_recur_customer' => [

            'title'         => 'Nova fatura recorrente',
            'description'   => 'A fatura <strong>:invoice_number</strong> foi criada com base no seu ciclo recorrente. <a href=":invoice_portal_link">Clique aqui</a> para ver os detalhes e prosseguir com o pagamento.',

        ],

        'invoice_recur_admin' => [

            'title'         => 'Nova fatura recorrente',
            'description'   => 'A fatura <strong>:invoice_number</strong> foi criada com base no ciclo recorrente de <strong>:customer_name</strong>. <a href=":invoice_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'invoice_view_admin' => [

            'title'         => 'Fatura visualizada',
            'description'   => '<strong>:customer_name</strong> visualizou a fatura <strong>:invoice_number</strong>. <a href=":invoice_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'revenue_new_customer' => [

            'title'         => 'Pagamento recebido',
            'description'   => 'Obrigado pelo pagamento da fatura <strong>:invoice_number</strong>. <a href=":invoice_portal_link">Clique aqui</a> para ver os detalhes.',

        ],

        'invoice_payment_customer' => [

            'title'         => 'Pagamento recebido',
            'description'   => 'Obrigado pelo pagamento da fatura <strong>:invoice_number</strong>. <a href=":invoice_portal_link">Clique aqui</a> para ver os detalhes.',

        ],

        'invoice_payment_admin' => [

            'title'         => 'Pagamento recebido',
            'description'   => ':customer_name registrou um pagamento para a fatura <strong>:invoice_number</strong>. <a href=":invoice_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'bill_remind_admin' => [

            'title'         => 'Conta atrasada',
            'description'   => 'A conta <strong>:bill_number</strong> venceu em <strong>:bill_due_date</strong>. <a href=":bill_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'bill_recur_admin' => [

            'title'         => 'Nova conta recorrente',
            'description'   => 'A conta <strong>:bill_number</strong> foi criada com base no ciclo recorrente de <strong>:vendor_name</strong>. <a href=":bill_admin_link">Clique aqui</a> para ver os detalhes.',

        ],

        'invalid_email' => [

            'title'         => 'E-mail :type inválido',
            'description'   => 'O endereço de e-mail <strong>:email</strong> foi relatado como inválido e a pessoa foi desativada. Verifique e corrija o endereço de e-mail.',

        ],

    ],

    'messages' => [

        'mark_read'             => ':type leu esta notificação!',
        'mark_read_all'         => ':type leu todas as notificações!',

    ],

    'browser' => [

        'firefox' => [

            'title' => 'Configuração de ícones do Firefox',
            'description'  => '<span class="font-medium">Se os seus ícones não aparecerem, por favor:</span> <br /> <span class="font-medium">Permita que as páginas escolham suas próprias fontes, em vez das seleções acima</span> <br /><br /> <span class="font-bold"> Configurações (Preferências) > Fontes > Avançado </span>',

        ],

    ],

];
