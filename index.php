<?php
require_once __DIR__ . '/vendor/autoload.php';

function process() {
	$url = $_POST['url'] ?? '';
	if ( ! $url ) {
		die( 'Empty URL' );
	}

	$curl = new Curl\Curl();
	$curl->setHeader('Content-Type', 'application/json');
	$curl->get($url);
	$posts = json_decode($curl->response);

	if ($curl->error) {
		echo $curl->error_message;
		die();
	}


	$mpdf = new \Mpdf\Mpdf(['tempDir' => '/tmp']);
	foreach ( $posts as $post ) {
		$mpdf->WriteHTML("<h1>{$post->title->rendered}</h1>");
		$mpdf->WriteHTML($post->content->rendered);
		$mpdf->AddPage();
	}

	$mpdf->Output();
	die();
}

$action = $_POST['action'] ?? '';
if ( $action === 'getpdf' ) {
	process();
}

?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>To PDF</title>
	<style>
		body {
			line-height: 1.5;
		}
		form {
			padding: 2rem;
			width: 80%;
			margin: 0 auto;
			background: cornflowerblue;
		}
		form input[type="text"] {
			width: 100%;
		}
	</style>
</head>
<body>
<form action="" method="post">
	<label>
		REST API URL to posts (ex. site.com/wp-json/wp/v2/posts)
		<input type="text" name="url">
	</label>
	<p>By default WP will return 10 posts but you can use the per_page=20 parameter in the URL to bring more but don't be too greedy.</p>
	<input type="submit" value="Submit">
	<input type="hidden" name="action" value="getpdf">
</form>
</body>
</html>

