<?php
$title = isset($_GET['id']) ? $_GET['id'] : (isset($_GET['d']) ? $_GET['d'] : null);
$feedURL = "https://www.blogger.com/feeds/966150931704515393/posts/default?alt=json&q=$title&max-results=1";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $feedURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if ($response === false) {
echo 'Curl error: ' . curl_error($ch);
} else {
$json = json_decode($response, true);
$conteudo = $json['feed']['entry'][0]['content']['$t'];
if (isset($_GET['d'])) {
$dadosBase64 = explode(',', $conteudo, 2)[1];
preg_match('/^data:[^\/]+\/([a-zA-Z]+);base64/', $conteudo, $matches);
$tipoConteudo = isset($matches[1]) ? $matches[1] : 'octet-stream';
$headerContentType = "Content-type: $tipoConteudo";
header($headerContentType);
$extensao = $tipoConteudo;
header("Content-Disposition: attachment; filename=$title.$extensao");
echo base64_decode($dadosBase64);
} else {
$tipoConteudo = mime_content_type($conteudo);
header("Content-type: $tipoConteudo");
readfile($conteudo);
}
}
curl_close($ch);
?>
