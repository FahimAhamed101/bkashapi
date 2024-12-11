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

<div id="horizontal" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif; font-weight: 500;  font-size: 12px; line-height:1.02 ; color: #000">
    <div class="horizontal__card" style="background-image: url({{asset('public/backEnd/id_card/img/vertical_bg.png')}}); width: 100px;display:flex; height: 100px; background-position: center center; background-size: 100% 100%;  position: relative; background-color: #fff; width: 57.15mm; height: 88.89999999999999mm;">


       {{-- <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding:8px 12px">
            <div class="logo__img logoImage hLogo" style="line-height:1.02; width: 80px; background-image: url('{{asset(generalSetting()->logo)}}');height: 30px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
            <div class="qr__img" style="line-height:1.02; width: 30px;">
                 <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%; width: 38px; position: absolute; right: 4px; top: 4px;"> 
            </div>
        </div>
        --}}

        <div class="horizontal_card_body" style="line-height:1.02; display:block;   padding-right: 3mm ; padding-left: 3mm; flex-direction: column;">
        
            <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                <div class="card_text_head " style="line-height:1.02; display: row; align-items: center; justify-content: space-between; width: 100%; margin-top:25px; margin-bottom:10px">
                    
                <div class="" style="background-color:green;">  <h3 class="text-center text-white">SCHOOL OF LAUREATES INTERNATIONAL</h3></div>
           
                <div class="" style="justify-content: center; display: flex;">
              <img style="width:70px;" class="p-2"
            src="{{  asset('uploads/settings/logo.png') }}"
            alt="">
            <div class="thumb hSize photo hImg " style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 21.166666667mm;  padding: 3px;   border: 3px solid #fff;"></div>
                </div>
                
                <div class="" style="background-color:green;">  <h4 class="text-center text-white">Md Nazrul Islam</h4><h5 class="text-center text-white">Deploma in office</h5></div>
                <div class="card_text_left hId">

                        <div id="hName">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;" class="role_name_text">
                                Student Name</h4>
                        </div>
                        <div id="hAdmissionNumber">
                            <h3 class="hStaffId" style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Admission No : 001</h3>
                        </div>
                        @if(moduleStatusCheck('University'))
                        <div id="hSession">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Session : 2022-2024 </h3>
                        </div>
                        <div id="hFaculty">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Faculty : FIST</h3>
                        </div>
                        <div id="hDepartment">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Department :Computer Science</h3>
                        </div>
                        <div id="hAcademic">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Academic : 2022</h3>
                        </div>
                        <div id="hSemester">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Semester : Summer</h3>
                        </div>
                        @else
                        <div id="hClass">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Class : One (A)</h3>
                        </div>
                        @endif
                    </div>
                    {{-- <div class="card_text_right">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:9px; font-weight:500;text-transform: uppercase; font-weight:500">jan 21. 2030</h3>
                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px; text-transform: uppercase; font-weight:500 ">Date of iSSued</h4>
                    </div> --}}
                    
                </div>

                <div class="card_text_head hStudentName" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                    <div class="card_text_left">
                        {{-- <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">InfixEdu</h3> --}}
                        <div id="hFatherName">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Father Name : Mr. Father</h4>
                        </div>
                        <div id="hMotherName">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px; font-weight:500">Mother Name : Mrs. Mother</h4>
                        </div>
                    </div>
                </div>

                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                    <div class="card_text_left">
                    <div id="hBloodGroup">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Blood Group : B+</h3>
                        </div>
                        <div id="hDob">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Date of Birth : Dec 25 , 2022</h3>
                        </div>
                     
                    </div>
                    {{-- <div class="card_text_right">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;  text-transform: uppercase;font-weight:500; text-align:center;">B+</h3>
                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Blood Group</h4>
                    </div> --}}
                </div>
                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px"> 
                    <div class="card_text_left" id="hAddress">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase">
                            {{  generalSetting()->address }}</h3>
                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">@lang('common.address')</h4>
                    </div>
                </div>
            </div>
            <div class="" style="background-color:green;">  <h4 class="p-1 text-center text-white">www.laureates.edu.bd</h4></div>
        </div>
        
        <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
            <div class="singnature_img signPhoto hSign" style="background-image:url('{{asset('public/backEnd/id_card/img/Signature.png')}}');line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px;height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
        </div>
   
   
        <div class="" style=" width: 57.15mm; height: 88.89999999999999mm; display:block;   padding-right: 3mm ; padding-left: 3mm; ">
        
            <div class="" style=" ">
                <div class=" " style="  width: 100%; margin-top:25px; margin-bottom:10px">
                    
                <div class="text-center" style="background-color:green;">  
                    <h4 class=" text-white">SCHOOL OF LAUREATES INTERNATIONAL</h4>
                    <div class="text-center">

                   
                <p style="font-size: 11px;" class="text-white ">21/1. RCRC street,Courtpara,Kushtia</p>
                <p style="font-size: 11px;" class="text-white">01518976973,01518976974,01518976975</p> </div>
            </div>
           
                <div class="" style="justify-content: center; display: flex;">
              <img style="width:70px;" class="p-2"
            src="{{  asset('uploads/settings/logo.png') }}"
            alt="">
            <div class="thumb hSize photo hImg " style=" background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}');background-size: cover; background-position: center center; background-repeat: no-repeat; line-height:1.02; width: 21.166666667mm;  padding: 3px;   border: 3px solid #fff;"></div>
                </div>
                

                <div class="card_text_left hId">

                        <div id="hName">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;" class="role_name_text">
                                Student Name</h4>
                        </div>
                        <div id="hAdmissionNumber">
                            <h3 class="hStaffId" style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Admission No : 001</h3>
                        </div>
                        @if(moduleStatusCheck('University'))
                        <div id="hSession">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Session : 2022-2024 </h3>
                        </div>
                        <div id="hFaculty">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Faculty : FIST</h3>
                        </div>
                        <div id="hDepartment">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Department :Computer Science</h3>
                        </div>
                        <div id="hAcademic">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Academic : 2022</h3>
                        </div>
                        <div id="hSemester">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500"> Semester : Summer</h3>
                        </div>
                        @else
                        <div id="hClass">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Class : One (A)</h3>
                        </div>
                        @endif
                    </div>
                    {{-- <div class="card_text_right">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:9px; font-weight:500;text-transform: uppercase; font-weight:500">jan 21. 2030</h3>
                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px; text-transform: uppercase; font-weight:500 ">Date of iSSued</h4>
                    </div> --}}
                    
                </div>

                <div class="card_text_head hStudentName" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                    <div class="card_text_left">
                        {{-- <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;">InfixEdu</h3> --}}
                        <div id="hFatherName">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Father Name : Mr. Father</h4>
                        </div>
                        <div id="hMotherName">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px; font-weight:500">Mother Name : Mrs. Mother</h4>
                        </div>
                    </div>
                </div>

                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:10px"> 
                    <div class="card_text_left">
                    <div id="hBloodGroup">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Blood Group : B+</h3>
                        </div>
                        <div id="hDob">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Date of Birth : Dec 25 , 2022</h3>
                        </div>
                     
                    </div>
                    {{-- <div class="card_text_right">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;  text-transform: uppercase;font-weight:500; text-align:center;">B+</h3>
                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Blood Group</h4>
                    </div> --}}
                </div>
                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px"> 
                    <div class="card_text_left" id="hAddress">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase">
                            {{  generalSetting()->address }}</h3>
                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">@lang('common.address')</h4>
                    </div>
                </div>
            </div>
            <div class="" style="background-color:green;">  <h4 class="p-1 text-center text-white">laureatesintkst@gmail.com</h4></div>
        </div>
        
        <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
            <div class="singnature_img signPhoto hSign" style="background-image:url('{{asset('public/backEnd/id_card/img/Signature.png')}}');line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px;height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;"></div>
        </div>
    </div>
</div>

<div id="vertical" class="d-none" style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif;  font-size: 12px; line-height:1.02 ;">
    <div class="vertical__card" style="line-height:1.02; background-image: url({{asset('public/backEnd/id_card/img/horizontal_bg.png')}}); width: 86mm; height: 54mm; margin: auto; background-size: 100% 100%; background-position: center center; position: relative;">
        <div class="horizontal_card_header" style="line-height:1.02; display: flex; align-items:center; justify-content:space-between; padding: 12px">
            <div class="logo__img logoImage vLogo" style="line-height:1.02; width: 80px; background-image: url('{{asset(generalSetting()->logo)}}');background-size: cover; height: 30px;background-position: center center; background-repeat: no-repeat;"></div>
            <div class="qr__img" style="line-height:1.02; width: 48px; position: absolute; right: 4px; top: 4px;">
                {{-- <img src="{{asset('public/backEnd/id_card/img/qr.png')}}" alt="" style="line-height:1.02; width: 100%;"> --}}
            </div>
        </div>
        <div class="vertical_card_body" style="line-height:1.02; display:flex; padding-top: 2.5mm; padding-bottom: 2.5mm; padding-right: 3mm ; padding-left: 3mm; align-items: center;">
            <div class="thumb vSize vSizeX photo vImg vRoundImg" style="background-image: url('{{asset('public/uploads/staff/demo/staff.jpg')}}'); line-height:1.02; width: 13.229166667mm; height: 13.229166667mm; flex-basis: 13.229166667mm; flex-grow: 0; flex-shrink: 0; margin-right: 20px; background-size: cover; background-position: center center;"></div>
            <div class="card_text" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; flex-direction: column;">
                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px"> 
                    <div class="card_text_left vId">
                        <div id="vName">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:11px; font-weight:600 ; text-transform: uppercase; color: #2656a6;" class="role_name_text"> Student Name</h3>
                        </div>
                        <div id="vAdmissionNumber">
                            <h4 class="vStaffId" style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px;">Admission No : 001</h4>
                        </div>
                        @if(moduleStatusCheck('University'))
                        <div id="vSession">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Session : 2022-2024 </h3>
                        </div>
                        <div id="vFaculty">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Faculty : FIRST</h3>
                        </div>
                        <div id="vDepartment">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Department :Computer Science</h3>
                        </div>
                        <div id="vAcademic">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Academic : 2022</h3>
                        </div>
                        <div id="vSemester">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;"> Semester : Summer</h3>
                        </div>
                        @else
                        <div id="vClass">
                            <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:10px;">Class : One (A)</h4>
                        </div>
                        @endif
                    </div>
                    <div class="card_text_right">
                        </br>
                        <div id="vDob">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;">DOB : jan 21. 2030</h3>
                        </div>
                        <div id="vBloodGroup">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;">Blood Group : B+</h3>
                        </div>
                    </div>
                </div>

                <div class="card_text_head vStudentName" style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px"> 
                    <div class="card_text_left">
                        {{-- <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase;font-weight:500">@lang('common.name')</h4> --}}
                    </div>
                </div>

                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-bottom:5px"> 
                    <div class="card_text_left">
                        <div id="vFatherName">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Father Name : Mr. Father</h3>
                        </div>
                        <div id="vMotherName">
                            <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500">Mother Name : Mrs. Mother</h3>
                        </div>
                    </div>
                    <div class="card_text_right">
                        {{-- <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 3px; font-size:10px; font-weight:500;  text-transform: uppercase; ">American</h3> --}}
                        {{-- <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Nationality</h4> --}}
                    </div>
                </div>
                <div class="card_text_head " style="line-height:1.02; display: flex; align-items: center; justify-content: space-between; width: 100%; margin-top:5px"> 
                    <div class="card_text_left vAddress">
                        <h3 style="line-height:1.02; margin-top: 0; margin-bottom: 5px; font-size:10px; font-weight:500; text-transform:uppercase;">  {{  generalSetting()->address }} </h3>
                        <h4 style="line-height:1.02; margin-top: 0; margin-bottom: 0; font-size:9px; text-transform: uppercase; font-weight:500">Address</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="horizontal_card_footer" style="line-height:1.02; text-align: right;">
            <div class="singnature_img signPhoto vSign" style="background-image: url('{{asset('public/backEnd/id_card/img/Signature.png')}}'); line-height:1.02; width: 50px; flex: 50px 0 0; margin-left: auto; position: absolute; right: 10px; bottom: 7px; height: 25px; background-size: cover; background-repeat: no-repeat; background-position: center center;">
            </div>
        </div>
    </div>
</div>
