<?php function api_request($url, $method, $data, $api_key) {
    $options = [
        'http' => [
            'method'  => $method,
            'header'  => "Content-Type: application/json\r\n" .
                         "x-api-key: " . $api_key . "\r\n",
            'content' => json_encode($data),
            'ignore_errors' => true
        ]
    ];

    $context  = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    $result   = json_decode($response, true);

    return $result;
}

function notify_cms_mpl_confirmed($reference_number) {
    global $env;

    $url = 'https://digmstudents.westphal.drexel.edu/cp3282/idm250/api/mpls.php';
    $api_key = $env['CMS_API_KEY'];

    $data = [
      'action' => 'confirm',
      'reference_number' => $reference_number
    ];

    return api_request($url, 'POST', $data, $api_key);
}

function notify_cms_order_shipped($order_number, $shipped_at) {
    global $env;

    $url = 'https://digmstudents.westphal.drexel.edu/cp3282/idm250/api/orders.php';
    $api_key = $env['CMS_API_KEY'];

    $data = [
        'action' => 'ship',
        'order_number' => $order_number,
        'shipped_at' => $shipped_at
    ];

    return api_request($url, 'POST', $data, $api_key);
}
