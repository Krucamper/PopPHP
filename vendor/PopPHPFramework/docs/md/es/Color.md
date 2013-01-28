Pop PHP Framework
=================

Documentation : Color
---------------------

Home

El componente de color es un componente Ãºtil para administrar y
utilizar los objetos de color valor. TambiÃ©n proporciona la
funcionalidad para convertir los valores de color a otros espacios de
color, por ejemplo, la conversiÃ³n de RGB a CMYK.

    use Pop\Color;

    // Create a color space value object
    $color = new Color\Color(new Color\Space\Rgb(112, 124, 228));

    echo '<strong>RGB values:</strong> ' . $color->rgb . '<br /><br />' . PHP_EOL;
    echo '<strong>HEX values:</strong> ' . $color->hex . '<br /><br />' . PHP_EOL;
    echo '<strong>CMYK conversion:</strong> ' . $color->cmyk . '<br /><br />' . PHP_EOL;
    echo '<strong>HSB conversion:</strong> ' . $color->hsb . '<br /><br />' . PHP_EOL;
    echo '<strong>Lab conversion:</strong> ' . $color->lab . '<br /><br />' . PHP_EOL;

    // Directly convert a single color object
    $rgb = new Color\Space\Rgb(112, 124, 228);
    $cmyk = Color\Convert::toCmyk($rgb);
    echo 'RGB: ' . $rgb . ' => CMYK: ' . $cmyk;

\(c) 2009-2013 [Moc 10 Media, LLC.](http://www.moc10media.com) All
Rights Reserved.
