<?php // callback.php

require "vendor/autoload.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = 'F4E7bUd5OwcKL8BNb4WMbeDZqpjbI9uFpmNFj42ps5RTF2bJlmUSDfiHmI6/Mod6h9pcTNp0lIW0sCyAf8s/8M+ezfFuCAKsQZ8VGjQlMjnaExnPy7aV2rb4vsvpGZz3gTUvjvkUZJUXm0+n2qJKsQdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {

		try {		
		// Reply only when message sent is in 'text' format
			if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
					// $text = $event['source']['userId'];
					// $profile = $event['profile']['display_name'];
					// Get replyToken
					$replyToken = $event['replyToken'];

					if($event['message']['text'] == 'สวัสดี'){
						$messages_response = 'สวัสดีต้องการให้ฉันช่วยอะไร ';
					}else if($event['message']['text'] == 'หิวข้าว'){
						$messages_response = 'กินสิ ';
					}else if($event['message']['text'] == 'บุ๋ม' || $event['message']['text'] == 'บุ๋มบิ๋ม'){
						$messages_response = 'ฉันรัก '.$event['message']['text'];
					}else{
						if($event['message']['text'] == '1'){
							$messages_response = 'เลื่อกรายการสินค้า a. ครีมบำรุงหน้า 1 b. ครีมบำรุงหน้า 2  c. ครีมบำรุงหน้า 3';
						if($event['message']['text'] == '2'){
							$messages_response = 'โทร 191';
						}else{
							$messages_response = 'ต้องการให้ช่วยอะไรค่ะ 1.ต้องการสอบถามข้อมูลสินค้า 2.ต้องการทราบข้อมูลติดต่อภายใน';
						}
					}

					// Build message to reply back
					$messages = [
						'type' => 'text',
						'text' => $messages_response
					];

			}
			// Reply only when message sent is in 'sticker' format
			else if($event['type'] == 'message' && $event['message']['type'] == 'sticker'){
					if($event['message']['text'] ){
						$stickerId = $event['message']['stickerId'];
					}else{
						$stickerId = 1;
					}

					$messages = [
						'type' => 'sticker',
						'text' => $stickerId
					];
			}else{
					$messages = [
						'type' => 'text',
						'text' => json_encode($event)
					];
			}
		} catch (Exception $e) {
			$messages = [
				'type' => 'text',
				'text' => $e->getMessage()
			];
		}

		// Make a POST Request to Messaging API to reply to sender
		$url = 'https://api.line.me/v2/bot/message/reply';
		$data = [
			'replyToken' => $replyToken,
			'messages' => [$messages],
		];

		$post = json_encode($data);
		$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);

		echo $result . "\r\n";
	
	}

}
echo "OK";
