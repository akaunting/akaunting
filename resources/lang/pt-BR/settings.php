<?php

return [

    'company' => [
        'description'                   => 'Alterar o nome da empresa, e-mail, endereço, número fiscal, etc.',
        'search_keywords'               => 'empresa, nome, e-mail, telefone, endereço, país, número fiscal, logotipo, cidade, vila, estado, província, código postal',
        'name'                          => 'Nome',
        'email'                         => 'E-mail',
        'phone'                         => 'Telefone',
        'address'                       => 'Endereço',
        'edit_your_business_address'    => 'Edite o endereço da sua empresa',
        'logo'                          => 'Logotipo',

        'form_description' => [
            'general'                   => 'Estas informações são visíveis nos registros que você criar.',
            'address'                   => 'O endereço será usado nas faturas, contas e outros registros que você emitir.',
        ],
    ],

    'localisation' => [
        'description'                   => 'Definir ano fiscal, fuso horário, formato de data e mais localizações',
        'search_keywords'               => 'financeiro, ano, início, indicar, hora, zona, data, formato, separador, desconto, porcentagem',
        'financial_start'               => 'Início do ano fiscal',
        'timezone'                      => 'Fuso horário',
        'financial_denote' => [
            'title'                     => 'Indicação do ano fiscal',
            'begins'                    => 'Pelo ano em que começa',
            'ends'                      => 'Pelo ano em que termina',
        ],
        'preferred_date'                => 'Data preferida',
        'date' => [
            'format'                    => 'Formato de data',
            'separator'                 => 'Separador de data',
            'dash'                      => 'Traço (-)',
            'dot'                       => 'Ponto (.)',
            'comma'                     => 'Vírgula (,)',
            'slash'                     => 'Barra (/)',
            'space'                     => 'Espaço ( )',
        ],
        'percent' => [
            'title'                     => 'Posição da porcentagem (%)',
            'before'                    => 'Antes do número',
            'after'                     => 'Depois do número',
        ],
        'discount_location' => [
            'name'                      => 'Localização do desconto',
            'item'                      => 'Na linha',
            'total'                     => 'No total',
            'both'                      => 'Tanto na linha quanto no total',
        ],

        'form_description' => [
            'fiscal'                    => 'Defina o período do ano fiscal que sua empresa utiliza para tributação e relatórios.',
            'date'                      => 'Selecione o formato de data que você deseja ver em todos os lugares da interface.',
            'other'                     => 'Selecione onde o sinal de porcentagem é exibido para os impostos. Você pode habilitar descontos em itens de linha e no total das faturas e contas.',
        ],
    ],

    'invoice' => [
        'description'                   => 'Personalizar prefixo da fatura, número, termos, rodapé, etc.',
        'search_keywords'               => 'personalizar, fatura, número, prefixo, dígito, próximo, logotipo, nome, preço, quantidade, modelo, título, subtítulo, rodapé, nota, ocultar, vencimento, cor, pagamento, termos, coluna',
        'prefix'                        => 'Prefixo do número',
        'digit'                         => 'Número de dígitos',
        'next'                          => 'Próximo número',
        'logo'                          => 'Logotipo',
        'custom'                        => 'Personalizado',
        'item_name'                     => 'Nome do item',
        'item'                          => 'Itens',
        'product'                       => 'Produtos',
        'service'                       => 'Serviços',
        'price_name'                    => 'Nome do preço',
        'price'                         => 'Preço',
        'rate'                          => 'Taxa',
        'quantity_name'                 => 'Nome da quantidade',
        'quantity'                      => 'Quantidade',
        'payment_terms'                 => 'Condições de pagamento',
        'title'                         => 'Título',
        'subheading'                    => 'Subtítulo',
        'due_receipt'                   => 'Vence no recebimento',
        'due_days'                      => 'Vence em :days dias',
        'due_custom'                    => 'Dia(s) personalizado(s)',
        'due_custom_day'                => 'após o dia',
        'choose_template'               => 'Escolha o modelo de fatura',
        'default'                       => 'Padrão',
        'classic'                       => 'Clássico',
        'modern'                        => 'Moderno',
        'logo_size_width'               => 'Largura do logotipo',
        'logo_size_height'              => 'Altura do logotipo',
        'hide' => [
            'item_name'                 => 'Ocultar nome do item',
            'item_description'          => 'Ocultar descrição do item',
            'quantity'                  => 'Ocultar quantidade',
            'price'                     => 'Ocultar preço',
            'amount'                    => 'Ocultar valor',
        ],
        'column'                        => 'Coluna|Colunas',

        'form_description' => [
            'general'                   => 'Defina os padrões para a formatação de seus números de fatura e condições de pagamento.',
            'template'                  => 'Selecione um dos modelos abaixo para suas faturas.',
            'default'                   => 'A seleção de padrões para faturas pré-preencherá títulos, subtítulos, notas e rodapés. Assim, não é necessário editar as faturas a cada vez para parecer mais profissional.',
            'column'                    => 'Personalize como as colunas da fatura são nomeadas. Se desejar ocultar descrições de itens e valores nas linhas, você pode alterar aqui.',
        ]
    ],

    'transfer' => [
        'choose_template'               => 'Escolha o modelo de transferência',
        'second'                        => 'Segundo',
        'third'                         => 'Terceiro',
    ],

    'default' => [
        'description'                   => 'Conta, moeda e idioma padrão da sua empresa',
        'search_keywords'               => 'conta, moeda, idioma, imposto, pagamento, método, paginação',
        'list_limit'                    => 'Registros por página',
        'use_gravatar'                  => 'Usar Gravatar',
        'income_category'               => 'Categoria de receita',
        'expense_category'              => 'Categoria de despesa',
        'address_format'                => 'Formato de endereço',
        'address_tags'                  => '<strong>Tags disponíveis:</strong> :tags',

        'form_description' => [
            'general'                   => 'Selecione a conta padrão, o imposto e a forma de pagamento para criar registros rapidamente. O painel e os relatórios são exibidos na moeda padrão.',
            'category'                  => 'Selecione as categorias padrão para agilizar a criação de registros.',
            'other'                     => 'Personalize as configurações padrão do idioma da empresa e o funcionamento da paginação.',
        ],
    ],

    'email' => [
        'description'                   => 'Alterar o protocolo de envio',
        'search_keywords'               => 'e-mail, enviar, protocolo, smtp, host, senha',
        'protocol'                      => 'Protocolo',
        'php'                           => 'PHP Mail',
        'smtp' => [
            'name'                      => 'SMTP',
            'host'                      => 'Host SMTP',
            'port'                      => 'Porta SMTP',
            'username'                  => 'Usuário SMTP',
            'password'                  => 'Senha SMTP',
            'encryption'                => 'Criptografia SMTP',
            'none'                      => 'Nenhum',
        ],
        'sendmail'                      => 'Sendmail',
        'sendmail_path'                 => 'Caminho do Sendmail',
        'log'                           => 'Registrar e-mails',
        'email_service'                 => 'Serviço de e-mail',
        'email_templates'               => 'Modelos de e-mail',

        'form_description' => [
            'general'                   => 'Envie e-mails regulares para sua equipe e contatos. Você pode definir o protocolo e as configurações de SMTP.',
        ],

        'templates' => [
            'description'               => 'Altere os modelos de e-mail',
            'search_keywords'           => 'e-mail, modelo, assunto, corpo, tag',
            'subject'                   => 'Assunto',
            'body'                      => 'Corpo',
            'tags'                      => '<strong>Tags disponíveis:</strong> :tag_list',
            'invoice_new_customer'      => 'Modelo de nova fatura (enviado ao cliente)',
            'invoice_remind_customer'   => 'Modelo de lembrete de fatura (enviado ao cliente)',
            'invoice_remind_admin'      => 'Modelo de lembrete de fatura (enviado ao administrador)',
            'invoice_recur_customer'    => 'Modelo de fatura recorrente (enviado ao cliente)',
            'invoice_recur_admin'       => 'Modelo de fatura recorrente (enviado ao administrador)',
            'invoice_view_admin'        => 'Modelo de fatura visualizada (enviado ao administrador)',
            'invoice_payment_customer'  => 'Modelo de recibo de pagamento (enviado ao cliente)',
            'invoice_payment_admin'     => 'Modelo de pagamento recebido (enviado ao administrador)',
            'bill_remind_admin'         => 'Modelo de lembrete de conta (enviado ao administrador)',
            'bill_recur_admin'          => 'Modelo de conta recorrente (enviado ao administrador)',
            'payment_received_customer' => 'Modelo de recibo de pagamento (enviado ao cliente)',
            'payment_made_vendor'       => 'Modelo de pagamento efetuado (enviado ao fornecedor)',
        ],
    ],

    'scheduling' => [
        'name'                          => 'Agendamento',
        'description'                   => 'Lembretes automáticos e comando para recorrentes',
        'search_keywords'               => 'automático, lembrete, recorrente, cron, comando',
        'send_invoice'                  => 'Enviar lembrete de fatura',
        'invoice_days'                  => 'Enviar após dias do vencimento',
        'send_bill'                     => 'Enviar lembrete de conta',
        'bill_days'                     => 'Enviar antes do vencimento',
        'cron_command'                  => 'Comando cron',
        'command'                       => 'Comando',
        'schedule_time'                 => 'Hora de execução',

        'form_description' => [
            'invoice'                   => 'Ative ou desative e defina lembretes para suas faturas quando estiverem atrasadas.',
            'bill'                      => 'Ative ou desative e defina lembretes para suas contas antes que estejam atrasadas.',
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
        'search_keywords'               => 'imposto, taxa, tipo, fixo, inclusivo, composto, retido',
    ],

];
