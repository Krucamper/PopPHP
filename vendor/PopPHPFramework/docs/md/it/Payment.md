Pop PHP Framework
=================

Documentation : Payment
-----------------------

Il componente di pagamento fornisce funzionalità standard per elaborare le richieste di pagamento con carta di credito tramite un gateway 3rd party. I gateway attuali built-in e supportati sono:

* Authorize.net
* PayLeap
* PayPal
* TrustCommerce
* UsaEpay

Tuttavia, se il supporto per un gateway diverso è necessario, allora sarebbe facile scrivere solo un adattatore per esso.

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
