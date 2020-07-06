<?php

return [

    'next'                  => 'Próximo',
    'refresh'               => 'Atualizar',

    'steps' => [
        'requirements'      => 'Por favor, peça ao seu fornecedor de alojamento web para corrigir os erros!',
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
        'executable'        => 'O ficheiro executável PHP CLI não está definido ou a funcionar, ou a sua versão do PHP não é :php_version ou superior! Por favor, peça à sua empresa de alojamento web para definir corretamente a variável de ambiente PHP_BINARY ou PHP_PATH.',
    ],

    'database' => [
        'hostname'          => 'Nome do servidor',
        'username'          => 'Nome de utilizador',
        'password'          => 'Palavra-passe',
        'name'              => 'Base de Dados',
    ],

    'settings' => [
        'company_name'      => 'Nome da empresa',
        'company_email'     => 'E-mail da empresa',
        'admin_email'       => 'E-mail do administrador',
        'admin_password'    => 'Palavra-passe do administrador',
    ],

    'error' => [
        'php_version'       => 'Erro: Peça ao seu fornecedor de alojamento web para usar PHP :php_version ou superior para HTTP e CLI.',
        'connection'        => 'Erro: Não foi possível ligar à base de dados! Por favor, verifique se a informação que inseriu está correcta.',
    ],

];
