<?php

return [

    'company' => [
        'description'       => 'Change company name, email, address, tax number etc',
        'name'              => 'Nome',
        'email'             => 'E-mail',
        'phone'             => 'Telefone',
        'address'           => 'Endereço',
        'logo'              => 'Logótipo',
    ],

    'localisation' => [
        'description'       => 'Set fiscal year, time zone, date format and more locals',
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
    ],

    'invoice' => [
        'description'       => 'Customize invoice prefix, number, terms, footer etc',
        'prefix'            => 'Prefixo',
        'digit'             => 'Quantidade de dígitos',
        'next'              => 'Próximo Número',
        'logo'              => 'Logotipo',
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
        'due_receipt'       => 'Due upon receipt',
        'due_days'          => 'Due within :days days',
        'choose_template'   => 'Choose invoice template',
        'default'           => 'Padrão',
        'classic'           => 'Clássico',
        'modern'            => 'Moderno',
    ],

    'default' => [
        'description'       => 'Default account, currency, language of your company',
        'list_limit'        => 'Records Per Page',
        'use_gravatar'      => 'Usar Gravatar',
    ],

    'email' => [
        'description'       => 'Change the sending protocol and email templates',
        'protocol'          => 'Protocolo',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'Servidor SMTP',
            'port'          => 'Porta SMTP',
            'username'      => 'Utilizador SMTP',
            'password'      => 'Senha SMTP',
            'encryption'    => 'Encriptação SMTP',
            'none'          => 'Nenhum',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Localização Sendmail',
        'log'               => 'Registo de e-mails',

        'templates' => [
            'subject'                   => 'Assunto',
            'body'                      => 'Conteúdo',
            'tags'                      => '<strong>Available Tags:</strong> :tag_list',
            'invoice_new_customer'      => 'New Invoice Template (sent to customer)',
            'invoice_remind_customer'   => 'Invoice Reminder Template (sent to customer)',
            'invoice_remind_admin'      => 'Invoice Reminder Template (sent to admin)',
            'invoice_recur_customer'    => 'Invoice Recurring Template (sent to customer)',
            'invoice_recur_admin'       => 'Invoice Recurring Template (sent to admin)',
            'invoice_payment_customer'  => 'Payment Received Template (sent to customer)',
            'invoice_payment_admin'     => 'Payment Received Template (sent to admin)',
            'bill_remind_admin'         => 'Bill Reminder Template (sent to admin)',
            'bill_recur_admin'          => 'Bill Recurring Template (sent to admin)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Scheduling',
        'description'       => 'Automatic reminders and command for recurring',
        'send_invoice'      => 'Enviar lembrete de faturas',
        'invoice_days'      => 'Enviar após dias de vencimento',
        'send_bill'         => 'Enviar lembrete de Contas',
        'bill_days'         => 'Enviar antes de vencer',
        'cron_command'      => 'Comando Cron',
        'schedule_time'     => 'Iniciar Cron',
    ],

    'categories' => [
        'description'       => 'Unlimited categories for income, expense, and item',
    ],

    'currencies' => [
        'description'       => 'Create and manage currencies and set their rates',
    ],

    'taxes' => [
        'description'       => 'Fixed, normal, inclusive, and compound tax rates',
    ],

];
