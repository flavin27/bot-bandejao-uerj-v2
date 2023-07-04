<?php
require __DIR__ . '/vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;
use Dotenv\Dotenv;
Dotenv::createImmutable(__DIR__)->load();
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
        return new TwitterOAuth($this->apiKey, $this->apiSecret, $this->accessToken, $this->accessTokenSecret);
    }
    public function post_tweet(string $payload): string {
        $api = $this->get_api();
        $response = $api->post("statuses/update", ["status" => $payload]);
        if ($response) {
            $data = [
                'success' => true,
                'message' => 'tweet posted',
                'tweet_id' => $response->id_str
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
        $response = $api->post("statuses/update", ["status" => $payload, "in_reply_to_status_id" => $id_string]);
        if ($response) {
            $data = [
                'success' => true,
                'message' => 'reply posted',
                'tweet_id' => $response->id_str
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

$client = new twitter();
$response = $client->post_tweet("teste reply");
$data = json_decode($response);
$id = $data->tweet_id;
$response2 = $client->post_reply("toma ai a respota", $id);
print_r($response);
print_r($response2);


