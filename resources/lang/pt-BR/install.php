<?php

return [

    'next'                  => 'Próximo',
    'refresh'               => 'Atualizar',

    'steps' => [
        'requirements'      => 'Por favor, consulte o seu provedor de hospedagem para corrigir os erros!',
        'language'          => 'Passo 1/3 : Selecionar idioma',
        'database'          => 'Passo 2/3 : Configuração do Banco de Dados',
        'settings'          => 'Passo 3/3 : Detalhes da empresa e do administrador',
    ],

    'language' => [
        'select'            => 'Selecionar Idioma',
    ],

    'requirements' => [
        'enabled'           => ':feature precisa esta habilitado!',
        'disabled'          => ':feature precisa esta desabilitado!',
        'extension'         => 'Extensão :extension precisa ser instalado e carregado!',
        'directory'         => 'O diretório :directory precisa de permissão para escrita!',
    ],

    'database' => [
        'hostname'          => 'Nome do servidor',
        'username'          => 'Nome de usuário',
        'password'          => 'Senha',
        'name'              => 'Banco de Dados',
    ],

    'settings' => [
        'company_name'      => 'Nome da empresa',
        'company_email'     => 'E-mail da empresa',
        'admin_email'       => 'E-mail do Admin',
        'admin_password'    => 'Senha do Admin',
    ],

    'error' => [
        'connection'        => 'Erro: Não foi possível conectar ao banco de dados! Certifique-se de que os detalhes estão corretos.',
    ],

];
