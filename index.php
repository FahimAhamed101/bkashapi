<?php



const APP_SYSTEM_PATH = __DIR__;

require APP_SYSTEM_PATH.'../../vendor/autoload.php';

require __DIR__ . '/headless.php';

//require __DIR__ . '/controllers/default/client/app.php';

//_auth();

$callbackURL = 'http://localhost/bkash/system/index.php';

$app_key ='0vWQuCRGiUX7EPVjQDr0EUAYtc';
$app_secret ='jcUNPBgbcqEDedNKdvE4G1cAK7D3hCjmJccNPZZBq96QIxxwAMEx';
$username ='01770618567';
$password ='D7DaC<*E*eG';
$base_url = 'https://tokenized.sandbox.bka.sh';

// Start Grant Token
$client = new \GuzzleHttp\Client();
$response = $client->request('POST', $base_url.'/v1.2.0-beta/tokenized/checkout/token/grant', 
[
  
  'headers' => [
    'accept' => 'application/json',
    'content-type' => 'application/json',
    'password' => $password,
    'username' => $username,
  ],
  'body' => json_encode(array('app_key'=> $app_key, 'app_secret'=> $app_secret))
]);
$response = json_decode($response->getBody());
$id_token = $response->id_token;
// End Grant Token





if (isset($_POST['grandtotalbutton'])) {
$amount = $_POST['grandtotalbutton'];

$InvoiceNumber = 'shop'.rand();


// Strat Create Payment
$auth = $id_token;
$requestbody = array(
'mode' => '0011',
'amount' => $amount,
'currency' => 'BDT',
'intent' => 'sale',
'payerReference' => $InvoiceNumber,
'merchantInvoiceNumber' => $InvoiceNumber,
'callbackURL' => $callbackURL
);
 $url = curl_init($base_url.'/v1.2.0-beta/tokenized/checkout/create');
$requestbodyJson = json_encode($requestbody);
$header = array(
'Content-Type:application/json',
'Authorization:'.$auth,
'X-APP-Key:'.$app_key
);
curl_setopt($url, CURLOPT_HTTPHEADER, $header);
curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
curl_setopt($url, CURLOPT_POSTFIELDS, $requestbodyJson);
curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
$resultdata = curl_exec($url);
curl_close($url);
$obj = json_decode($resultdata);
header("Location: " . $obj->{'bkashURL'});
// End Create Payment
}






// execute payment
if (isset($_GET['paymentID'],$_GET['status']) && $_GET['status'] == 'success') {



$paymentID = $_GET['paymentID'];  
$auth = $id_token;
$post_token = array( 'paymentID' => $paymentID );
$url = curl_init($base_url.'/v1.2.0-beta/tokenized/checkout/execute');       
$posttoken = json_encode($post_token);
            $header = array(
                'Content-Type:application/json',
                'Authorization:' . $auth,
                'X-APP-Key:'.$app_key
            );
            curl_setopt($url, CURLOPT_HTTPHEADER, $header);
            curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($url, CURLOPT_POSTFIELDS, $posttoken);
            curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            $resultdata = curl_exec($url);
            curl_close($url);
          $obj = json_decode($resultdata);

$customerMsisdn = $obj->customerMsisdn;
$paymentID = $obj->paymentID;
$trxID = $obj->trxID;
$merchantInvoiceNumber = $obj->merchantInvoiceNumber;
$time = $obj->paymentExecuteTime;
$transactionStatus = $obj->transactionStatus;
$amount = $obj->amount;

print_r($obj);




}

$d = new Transaction();


$d->type = 'Income';
$d->payerid = $merchantInvoiceNumber;
$d->amount = $amount;
$d->account = $merchantInvoiceNumber;
$d->account_id = $customerMsisdn;


$d->ref = $paymentID;
$d->date = $time;
$d->dr = '0.00';
$d->cr = $amount;

$d->iid = $trxID;



$d->payer = $customerMsisdn;
$d->payee = '';

$d->status = 'Cleared';
$d->tax = '0.00';




$d->updated_at = date('Y-m-d H:i:s');

$d->save();


// execute payment

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Shop</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Bootstrap icons-->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Navigation-->
    
        <!-- Header-->
        <header class="bg-dark py-5">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white">
                    <h1 class="display-4 fw-bolder">Shop in style</h1>
                </div>
            </div>
        </header>
        <!-- Section-->
        <section class="py-5">
            <div class="container px-4 px-lg-5 mt-5">
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">

                 <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Sale badge-->
                            <div class="badge bg-dark text-white position-absolute" style="top: 0.5rem; right: 0.5rem">Sale</div>
                            <!-- Product image-->
                            <img class="card-img-top" src="https://icpih.com/media-intestinal-health-ihsig/PAYMENT-SUCCESS.png" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                           
                                    <h5 class="fw-bolder">Payent sucessfull</h5>
                              
                               
                                    <!-- Product price-->
                                   
                                  
                                </div>
                            </div>
                            <!-- Product actions-->
                            <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="./?a=15">Buy Now</a></div>
                            </div>
                        </div>
                    </div>



   


                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Demo Shop 2023</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>