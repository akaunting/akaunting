<?php

return [

    'company' => [
        'description'                => 'Alterar o nome da empresa, e-mail, endereço, número de imposto etc',
        'name'                       => 'Nome',
        'email'                      => 'E-mail',
        'phone'                      => 'Telefone',
        'address'                    => 'Endereço',
        'edit_your_business_address' => 'Editar seu endereço comercial',
        'logo'                       => 'Logo',
    ],

    'localisation' => [
        'description'       => 'Definir ano fiscal, fuso horário, formato de data e mais locais',
        'financial_start'   => 'Início do ano fiscal',
        'timezone'          => 'Fuso Horário',
        'financial_denote' => [
            'title'         => 'Indicar Ano Fiscal',
            'begins'        => 'Pelo ano em que começa',
            'ends'          => 'Pelo ano em que termina',
        ],
        'date' => [
            'format'        => 'Formato da Data',
            'separator'     => 'Separador de Data',
            'dash'          => 'Traço (-)',
            'dot'           => 'Ponto (.)',
            'comma'         => 'Vírgula (,)',
            'slash'         => 'Barra (/)',
            'space'         => 'Espaço ( )',
        ],
        'percent' => [
            'title'         => 'Posição do (%)',
            'before'        => 'Antes do número',
            'after'         => 'Depois do número',
        ],
        'discount_location' => [
            'name'          => 'Localização do desconto',
            'item'          => 'Na linha',
            'total'         => 'No total',
            'both'          => 'Em ambas, na linha e no total',
        ],
    ],

    'invoice' => [
        'description'       => 'Personalizar o prefixo da fatura, número, termos, rodapé etc',
        'prefix'            => 'Formato do número',
        'digit'             => 'Número de dígitos',
        'next'              => 'Próximo número',
        'logo'              => 'Logotipo',
        'custom'            => 'Personalizado',
        'item_name'         => 'Nome do Item',
        'item'              => 'Itens',
        'product'           => 'Produtos',
        'service'           => 'Serviços',
        'price_name'        => 'Nome do preço',
        'price'             => 'Preço',
        'rate'              => 'Taxa',
        'quantity_name'     => 'Nome da quantidade',
        'quantity'          => 'Quantidade',
        'payment_terms'     => 'Condições de Pagamento',
        'title'             => 'Título',
        'subheading'        => 'Subtítulo',
        'due_receipt'       => 'Vence após recebimento',
        'due_days'          => 'Vence em :days dias',
        'choose_template'   => 'Escolha o modelo da fatura',
        'default'           => 'Padrão',
        'classic'           => 'Clássico',
        'modern'            => 'Moderno',
        'hide'              => [
            'item_name'         => 'Ocultar Nome do item',
            'item_description'  => 'Ocultar descrição do item',
            'quantity'          => 'Ocultar quantidade',
            'price'             => 'Ocultar preço',
            'amount'            => 'Ocultar quantidade',
        ],
    ],

    'transfer' => [
        'choose_template'   => 'Escolher modelo de transferência',
        'second'            => 'Segundo',
        'third'             => 'Terceiro',
    ],

    'default' => [
        'description'       => 'Conta padrão, moeda, idioma de sua empresa',
        'list_limit'        => 'Registros por Página',
        'use_gravatar'      => 'Usar Gravatar',
        'income_category'   => 'Categoria de renda',
        'expense_category'  => 'Categoria de despesa',
    ],

    'email' => [
        'description'       => 'Alterar o protocolo de envio e modelos de e-mail',
        'protocol'          => 'Protocolo',
        'php'               => 'PHP Mail',
        'smtp' => [
            'name'          => 'SMTP',
            'host'          => 'SMTP Host',
            'port'          => 'SMTP Porta',
            'username'      => 'SMTP Usuário',
            'password'      => 'SMTP Senha',
            'encryption'    => 'SMTP Criptografia',
            'none'          => 'Nenhum',
        ],
        'sendmail'          => 'Sendmail',
        'sendmail_path'     => 'Sendmail Path',
        'log'               => 'Log Emails',

        'templates' => [
            'subject'                   => 'Assunto',
            'body'                      => 'Corpo',
            'tags'                      => '<strong>Tags disponíveis:</strong> :tag_list',
            'invoice_new_customer'      => 'Novo Modelo de Fatura (enviado ao cliente)',
            'invoice_remind_customer'   => 'Modelo de lembrete de fatura (enviado ao cliente)',
            'invoice_remind_admin'      => 'Modelo de lembrete de fatura (enviado para administrador)',
            'invoice_recur_customer'    => 'Modelo de Fatura Recorrente (enviado ao cliente)',
            'invoice_recur_admin'       => 'Modelo de Fatura Recorrente (enviado ao administrador)',
            'invoice_payment_customer'  => 'Modelo de Pagamento Recebido (enviado ao cliente)',
            'invoice_payment_admin'     => 'Modelo de Pagamento Recebido (enviado ao administrador)',
            'bill_remind_admin'         => 'Modelo de Lembrete de Fatura (enviado ao administrador)',
            'bill_recur_admin'          => 'Modelo de cobrança recorrente (enviado ao administrador)',
            'revenue_new_customer'      => 'Modelo de Pagamento Recebido (enviado ao cliente)',
        ],
    ],

    'scheduling' => [
        'name'              => 'Agendamento',
        'description'       => 'Lembretes automáticos e comando para recorrentes',
        'send_invoice'      => 'Enviar lembrete de faturas',
        'invoice_days'      => 'Enviar após dias de vencimento',
        'send_bill'         => 'Enviar lembrança',
        'bill_days'         => 'Enviar antes de vencer',
        'cron_command'      => 'Comando Cron',
        'schedule_time'     => 'Iniciar Cron',
    ],

    'categories' => [
        'description'       => 'Categorias ilimitadas para receita, despesa e item',
    ],

    'currencies' => [
        'description'       => 'Crie e gerencie moedas e defina suas taxas',
    ],

    'taxes' => [
        'description'       => 'Taxas de imposto fixas, normais, inclusivas e compostas',
    ],

];
