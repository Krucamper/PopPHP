Pop PHP Framework
=================

Documentation : Payment
-----------------------

Le composant offre une fonctionnalité de paiement standardisé pour traiter les demandes de cartes de crédit de paiement via une passerelle 3ème partie. Les passerelles actuelles intégrés et pris en charge sont les suivants:

* Authorize.net
* PayLeap
* PayPal
* TrustCommerce
* UsaEpay

Toutefois, si le support pour une passerelle différente est nécessaire, alors il serait simple d'y écrire un adaptateur pour elle.

<pre>
use Pop\Payment\Payment,
    Pop\Payment\Adapter\Authorize;

$payment = new Payment(new Authorize('API_LOGIN_ID', 'TRANS_KEY', Payment::TEST));

$payment->cardNum = 'XXXXXXXXXXXXXXXX';
$payment->amount = '27.00';
$payment->expDate = '12/13';

$payment->send();

if ($payment->isApproved()) {
    echo "You're approved!" . PHP_EOL;
    echo $payment->getMessage();
} else if ($payment->isDeclined()) {
    echo "You were declined!" . PHP_EOL;
    echo $payment->getMessage();
} else if ($payment->isError()) {
    echo "There was an error!" . PHP_EOL;
    echo $payment->getMessage();
}
</pre>

(c) 2009-2012 [Moc 10 Media, LLC.](http://www.moc10media.com) All Rights Reserved.
