<?php
require __DIR__ . '/vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
use Dotenv\Dotenv;

Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();

class Twitter {
    private string $apiKey;
    private string $apiSecret;
    private string $accessToken;
    private string $accessTokenSecret;

    public function __construct() {
        $this->apiKey = $_ENV['API_KEY'];
        $this->apiSecret = $_ENV['API_SECRET_KEY'];
        $this->accessToken = $_ENV['ACCESS_TOKEN'];
        $this->accessTokenSecret = $_ENV['ACCESS_TOKEN_SECRET'];
    }
    public function get_api(): TwitterOAuth {
        $connection = new TwitterOAuth($this->apiKey, $this->apiSecret, $this->accessToken, $this->accessTokenSecret);
        $connection->setApiVersion('2');
        return $connection;
    }
    public function post_tweet(string $payload): string {
        $api = $this->get_api();
        $response = $api->post("tweets", ["text" => $payload], true);
        $response = json_encode($response);
        $response = json_decode($response, true);
        if ($response) {
            $data = [
                'success' => true,
                'message' => 'tweet posted',
                'tweet_id' => $response['data']['id']
            ];
        } else {
            $data = [
                'success' => false,
                'message' => 'Failed to post'
            ];
        }
        return json_encode($data);
    }

    public function post_reply(string $payload, string $id_string): string {
        $api = $this->get_api();
        $response = $api->post("tweets", ["text" => $payload, "reply" => ["in_reply_to_tweet_id" => $id_string]], true);
        $response = json_encode($response);
        $response = json_decode($response, true);
        if ($response) {
            $data = [
                'success' => true,
                'message' => 'reply posted',
                'tweet_id' => $response['data']['id']
            ];
        } else {
            $data = [
                'success' => false,
                'message' => 'Failed to post reply'
            ];
        }
        return json_encode($data);
    }
}





