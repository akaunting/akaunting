<?php

return [

    'next'                  => 'Próximo',
    'refresh'               => 'Atualizar',

    'steps' => [
        'requirements'      => 'Por favor, peça ao seu fornecedor de hospedagem para corrigir os erros!',
        'language'          => 'Passo 1/3: Selecionar idioma',
        'database'          => 'Passo 2/3: Configuração da base de dados',
        'settings'          => 'Passo 3/3: Detalhes da empresa e do administrador',
    ],

    'language' => [
        'select'            => 'Selecionar Idioma',
    ],

    'requirements' => [
        'enabled'           => ':feature precisa estar ativada!',
        'disabled'          => ':feature precisa estar desativada!',
        'extension'         => ':extensão extensão precisa estar instalada e carregada!',
        'directory'         => 'O diretório :directory precisa de permissão para escrita!',
    ],

    'database' => [
        'hostname'          => 'Nome do servidor',
        'username'          => 'Nome de utilizador',
        'password'          => 'Senha',
        'name'              => 'Base de Dados',
    ],

    'settings' => [
        'company_name'      => 'Nome da empresa',
        'company_email'     => 'E-mail da empresa',
        'admin_email'       => 'E-mail do administrador',
        'admin_password'    => 'Senha do administrador',
    ],

    'error' => [
        'connection'        => 'Erro: Não foi possível ligar à base de dados! Por favor, verifique se a informação que inseriu está correcta.',
    ],

];
