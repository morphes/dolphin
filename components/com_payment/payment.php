<?php
defined('_JEXEC') or die;
JFactory::getDocument()->setTitle("");
$projectPart = '/../..';
require_once(dirname(__FILE__) . $projectPart . '/vendor/autoload.php');
use Lime\Request;
use Spipu\Html2Pdf\Html2Pdf;

if (isset($_GET['pdf']) && isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];
    $pdf     = $_GET['pdf'];
    if (md5($orderId . 'secret_hash') == $pdf) {

        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__order'));
        $query->where($db->quoteName('id') . ' = ' . (int)$orderId);
        $db->setQuery($query);
        $order = $db->loadObject();

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($order->pdf);
        $mpdf->Output();
    } else {
        echo 'error';
    }
    die();
}

include_once 'orangedata_client.php';
require_once __DIR__ . '/QR/qrlib.php';
require_once(dirname(__FILE__) . $projectPart . '/templates/jblank/html/com_content/article/form.lib.php');
include_once(__DIR__ . '/letter.php');
$domain = 'http://dolphinevpatoria.ru/';

if (isset($_POST['xmlmsg'])) {
    $xmlMsg         = $_POST['xmlmsg'];
    $paymentMessage = (array)simplexml_load_string($xmlMsg);
    if (isset($paymentMessage['OrderStatus'])) {
        $status = $paymentMessage['OrderStatus'];

        if ($status == 'CANCELED') {
            echo 'Заказ отменен';
        }

        if ($status == 'APPROVED' || $status == 'ERROR') {
            $api_url = 12003;

            $sign_pkey           = getcwd() . '/certificates/orange_live/signpkey.pem';
            $ssl_client_key      = getcwd() . '/certificates/orange_live/9110001780.key';
            $ssl_client_crt      = getcwd() . '/certificates/orange_live/9110001780.crt';
            $ssl_ca_cert         = getcwd() . '/certificates/orange_live/cacert.pem';
            $ssl_client_crt_pass = '';
            $inn                 = '9110001780';
            $phone               = '79889917443';

            if (isset($paymentMessage['OrderDescription'])) {
                $orderNumber = (int)explode('#', $paymentMessage['OrderDescription'])[1];
                if ($orderNumber) {
                    $db    = JFactory::getDbo();
                    $query = $db->getQuery(true);
                    $query->select('*');
                    $query->from($db->quoteName('#__order'));
                    $query->where($db->quoteName('id') . ' = ' . $orderNumber);
                    $db->setQuery($query);
                    $order = $db->loadObject();

                    $byer = new orangedata\orangedata_client($inn, $api_url, $sign_pkey, $ssl_client_key, $ssl_client_crt, $ssl_ca_cert, $ssl_client_crt_pass);

                    $byer->create_order($paymentMessage['OrderID'] . '-' . time(), 1, $phone, 1, null, $inn);

                    $prices = file_get_contents(JPATH_THEMES . '/jblank/html/com_content/article/prices.json');
                    $prices = @json_decode($prices);

                    $pricesByKeys = [];
                    foreach ($prices as $key => $value) {
                        $pricesByKeys[$value->name] = $value->price;
                    }

                    foreach ($order as $key => $value) {
                        if ($value) {
                            if (isset($pricesByKeys[$key])) {
                                $byer->add_position_to_order($value, $pricesByKeys[$key], 2, 'Билет');
                            }
                        }
                    }
                    $byer->add_payment_to_order(2, $order->total_price);

                    $byer->add_agent_to_order(127, [], 'Operation', [], [], 'Name', 'ulitsa Adress, dom 7', 3123011520, []);

                    $result = $byer->send_order();

                    $request = new Request();

                    $request->setApiUser('sazon@nxt.ru')
                        ->setApiPassword('everest1024')
                        ->setApiUserId('614628')
                        ->setInstallationId('79')
                        ->setCashdeskId('210')
                        ->setProcessingId('161');

                    $items = [];
                    foreach ($prices as $key => $value) {
                        if (property_exists($order, $value->name) && $order->{$value->name}) {
                            $items[] = [
                                'id'     => 0,
                                'name'   => $value->description,
                                'price'  => $value->price * 100,
                                'limeid' => $value->api_id,
                                'amount' => $order->{$value->name}
                            ];
                        }
                    }

                    $qrs    = $request->order($items);
                    $params = [];
                    foreach ($qrs as $key => $qr) {
                        $qrFilename = dirname(__FILE__) . $projectPart . "/media/" . $orderNumber . "-" . $key . ".png";

                        $qrFilePath = str_replace(dirname(__FILE__) . $projectPart . '/', '', $qrFilename);
                        QRcode::png($qr, $qrFilename, QR_ECLEVEL_L, 10);
                        $params['qrs'][] = [
                            'qr'     => $qrFilePath,
                            'domain' => $domain
                        ];
                    }
                    $params['domain']      = $domain;
                    $params['date']        = $order->day . ' ' . $order->month;
                    $params['price']       = $order->total_price;
                    $params['time']        = $order->time;
                    $params['order_id']    = $order->id;
                    $params['child_count'] = $order->show_child;
                    $params['adult_count'] = $order->show_adult;
                    $filename              = md5($order->id);
                    $params['pdf']         = $filename;

                    $letterTemplate = letterTemplate();
                    $content        = template($letterTemplate, $params);


                    $query      = $db->getQuery(true);
                    $fields     = [
                        $db->quoteName('pdf') . ' = ' . $db->quote($content)
                    ];
                    $conditions = [
                        $db->quoteName('id') . ' = ' . $order->id
                    ];
                    $query->update($db->quoteName('#__order'))->set($fields)->where($conditions);
                    $db->setQuery($query);
                    $result = $db->execute();

                    mailAttachments($order->email, "Ваш билет", $content);
                    mailAttachments('evpatoriiskydelfinary@yandex.ru', "Заказанный билет", $content);

                    header('Location: ' . $domain . 'templates/jblank/html/com_content/article/success.php', true, 301);
                    exit;
                }
            }
        }
        if ($status == 'DECLINED') {
            header('Location: /templates/jblank/html/com_content/article/failed.php', true, 301);
        }
    }
}

/**
 * [@attributes] => Array
 * (
 * [date] => 22/08/2019 19:14:55
 * )
 *
 * [Language] => eng
 * [ResponseCode] => 001
 * [Version] => 1.0
 * [Currency] => 643
 * [OrderIDEncrypted] => @encrypted@1@FA317EFDE6A47313
 * [TransactionType] => Purchase
 * [OrderDescription] => TEST PAYMENT $0.01
 * [ApprovalCodeScr] => 7B18JF
 * [PurchaseAmountScr] => 0,01
 * [TotalAmountScr] => 0,01
 * [AcqFeeScr] => 0,00
 * [CurrencyScr] => Russian rouble
 * [TranDateTime] => 22/08/2019 19:14:55
 * [ResponseDescription] => Approved, no balances available
 * [OrderStatusScr] => APPROVED
 * [RezultOperation] => Transaction Result
 * [Brand] => MIR
 * [PurchaseAmount] => 1
 * [ThreeDSVars] => SimpleXMLElement Object
 * (
 * [PrefixParam] => SimpleXMLElement Object
 * (
 * [TypeCard] => MIR
 * )
 *
 * )
 *
 * [SessionId] => A94FA3F36C5B06500C67F6DAE9E3B097
 * [OrderStatus] => APPROVED
 * [OrderID] => 258
 * [SessionID] => A94FA3F36C5B06500C67F6DAE9E3B097
 * [ApprovalCode] => 7B18JF A
 * [TotalAmount] => 1
 * [FeeScr] => 0,00
 * [AcqFee] => 0
 * [PAN] => 220013XXXXXX1124
 */