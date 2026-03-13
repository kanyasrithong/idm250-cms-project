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

    if ($response === false) {
        error_log("api_request failed: $method $url");
        return null;
    }

    $status = $http_response_header[0] ?? '';
    preg_match('/HTTP\/\d\.\d (\d+)/', $status, $matches);
    $status_code = intval($matches[1] ?? 0);

    if ($status_code < 200 || $status_code >= 300) {
        error_log("api_request error [$status]: $method $url — Response: $response");
        return null;
    }

    $result   = json_decode($response, true);

    return $result;
}

function notify_cms_mpl_confirmed($reference_number) {
    global $env;

    $url = $env['CMS_API_URL'] . '/mpls.php';
    $api_key = $env['CMS_API_KEY'];

    $data = [
      'action' => 'confirm',
      'reference_number' => $reference_number
    ];

    return api_request($url, 'POST', $data, $api_key);
}

function notify_cms_order_shipped($order_number, $shipped_at) {
    global $env;

    $url = $env['CMS_API_URL'] . '/orders.php';
    $api_key = $env['CMS_API_KEY'];

    $data = [
        'action' => 'ship',
        'order_number' => $order_number,
        'shipped_at' => $shipped_at
    ];

    return api_request($url, 'POST', $data, $api_key);
}
