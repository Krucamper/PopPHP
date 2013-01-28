Pop PHP Framework
=================

Documentation : Compress
------------------------

Home

O componente Compress fornece um mÃ©todo normalizado para compactar e
descompactar arquivos de dados e atravÃ©s dos mÃ©todos suportados:

-   bzip2
-   gzip & zlib
-   lzf

<!-- -->

    use Pop\Compress\Bzip2;

    $compressed = Bzip2::compress('Some string');
    $uncompressed = Bzip2::uncompress($compressed);

\(c) 2009-2013 [Moc 10 Media, LLC.](http://www.moc10media.com) All
Rights Reserved.
