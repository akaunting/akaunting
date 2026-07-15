<?php

return [

    'next'                  => 'Próximo',
    'refresh'               => 'Atualizar',

    'steps' => [
        'requirements'      => 'Por favor, peça ao seu provedor de hospedagem para corrigir os erros!',
        'language'          => 'Passo 1/3 : Seleção de idioma',
        'database'          => 'Passo 2/3 : Configuração do banco de dados',
        'settings'          => 'Passo 3/3 : Detalhes da empresa e do administrador',
    ],

    'language' => [
        'select'            => 'Selecionar idioma',
    ],

    'requirements' => [
        'enabled'           => ':feature precisa estar habilitado!',
        'disabled'          => ':feature precisa estar desabilitado!',
        'extension'         => 'A extensão :extension precisa ser instalada e carregada!',
        'directory'         => 'O diretório :directory precisa ter permissão de escrita!',
        'executable'        => 'O arquivo executável do PHP CLI não está definido/funcionando ou sua versão não é :php_version ou superior! Por favor, peça à sua empresa de hospedagem que defina corretamente a variável de ambiente PHP_BINARY ou PHP_PATH.',
        'npm'               => '<b>Arquivos JavaScript ausentes!</b> <br><br><span>Você deve executar os comandos <em class="underline">npm install</em> e <em class="underline">npm run dev</em>.</span>',
    ],

    'database' => [
        'hostname'          => 'Nome do servidor',
        'username'          => 'Nome de usuário',
        'password'          => 'Senha',
        'name'              => 'Banco de dados',
    ],

    'settings' => [
        'company_name'      => 'Nome da empresa',
        'company_email'     => 'E-mail da empresa',
        'admin_email'       => 'E-mail do administrador',
        'admin_password'    => 'Senha do administrador',
    ],

    'error' => [
        'php_version'       => 'Erro: Peça ao seu provedor de hospedagem para usar PHP :php_version ou superior tanto para HTTP quanto para CLI.',
        'connection'        => 'Erro: Não foi possível conectar ao banco de dados! Por favor, certifique-se de que os detalhes estão corretos.',
    ],

    'update' => [
        'core'              => 'Uma nova versão do Akaunting está disponível! Por favor, atualize <a href=":url">sua instalação.</a>',
        'module'            => 'Uma nova versão de :module está disponível! Por favor, atualize <a href=":url">sua instalação.</a>',
    ],
];
