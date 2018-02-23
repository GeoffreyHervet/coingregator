#!/usr/bin/env php
<?php

require_once __DIR__ . '/vendor/autoload.php';

if (!getenv('API_URL')) {
//    die('env(API_URL) is missing' . PHP_EOL);
}

use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\Message;
use function \Ratchet\Client\connect;

$url = 'wss://stream.binance.com:9443/ws/ethbtc@kline_1m';

connect($url)
    ->then(function (WebSocket $conn) {
        $conn->on('message', function (Message $msg) use ($conn) {
            echo json_encode(json_decode($msg), JSON_PRETTY_PRINT),PHP_EOL;
        });
    }, function (\Exception $e) {
        echo "Could not connect: {$e->getMessage()}\n";
    })
;


// EventTime <
/*
 * {
  "e": "kline",     // Event type
  "E": 123456789,   // Event time
  "s": "BNBBTC",    // Symbol
  "k": {
    "t": 123400000, // Kline start time
    "T": 123460000, // Kline close time
    "s": "BNBBTC",  // Symbol
    "i": "1m",      // Interval
    "f": 100,       // First trade ID
    "L": 200,       // Last trade ID
    "o": "0.0010",  // Open price
    "c": "0.0020",  // Close price
    "h": "0.0025",  // High price
    "l": "0.0015",  // Low price
    "v": "1000",    // Base asset volume
    "n": 100,       // Number of trades
    "x": false,     // Is this kline closed?
    "q": "1.0000",  // Quote asset volume
    "V": "500",     // Taker buy base asset volume
    "Q": "0.500",   // Taker buy quote asset volume
    "B": "123456"   // Ignore
  }
}
 */
