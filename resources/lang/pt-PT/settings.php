<?php

return [

    'company' => [
        'description'       => 'Alterar nome da empresa, e-mail, endereço, número fiscal etc',
        'name'              => 'Nome',
        'email'             => 'E-mail',
        'phone'             => 'Telefone',
        'address'           => 'Endereço',
        'logo'              => 'Logótipo',
    ],

    'localisation' => [
        'description'       => 'Definir o ano fiscal, fuso horário, formato da data e localizações',
        'financial_start'   => 'Início do ano fiscal',
        'timezone'          => 'Fuso Horário',
        'date' => [
            'format'        => 'Formato da Data',
            'separator'     => 'Separador da Data',
            'dash'          => 'Traço (-)',
            'dot'           => 'Ponto (.)',
            'comma'         => 'Vírgula (,)',
            'slash'         => 'Barra (/)',
            'space'         => 'Espaço ( )',
        ],
        'percent' => [
            'title'         => 'Posição do símbolo de percentagem (%)',
            'before'        => 'Antes do Número',
            'after'         => 'Depois do Número',
        ],
        'discount_location' => [
            'name'          => 'Localização do Desconto',
            'item'          => 'Por linha',
            'total'         => 'No total',
            'both'          => 'Em ambos (linha e total)',
        ],
    ],

    'invoice' => [
        'description'       => 'Personalizar prefixo de fatura, número, termos, rodapé etc',
        'prefix'            => 'Prefixo',
        'digit'             => 'Quantidade de dígitos',
        'next'              => 'Próximo Número',
        'logo'              => 'Logótipo',
        'custom'            => 'Personalizado',
        'item_name'         => 'Nome do Item',
        'item'              => 'Itens',
        'product'           => 'Produtos',
        'service'           => 'Serviços',
        'price_name'        => 'Nome do Preço',
        'price'             => 'Preço',
        'rate'              => 'Taxa',
        'quantity_name'     => 'Nome de Quantidade',
        'quantity'          => 'Quantidade',
        'payment_terms'     => 'Termos de Pagamento',
        'title'             => 'Título',
        'subheading'        => 'Subtítulo',
        'due_receipt'       => 'Vencida ao receber',
        'due_days'          => 'Vencida após :days dias',
        'choose_template'   => 'Escolher modelo da Fatura',
        'default'           => 'Padrão',
        'classic'           => 'Clássico',
        'modern'            => 'Moderno',
    ],

    'default' => [
        'description'       => 'Conta padrão, moeda, idioma da sua empresa',
        'list_limit'        => 'Registos por página',
        'use_gravatar'      => 'Usar Gravatar',
    ],

    'email' => [
        'description'       => 'Alterar o protocolo de envio e modelos de e-mail',
        'protocol'          => 'Protocolo',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'Servidor SMTP',
            'port'          => 'Porta SMTP',
            'username'      => 'Utilizador SMTP',
            'password'      => 'Palavra-passe SMTP',
            'encryption'    => 'Encriptação SMTP',
            'none'          => 'Nenhum',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Localização Sendmail',
        'log'               => 'Registo de e-mails',

        'templates' => [
            'subject'                   => 'Assunto',
            'body'                      => 'Conteúdo',
            'tags'                      => '<strong>Etiquetas Disponíveis:</strong> :tag_list',
            'invoice_new_customer'      => 'Novo modelo de Fatura (envio ao cliente)',
            'invoice_remind_customer'   => 'Modelo de lembrete de Fatura (envio ao cliente)',
            'invoice_remind_admin'      => 'Modelo de lembrete de Fatura (envio ao administrador)',
            'invoice_recur_customer'    => 'Modelo de Fatura recorrente (envio ao cliente)',
            'invoice_recur_admin'       => 'Modelo de Fatura recorrente (envio ao administrador)',
            'invoice_payment_customer'  => 'Modelo de Pagamento recebido (envio ao cliente)',
            'invoice_payment_admin'     => 'Modelo de Pagamento recebido (envio ao administrador)',
            'bill_remind_admin'         => 'Modelo de lembrete de Conta (envio ao administrador)',
            'bill_recur_admin'          => 'Modelo de Conta recorrente (envio ao administrador)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Agendado',
        'description'       => 'Lembretes automáticos e comandos para recorrentes agendados',
        'send_invoice'      => 'Enviar lembrete de faturas',
        'invoice_days'      => 'Enviar após dias de vencimento',
        'send_bill'         => 'Enviar lembrete de Contas',
        'bill_days'         => 'Enviar antes de vencer',
        'cron_command'      => 'Comando Cron',
        'schedule_time'     => 'Iniciar Cron',
    ],

    'categories' => [
        'description'       => 'Categorias ilimitadas para receita, despesa, e itens',
    ],

    'currencies' => [
        'description'       => 'Criar e gerir moedas e definir as taxas',
    ],

    'taxes' => [
        'description'       => 'Taxas de imposto fixa, normal, inclusiva e composta',
    ],

];
