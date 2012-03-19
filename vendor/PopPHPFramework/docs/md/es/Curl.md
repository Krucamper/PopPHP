Pop PHP Framework
=================

Documentation : Curl
--------------------

El componente Curl simplemente proporciona una capa API orientada a objetos a la extensión CURL de PHP.

<pre>
use Pop\Curl\Curl;

$options = array(
    CURLOPT_URL            => 'http://pop.localhost/examples/curl/curl-process.php',
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => array('name' => 'Bubba', 'email' => 'bubba@hotmail.com'),
    CURLOPT_HEADER         => false,
    CURLOPT_RETURNTRANSFER => true
);

header('Content-Type: text/html; charset=utf-8');
$curl = new Curl($options);
echo $curl->execute();
</pre>

(c) 2009-2012 [Moc 10 Media, LLC.](http://www.moc10media.com) All Rights Reserved.
