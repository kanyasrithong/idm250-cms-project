<?php
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit;
    }
}

function check_api_key($env) {
	$valid_key = $env['X-API-KEY'];
	$provided_key = null;
	$headers = getallheaders();
	
	foreach($headers as $name => $value) {
		if (strtolower($name) === 'x-api-key') {
			$provided_key = $value;
			break;
		};
	};
	
	if ($provided_key !== $valid_key) {
		http_response_code(401);
		echo json_encode(['error' => 'Missing credentials']);
    exit();
	}
};