<?php

return [

    'company' => [
        'name'              => 'Nome',
        'email'             => 'E-mail',
        'phone'             => 'Telefone',
        'address'           => 'Endereço',
        'logo'              => 'Logotipo',
    ],
    'localisation' => [
        'tab'               => 'Localização',
        'date' => [
            'format'        => 'Formato da Data',
            'separator'     => 'Separador da Data',
            'dash'          => 'Traço (-)',
            'dot'           => 'Ponto (.)',
            'comma'         => 'Vírgula (,)',
            'slash'         => 'Barra (/)',
            'space'         => 'Espaço ( )',
        ],
        'timezone'          => 'Fuso Horário',
        'percent' => [
            'title'         => 'Posição do símbolo de percentagem (%)',
            'before'        => 'Antes do Número',
            'after'         => 'Depois do Número',
        ],
    ],
    'invoice' => [
        'tab'               => 'Faturas',
        'prefix'            => 'Prefixo',
        'digit'             => 'Quantidade de dígitos',
        'next'              => 'Próximo Número',
        'logo'              => 'Logotipo',
    ],
    'default' => [
        'tab'               => 'Padrões',
        'account'           => 'Conta Padrão',
        'currency'          => 'Moeda Padrão',
        'tax'               => 'Imposto Padrão',
        'payment'           => 'Método de Pagamento Padrão',
        'language'          => 'Idioma Padrão',
    ],
    'email' => [
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
    ],
    'scheduling' => [
        'tab'               => 'Agendamento',
        'send_invoice'      => 'Enviar lembrete de faturas',
        'invoice_days'      => 'Enviar após dias de vencimento',
        'send_bill'         => 'Enviar lembrete de Conta',
        'bill_days'         => 'Enviar antes de vencer',
        'cron_command'      => 'Comando Cron',
        'schedule_time'     => 'Iniciar Cron',
    ],
    'appearance' => [
        'tab'               => 'Aparência',
        'theme'             => 'Tema',
        'light'             => 'Claro',
        'dark'              => 'Escuro',
        'list_limit'        => 'Resultados por Página',
        'use_gravatar'      => 'Usar Gravatar',
    ],
    'system' => [
        'tab'               => 'Sistema',
        'session' => [
            'lifetime'      => 'Fechar sessão (Minutos)',
            'handler'       => 'Gestor de Sessão',
            'file'          => 'Ficheiro',
            'database'      => 'Base de Dados',
        ],
        'file_size'         => 'Tamanho máximo do ficheiro (MB)',
        'file_types'        => 'Tipos de ficheiros permitidos',
    ],

];
