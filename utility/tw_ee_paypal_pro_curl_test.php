<?php
//Set SANDBOX credentials here, Live credentials will NOT work.
$user 		= '';
$pw 		= '';
$signature 	= '';

// Create a random Invoive number to allow the payment to work.
$random_inv_num = md5(uniqid(rand(), true));

// Example post values for PayPal pro request.
$request = "USER=$user&PWD=$pw&VERSION=64.0&SIGNATURE=$signature&METHOD=DoDirectPayment&BUTTONSOURCE=AngellEYE_PHP_Class_DDP&PAYMENTACTION=Sale&IPADDRESS=80.190.214.99&RETURNFMFDETAILS=1&CREDITCARDTYPE=Visa&ACCT=5424180818927383&EXPDATE=42022&CVV2=123&STARTDATE=&ISSUENUMBER=&SALUTATION=&FIRSTNAME=PayPalTestFname&MIDDLENAME=&LASTNAME=PayPalTestLname&SUFFIX=&STREET=1&STREET2=&CITY=2&STATE=3&COUNTRYCODE=US&ZIP=4&PHONENUM=&AMT=10.00&CURRENCYCODE=GBP&ITEMAMT=&SHIPPINGAMT=&HANDLINGAMT=&TAXAMT=&DESC=Test+PayPal+Pro&CUSTOM=&INVNUM=$random_inv_num&NOTIFYURL=&BUTTONSOURCE=EventEspresso_SP&L_NAME0=Test+PayPal+Pro&L_DESC0=Test+PayPal+Pro&L_AMT0=10.00&L_NUMBER0=&L_QTY0=1&L_TAXAMT0=&L_EBAYITEMNUMBER0=&L_EBAYITEMAUCTIONTXNID0=&L_EBAYITEMORDERID0=";

// Sandbox URL
$sandbox_api = 'https://api-3t.sandbox.paypal.com/nvp';

$curl = curl_init();

curl_setopt($curl, CURLOPT_VERBOSE, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0);
curl_setopt($curl, CURLOPT_TIMEOUT, 45);
curl_setopt($curl, CURLOPT_URL, $sandbox_api);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
//curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
$verbose = fopen('php://temp', 'w+');
curl_setopt($curl, CURLOPT_STDERR, $verbose);

// execute the curl POST
$response = curl_exec($curl);

rewind($verbose);
$verboseLog = stream_get_contents($verbose);

echo "Verbose information:\n<pre>", htmlspecialchars($verboseLog), "</pre>\n";
echo "<hr><br>\n";
echo 'cURL Response:';
var_dump($response);
echo "<hr><br>\n";
var_dump(curl_getinfo($curl));
echo "<hr><br>\n";
echo 'cURL Error: ' . curl_errno($curl) . ' ' . curl_error($curl) . '<br><br>';
echo "<hr><br>\n";
curl_close($curl);
print "result - $response";
