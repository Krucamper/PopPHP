Pop PHP Framework
=================

Documentation : Filter
----------------------

Home

Το Φίλτρο συστατικό παρέχει κάποιες χρήσιμες λειτουργίες φιλτραρίσματος
για σειρά χειραγώγηση, την κρυπτογράφηση και την αναζήτηση πίνακα.

    use Pop\Filter\String;

    echo 'Random String: ' . String::random(6, String::ALPHANUM, String::UPPER) . '<br /><br />' . PHP_EOL;

    $key = md5('Pop PHP Framework');

    $encrypted = String::encrypt('Hello World!', $key);
    echo 'Encrypted: ' . $encrypted . '<br /><br />' . PHP_EOL;

    $decrypted = String::decrypt($encrypted, $key);
    echo 'Decrypted: ' . $decrypted . '<br />' . PHP_EOL;

\(c) 2009-2013 [Moc 10 Media, LLC.](http://www.moc10media.com) All
Rights Reserved.
