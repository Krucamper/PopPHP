Pop PHP Framework
=================

Documentation : CLI
-------------------

Home

O comando interface de linha de componente (CLI) Ã© um componente muito
Ãºtil que permite executar algumas tarefas Ãºteis, tais como:

-   avaliar o ambiente atual para dependÃªncias necessÃ¡rias
-   instalar um projeto de um arquivo de instalaÃ§Ã£o do projeto
-   definir o idioma padrÃ£o de uma aplicaÃ§Ã£o
-   criar um mapa de classe
-   verificar a versÃ£o atual com a Ãºltima versÃ£o disponÃ­vel

<!-- -->

    script/pop --check                     // Check the current configuration for required dependencies
    script/pop --help                      // Display this help
    script/pop --install file.php          // Install a project based on the install file specified
    script/pop --lang fr                   // Set the default language for the project
    script/pop --map folder file.php       // Create a class map file from the source folder and save to the output file
    script/pop --show                      // Show project install instructions
    script/pop --version                   // Display version of Pop PHP Framework and latest available

Aqui estÃ¡ um exemplo de arquivo de instalaÃ§Ã£o do projeto:

    return new Pop\Config(array(
        'project' => array(
            'name'    => 'HelloWorld',
            'base'    => __DIR__ . '/../../',
            'docroot' => __DIR__ . '/../../public'
        ),
        'databases' => array(
            'helloworld' => array(
                'type'     => 'Sqlite',
                'database' => '.hthelloworld.sqlite',
                'prefix'   => 'pop_',
                'default'  => true
            )
        ),
        'forms' => array(
            'login' => array(
                'fields' => array(
                    array(
                        'type'       => 'text',
                        'name'       => 'username',
                        'label'      => 'Username:',
                        'required'   => true,
                        'attributes' => array('size', 40),
                        'validators' => 'AlphaNumeric()'
                    ),
                    array(
                        'type'       => 'password',
                        'name'       => 'password',
                        'label'      => 'Password:',
                        'required'   => true,
                        'attributes' => array('size', 40),
                        'validators' => array('NotEmpty()', 'LengthGt(6)')
                    ),
                    array(
                        'type'       => 'submit',
                        'name'       => 'submit',
                        'value'      => 'LOGIN'
                    )
                )
            )
        ),
        'controllers' => array(
            '/' => array(
                'index'   => 'index.phtml',
                'about'   => 'about.phtml',
                'contact' => 'contact.phtml',
                'error'   => 'error.phtml'
            )
        ),
        'models' => array(
            'User'
        )
    ));

\(c) 2009-2013 [Moc 10 Media, LLC.](http://www.moc10media.com) All
Rights Reserved.
