<?php
define("CHANNEL_ACCESS_TOKEN", getenv('CHANNEL_ACCESS_TOKEN'));
define("CHANNEL_SECRET", getenv('CHANNEL_SECRET'));


use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Event\MessageEvent\TextMessage;

require_once __DIR__ . '/vendor/autoload.php';

$bot = new LINEBot(new CurlHTTPClient(CHANNEL_ACCESS_TOKEN), [
        'channelSecret' => CHANNEL_SECRET,
]);

$signature = $_SERVER["HTTP_" . HTTPHeader::LINE_SIGNATURE];
$body = file_get_contents("php://input");

try {
    $events = $bot->parseEventRequest($body, $signature);

    foreach ($events as $event) {
        if ($event instanceof TextMessage) {
            $bot->replyText($event->getReplyToken(), 'メッセージが来たよ！');
        }
    }
} catch (LINEBot\Exception\InvalidEventRequestException $e) {
} catch (LINEBot\Exception\InvalidSignatureException $e) {
} catch (ReflectionException $e) {
}
