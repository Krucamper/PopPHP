Pop PHP Framework
=================

Documentation : Config
----------------------

Home

O componente de configuraÃ§Ã£o fornece um objeto de valor de dados que
Ã© utilizada por outros componentes, como o componente do projecto.
Normalmente, coisas como credenciais de banco de dados sÃ£o definidos em
um objeto de configuraÃ§Ã£o e passado para um objeto de projeto a ser
utilizado durante o ciclo de vida do projeto ou script.

    use Pop\Config;

    $cfg = array(
        'db' => array(
            'name' => 'testdb',
            'host' => 'localhost',
            'user' => array(
                'username' => 'testuser',
                'password' => '12test34',
                'role'     => 'editor'
            )
        ),
        'module' => 'TestModule'
    );

    $config = new Config($cfg);

    echo 'DB Name: ' . $config->db->name;
    echo 'User: ' . $config->db->user->username . ' has the role: ' . $config->db->user->role;
    echo 'Module Name: ' . $config->module;

\(c) 2009-2013 [Moc 10 Media, LLC.](http://www.moc10media.com) All
Rights Reserved.
