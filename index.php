<?php

require_once __DIR__ . '/vendor/autoload.php';

$curl = new Curl\Curl();
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://public-api.wordpress.com/wp/v2/sites/igmoweb.com/posts');
$posts = json_decode($curl->response);
$mpdf = new \Mpdf\Mpdf();
foreach ( $posts as $post ) {
	$mpdf->WriteHTML("<h1>{$post->title->rendered}</h1>");
	$mpdf->WriteHTML($post->content->rendered);
}

$mpdf->Output();