<?php

return [

    'auth'                  => 'Autenticação',
    'profile'               => 'Perfil',
    'logout'                => 'Sair',
    'login'                 => 'Entrar',
    'forgot'                => 'Esqueci',
    'login_to'              => 'Entre para iniciar sua sessão',
    'remember_me'           => 'Lembrar-me',
    'forgot_password'       => 'Esqueci minha senha',
    'reset_password'        => 'Redefinir senha',
    'change_password'       => 'Alterar senha',
    'enter_email'           => 'Digite seu endereço de e-mail',
    'current_email'         => 'E-mail atual',
    'reset'                 => 'Redefinir',
    'never'                 => 'Nunca',
    'landing_page'          => 'Página inicial',
    'personal_information'  => 'Informações pessoais',
    'register_user'         => 'Registrar usuário',
    'register'              => 'Registre-se',

    'form_description' => [
        'personal'          => 'O link do convite será enviado para o novo usuário, então certifique-se de que o endereço de e-mail esteja correto. Eles poderão digitar sua senha.',
        'assign'            => 'O usuário terá acesso às empresas selecionadas. Você pode restringir as permissões na página de <a href=":url" class="border-b border-black">funções</a>.',
        'preferences'       => 'Selecione o idioma padrão do usuário. Você também pode definir a página inicial após o login do usuário.',
    ],

    'password' => [
        'pass'              => 'Senha',
        'pass_confirm'      => 'Confirmação de senha',
        'current'           => 'Senha atual',
        'current_confirm'   => 'Confirmação de senha atual',
        'new'               => 'Nova senha',
        'new_confirm'       => 'Confirmação de nova senha',
    ],

    'error' => [
        'self_delete'       => 'Erro: Você não pode excluir a si mesmo!',
        'self_disable'      => 'Erro: Você não pode desativar a si mesmo!',
        'unassigned'        => 'Erro: Não é possível desatribuir a empresa! A empresa :company deve ter pelo menos um usuário atribuído.',
        'no_company'        => 'Erro: Nenhuma empresa atribuída à sua conta. Por favor, entre em contato com o administrador do sistema.',
    ],

    'login_redirect'        => 'Verificação concluída! Você está sendo redirecionado...',
    'failed'                => 'Essas credenciais não correspondem aos nossos registros.',
    'throttle'              => 'Muitas tentativas de login. Por favor, tente novamente em :seconds segundos.',
    'disabled'              => 'Esta conta está desativada. Por favor, entre em contato com o administrador do sistema.',

    'notification' => [
        'message_1'         => 'Você está recebendo este e-mail porque recebemos um pedido de redefinição de senha para sua conta.',
        'message_2'         => 'Se você não pediu uma redefinição de senha, nenhuma ação adicional é necessária.',
        'button'            => 'Redefinir senha',
    ],

    'invitation' => [
        'message_1'         => 'Você está recebendo este e-mail porque foi convidado a juntar-se ao Akaunting.',
        'message_2'         => 'Se não deseja participar, nenhuma outra ação é necessária.',
        'button'            => 'Começar',
    ],

    'information' => [
        'invoice'           => 'Crie faturas facilmente',
        'reports'           => 'Obtenha relatórios detalhados',
        'expense'           => 'Acompanhe qualquer despesa',
        'customize'         => 'Personalize seu Akaunting',
    ],

    'roles' => [
        'admin' => [
            'name'          => 'Administrador',
            'description'   => 'Eles têm acesso total ao seu Akaunting, incluindo clientes, faturas, relatórios, configurações e apps.',
        ],
        'manager' => [
            'name'          => 'Gerente',
            'description'   => 'Eles têm acesso total ao seu Akaunting, mas não podem gerenciar usuários e apps.',
        ],
        'customer' => [
            'name'          => 'Cliente',
            'description'   => 'Eles podem acessar o Portal do Cliente e pagar suas faturas online através das formas de pagamento que você configurou.',
        ],
        'accountant' => [
            'name'          => 'Contador',
            'description'   => 'Eles podem acessar faturas, transações, relatórios e criar lançamentos contábeis.',
        ],
        'employee' => [
            'name'          => 'Colaborador',
            'description'   => 'Eles podem criar solicitações de despesas e controlar o tempo em projetos atribuídos, mas só podem ver suas próprias informações.',
        ],
    ],

];
