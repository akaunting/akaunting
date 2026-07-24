<?php

return [

    'company' => [
        'description'                   => 'Alterar o nome da empresa, e-mail, endereço, número de imposto etc',
        'search_keywords'               => 'empresa, nome, e-mail, telefone, endereço, país, número fiscal, logotipo, cidade, vilarejo, estado, província, código postal',
        'name'                          => 'Nome',
        'email'                         => 'E-mail',
        'phone'                         => 'Telefone',
        'address'                       => 'Endereço',
        'edit_your_business_address'    => 'Editar seu endereço comercial',
        'logo'                          => 'Logo',

        'form_description' => [
            'general'                   => 'Essa informação está visível nos registros que criar.',
            'address'                   => 'O endereço será usado nas faturas, contas e outros registros que solicitar.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Definir ano fiscal, fuso horário, formato de data e mais locais',
        'search_keywords'               => 'financeiro, ano, início, denote, hora, zona, data, formato, separador, desconto, porcentagem',
        'financial_start'               => 'Início do ano fiscal',
        'timezone'                      => 'Fuso Horário',
        'financial_denote' => [
            'title'                     => 'Indicar Ano Fiscal',
            'begins'                    => 'Pelo ano em que começa',
            'ends'                      => 'Pelo ano em que termina',
        ],
        'preferred_date'                => 'Data preferida',
        'date' => [
            'format'                    => 'Formato da Data',
            'separator'                 => 'Separador de Data',
            'dash'                      => 'Traço (-)',
            'dot'                       => 'Ponto (.)',
            'comma'                     => 'Vírgula (,)',
            'slash'                     => 'Barra (/)',
            'space'                     => 'Espaço ( )',
        ],
        'percent' => [
            'title'                     => 'Posição do (%)',
            'before'                    => 'Antes do número',
            'after'                     => 'Depois do número',
        ],
        'discount_location' => [
            'name'                      => 'Localização do desconto',
            'item'                      => 'Na linha',
            'total'                     => 'No total',
            'both'                      => 'Em ambas, na linha e no total',
        ],

        'form_description' => [
            'fiscal'                    => 'Defina o período do ano fiscal que sua empresa utiliza para a tributação e relatórios.',
            'date'                      => 'Selecione o formato de data que deseja ver em todos os lugares na interface.',
            'other'                     => 'Selecione o local onde o sinal de percentual é exibido para os impostos. É possível habilitar descontos em itens de linha, no total das faturas e nas contas.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Personalizar o prefixo da fatura, número, termos, rodapé etc',
        'search_keywords'               => 'personalizar, fatura, número, prefixo, dígito, próxima, logotipo, nome, preço, quantidade, modelo, título, subtítulo, rodapé, nota, ocultar, vencimento, cor, pagamento, termos, coluna',
        'prefix'                        => 'Formato do número',
        'digit'                         => 'Número de dígitos',
        'next'                          => 'Próximo número',
        'logo'                          => 'Logotipo',
        'custom'                        => 'Personalizado',
        'item_name'                     => 'Nome do Item',
        'item'                          => 'Itens',
        'product'                       => 'Produtos',
        'service'                       => 'Serviços',
        'price_name'                    => 'Nome do preço',
        'price'                         => 'Preço',
        'rate'                          => 'Taxa',
        'quantity_name'                 => 'Nome da quantidade',
        'quantity'                      => 'Quantidade',
        'payment_terms'                 => 'Condições de Pagamento',
        'title'                         => 'Título',
        'subheading'                    => 'Subtítulo',
        'due_receipt'                   => 'Vence após recebimento',
        'due_days'                      => 'Vence em :days dias',
        'choose_template'               => 'Escolha o modelo da fatura',
        'default'                       => 'Padrão',
        'classic'                       => 'Clássico',
        'modern'                        => 'Moderno',
        'hide' => [
            'item_name'                 => 'Ocultar Nome do item',
            'item_description'          => 'Ocultar descrição do item',
            'quantity'                  => 'Ocultar quantidade',
            'price'                     => 'Ocultar preço',
            'amount'                    => 'Ocultar quantidade',
        ],
        'column'                        => 'Coluna|Colunas',

        'form_description' => [
            'general'                   => 'Defina os padrões para a formatação de seus números de fatura e termos de pagamento.',
            'template'                  => 'Selecione um dos modelos abaixo para suas faturas.',
            'default'                   => 'A seleção de padrões para faturas pré-populará títulos, subtítulos, notas e rodapés. Portanto, não precisa editar as faturas de cada vez para parecer mais profissional.',
            'column'                    => 'Personalize como as colunas da fatura são nomeadas. Se desejar ocultar as descrições dos itens e os valores em linhas, é possível modificar aqui.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Escolher modelo de transferência',
        'second'                        => 'Segundo',
        'third'                         => 'Terceiro',
    ],

    'default' => [
        'description'                   => 'Conta padrão, moeda, idioma de sua empresa',
        'search_keywords'               => 'conta, moeda, idioma, imposto, pagamento, método, paginação',
        'list_limit'                    => 'Registros por Página',
        'use_gravatar'                  => 'Usar Gravatar',
        'income_category'               => 'Categoria de renda',
        'expense_category'              => 'Categoria de despesa',

        'form_description' => [
            'general'                   => 'Selecione a conta padrão, o imposto e a forma de pagamento para criar registros rapidamente. O Painel e os Relatórios são exibidos na moeda padrão.',
            'category'                  => 'Selecione as categorias padrão para acelerar a criação de registros.',
            'other'                     => 'Personalize as configurações padrão do idioma da empresa e como a paginação funciona. ',
        ],
    ],

    'email' => [
        'description'                   => 'Alterar o protocolo de envio e modelos de e-mail',
        'search_keywords'               => 'e-mail, envio, protocolo, smtp, host, senha',
        'protocol'                      => 'Protocolo',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'SMTP Host',
            'port'                      => 'SMTP Porta',
            'username'                  => 'SMTP Usuário',
            'password'                  => 'SMTP Senha',
            'encryption'                => 'SMTP Criptografia',
            'none'                      => 'Nenhum',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Sendmail Path',
        'log'                           => 'Log Emails',
        'email_service'                 => 'Serviço de e-mail',
        'email_templates'               => 'Modelos de e-mail',

        'form_description' => [
            'general'                   => 'Envie e-mails regulares para sua equipe e contatos. É possível definir o protocolo e as configurações de SMTP.',
        ],

        'templates' => [
            'description'               => 'Alterar os modelos de e-mail',
            'search_keywords'           => 'e-mail, modelo, assunto, corpo, tag',
            'subject'                   => 'Assunto',
            'body'                      => 'Corpo',
            'tags'                      => '<strong>Tags disponíveis:</strong> :tag_list',
            'invoice_new_customer'      => 'Modelo de Fatura Criada (enviado ao cliente)',
            'invoice_remind_customer'   => 'Modelo de lembrete de fatura (enviado ao cliente)',
            'invoice_remind_admin'      => 'Modelo de lembrete de fatura (enviado para administrador)',
            'invoice_recur_customer'    => 'Modelo de Fatura Recorrente (enviado ao cliente)',
            'invoice_recur_admin'       => 'Modelo de Fatura Recorrente (enviado ao administrador)',
            'invoice_view_admin'        => 'Modelo de fatura visualizada (enviado para administrador)',
            'invoice_payment_customer'  => 'Modelo de Pagamento Recebido (enviado ao cliente)',
            'invoice_payment_admin'     => 'Modelo de Pagamento Recebido (enviado ao administrador)',
            'bill_remind_admin'         => 'Modelo de Lembrete de Conta (enviado ao administrador)',
            'bill_recur_admin'          => 'Modelo de cobrança recorrente (enviado ao administrador)',
            'payment_received_customer' => 'Modelo de recibo de pagamento (enviado para cliente)',
            'payment_made_vendor'       => 'Modelo de pagamento feito (enviado para fornecedor)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Agendamento',
        'description'                   => 'Lembretes automáticos e comando para recorrentes',
        'search_keywords'               => 'automático, lembrete, recorrente, cron, comando',
        'send_invoice'                  => 'Enviar lembrete de faturas',
        'invoice_days'                  => 'Enviar após dias de vencimento',
        'send_bill'                     => 'Enviar lembrança',
        'bill_days'                     => 'Enviar antes de vencer',
        'cron_command'                  => 'Comando Cron',
        'command'                       => 'Comando',
        'schedule_time'                 => 'Iniciar Cron',

        'form_description' => [
            'invoice'                   => 'Ativa ou desativa e define lembretes para as suas faturas quando elas estiverem atrasadas.',
            'bill'                      => 'Ativa ou desativa e define lembretes para suas contas antes que elas estejam atrasadas.',
            'cron'                      => 'Copie o comando cron que seu servidor deve executar. Defina a hora para acionar o evento.',
        ]
    ],

    'categories' => [
        'description'                   => 'Categorias ilimitadas para receita, despesa e item',
        'search_keywords'               => 'categoria, receita, despesa, item',
    ],

    'currencies' => [
        'description'                   => 'Crie e gerencie moedas e defina suas taxas',
        'search_keywords'               => 'padrão, moeda, moedas, código, taxa, símbolo, precisão, posição, decimal, milhares, marca, separador',
    ],

    'taxes' => [
        'description'                   => 'Taxas de imposto fixas, normais, inclusivas e compostas',
        'search_keywords'               => 'imposto, taxa, tipo, fixado, inclusivo, composto, retido',
    ],

];
