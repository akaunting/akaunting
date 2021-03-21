<?php

return [

    'next'                  => 'Következő',
    'refresh'               => 'Frissítés',

    'steps' => [
        'requirements'      => 'Kérd meg a tárhely szolgáltatód, hogy javítsa a hibát!',
        'language'          => '1/3. lépés: Nyelv kiválasztása',
        'database'          => '2/3. lépés: Adatbázis beállítás',
        'settings'          => '3/3. lépés: Céges és admin részletek',
    ],

    'language' => [
        'select'            => 'Válasszon nyelvet',
    ],

    'requirements' => [
        'enabled'           => ':feature engedélyezése szükséges!',
        'disabled'          => ':feature letiltása szükséges!',
        'extension'         => 'Telepítened és be kell töltened ezeket a kiterjesztéseket: :extension !',
        'directory'         => ': directory könyvtárnak írhatónak kell lennie!',
        'executable'        => 'A PHP CLI futtatható fájl nincs definiálva / működik, vagy annak verziója nem: php_version vagy magasabb! Kérje meg a tárhelyvállalatot, hogy állítsa be a PHP_BINARY vagy a PHP_PATH környezeti változót helyesen.',
    ],

    'database' => [
        'hostname'          => 'Kiszolgáló név',
        'username'          => 'Felhasználónév',
        'password'          => 'Jelszó',
        'name'              => 'Adatbázis',
    ],

    'settings' => [
        'company_name'      => 'Cégnév',
        'company_email'     => 'A cég email címe',
        'admin_email'       => 'Admin email címe',
        'admin_password'    => 'Admin jelszó',
    ],

    'error' => [
        'php_version'       => '
Hiba: Kérje meg a tárhelyszolgáltatót, hogy a PHP: php_version vagy magasabb verziót használja mind a HTTP, mind a CLI számára.',
        'connection'        => 'Hiba: Nem sikerült csatlakozni az adatbázishoz! Győződjön meg a beállítások helyességéről!',
    ],

];
