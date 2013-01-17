Pop PHP Framework
=================

Documentation : Dom
-------------------

ドム·コンポーネントは、DOMドキュメントとその子を作成および操作する簡単な方法を提供します。

<pre>
use Pop\Dom\Child,
    Pop\Dom\Dom;

$title = new Child('title', 'This is the title');

$meta = new Child('meta');
$meta->setAttributes(array('http-equiv' => 'Content-Type', 'content' => 'text/html; charset=utf-8'));

$head = new Child('head');
$head->addChildren(array($title, $meta));

$h1 = new Child('h1', 'This is a header');
$p = new Child('p', 'This is a paragraph.');

$div = new Child('div');
$div->setAttributes('id', 'contentDiv');
$div->addChildren(array($h1, $p));

$body = new Child('body');
$body->addChild($div);

$html = new Child('html');
$html->setAttributes(array('xmlns' => 'http://www.w3.org/1999/xhtml', 'xml:lang' => 'en'));
$html->addChildren(array($head, $body));

$doc = new Dom(Dom::XHTML11, 'utf-8', $html);
$doc->render();
</pre>

また、値の構造化された配列を介して子供を追加することができます。

<pre>
use Pop\Dom\Dom;

$children = array(
    array(
        'nodeName'      => 'h1',
        'nodeValue'     => 'This is a header',
        'attributes'    => array('class' => 'headerClass', 'style' => 'font-size: 3.0em;'),
        'childrenFirst' => false,
        'childNodes'    => null
    ),
    array(
        'nodeName'      => 'div',
        'nodeValue'     => 'This is a div element',
        'attributes'    => array('id' => 'contentDiv'),
        'childrenFirst' => false,
        'childNodes'    => array(
            array(
                 'nodeName'      => 'p',
                 'nodeValue'     => 'This is a paragraph1',
                 'attributes'    => array('style' => 'font-size: 0.9em;'),
                 'childrenFirst' => false,
                 'childNodes'    => array(
                     array(
                         'nodeName'   => 'strong',
                         'nodeValue'  => 'This is bold!',
                         'attributes' => array('style' => 'font-size: 1.2em;')
                     )
                 )
            ),
            array(
                'nodeName'   => 'p',
                'nodeValue'  => 'This is another paragraph!',
                'attributes' => array('style' => 'font-size: 0.9em;')
            )
        ),
    )
);

$doc = new Dom(Dom::XHTML11);
$doc->addChildren($children);
$doc->render();
</pre>

(c) 2009-2013 [Moc 10 Media, LLC.](http://www.moc10media.com) All Rights Reserved.
