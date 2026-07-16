<?php

return [

    'auth'                  => 'Autenticação',
    'profile'               => 'Perfil',
    'logout'                => 'Sair',
    'login'                 => 'Logar',
    'forgot'                => 'Esqueci',
    'login_to'              => 'Entre para iniciar sua sessão',
    'remember_me'           => 'Lembrar-me',
    'forgot_password'       => 'Lembrar senha',
    'reset_password'        => 'Resetar Senha',
    'change_password'       => 'Alterar senha',
    'enter_email'           => 'Entre com o seu endereço de e-mail',
    'current_email'         => 'E-mail atual',
    'reset'                 => 'Resetar',
    'never'                 => 'Nunca',
    'landing_page'          => 'Página Inicial',
    'personal_information'  => 'Informações pessoais',
    'register_user'         => 'Registrar usuário',
    'register'              => 'Registre-se',

    'form_description' => [
        'personal'          => 'O link do convite será enviado para o novo usuário, então certifique-se de que o endereço de e-mail esteja correto. Eles poderão digitar sua senha.',
        'assign'            => 'O usuário terá acesso às empresas selecionadas. É possível restringir as permissões na página <a href=":url" class="border-b border-black">funções</a>.',
        'preferences'       => 'Selecione o idioma padrão do usuário. Também é possível definir a página inicial após o login do usuário.',
    ],

    'password' => [
        'pass'              => 'Senha',
        'pass_confirm'      => 'Confirmação da senha',
        'current'           => 'Senha',
        'current_confirm'   => 'Confirmação da Senha',
        'new'               => 'Nova Senha',
        'new_confirm'       => 'Confirmação da Nova Senha',
    ],

    'error' => [
        'self_delete'       => 'Erro: não pode se excluir!',
        'self_disable'      => 'Erro: Você não pode desativar você mesmo!',
        'unassigned'        => 'Erro: não pode deixar de ser atribuído empresa! A :company deve ser atribuída pelo menos um usuário.',
        'no_company'        => 'Erro: Nenhuma empresa atribuída à sua conta. Por favor, entre em contato com o administrador do sistema.',
    ],

    'login_redirect'        => 'Verificação concluída! Você será redirecionado...',
    'failed'                => 'Essas credenciais não correspondem aos nossos registros.',
    'throttle'              => 'Muitas tentativas de login. Tente novamente em :seconds segundos.',
    'disabled'              => 'Esta conta está desabilitada. Por favor, entre em contato com o administrador do sistema.',

    'notification' => [
        'message_1'         => 'Você está recebendo este e-mail porque recebemos um pedido de redefinição de senha para sua conta.',
        'message_2'         => 'Se você não pediu uma redefinição de senha, nenhuma ação adicional é necessária.',
        'button'            => 'Recuperar senha',
    ],

    'invitation' => [
        'message_1'         => 'Você está recebendo este e-mail porque foi convidado a juntar-se ao Akaunting.',
        'message_2'         => 'Se não quiser se juntar, nenhuma outra ação é requerida.',
        'button'            => 'Iniciar',
    ],

    'information' => [
        'invoice'           => 'Criar faturas facilmente',
        'reports'           => 'Obter relatórios detalhados',
        'expense'           => 'Rastreie qualquer despesa',
        'customize'         => 'Personalize seu Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Administrador',
            'description'   => 'Eles têm acesso total ao seu Akauning, incluindo clientes, faturas, relatórios, configurações e apps.',
        ],
        'manager' => [
            'name'          => 'Gerente',
            'description'   => 'Eles têm acesso total ao seu Akaunting, mas não podem gerenciar usuários e apps.',
        ],
        'customer' => [
            'name'          => 'Cliente',
            'description'   => 'Eles podem acessar o Portal do Cliente e pagar suas faturas online através dos métodos de pagamento configurados.',
        ],
        'accountant' => [
            'name'          => 'Contador',
            'description'   => 'Eles podem acessar faturas, transações, relatórios e criar registros de diário.',
        ],
        'employee' => [
            'name'          => 'Colaborador',
            'description'   => 'Eles podem criar declarações de despesas e controlar o tempo de projetos atribuídos, mas só podem ver suas próprias informações.',
        ],
    ],

];
