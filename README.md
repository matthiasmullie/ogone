# Ogone

## Example usage

    /*
     * Build your Ogone object; you'll need to do this to build the submission form
     * as well as to process the result from Ogone.
     */

    // order details
    $orderID = 1;
    $amount = 50;
    $pspId = 'EUR';

    // your Ogone config setup (see Ogone class phpdoc for more info)
    $pspId = '<your-PSP-id>';
    $shaIn = '<your-SHA-In>';
    $shaOut = '<your-SHA-Out>';
    $digest = 'SHA512'; // value can be either SHA1', 'SHA256' or 'SHA512
    $environment = 'test'; // value can be either 'test' or 'prod'
    $method = 'post'; // value can be either 'get' or 'post'
    $verification = 'each'; // value can be either 'main' or 'each'

    // create Ogone-object
    use MatthiasMullie\Ogone;
    $ogone = new Ogone\Ogone($orderID, $amount, $pspId, $currency, $shaIn, $shaOut, $digest);

    // define test or production (for real payments) Ogone environment
    $ogone->setEnvironment($environment);
    $ogone->setMethod($method);
    $ogone->setVerification($verification);


    /*
     * Initialize a payment: build the form data to submit to Ogone.
     */

    // add parameters (see parameter list: https://secure.ogone.com/ncol/Ogone_e-Com-ADV_EN.pdf)
    // here are some sample parameters
    $ogone->setParameter('LANGUAGE', 'nl');
    $ogone->setParameter('CN', 'Matthias Mullie');
    $ogone->setParameter('EMAIL', 'ogone-example@mullie.eu');
    $ogone->setParameter('OWNERADDRESS', '');
    $ogone->setParameter('OWNERZIP', '');
    $ogone->setParameter('OWNERTOWN', '');
    $ogone->setParameter('OWNERCTY', '');
    $ogone->setParameter('ACCEPTURL', '');
    $ogone->setParameter('DECLINEURL', '');
    $ogone->setParameter('EXCEPTIONURL', '');
    $ogone->setParameter('CANCELURL', '');
    $ogone->setParameter('TP', '');
    // ...

    // build form to submit
    $form = '<form method="POST" action="'.$ogone->getEnvironment().'">';
    $parameters = $ogone->getFormParameters();
    foreach($parameters as $key => $value) {
        $form .= '<input type="hidden" name="'.$key.'" value="'.$value.'" />';
    }
    $form .= '<input type="submit" value="Pay with Ogone">';
    $form .= '</form>';


    /*
     * Verify a payment.
     */

    // verify the SHA signature
    if($ogone->isCorrectSHA()) {
        // fetch all details retrieved from Ogone...
        $ogoneResponse = $ogone->getDetail(); // no parameter

        // ... or retrieve a specific value
        $status = $ogone->getDetail('status'); // 'status' parameter

        // your business logic here
    }

## License
Ogone is [MIT](http://opensource.org/licenses/MIT) licensed.
