<?php

namespace App\Libs\Baidu;

use App\Exceptions\ErrorMessageException;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BaiduAPI {
    const ACCESS_KEY = 'WyhBhL88TTeWG7zWC1TQepMl';
    const SECRET_Key = 'uzG2U67GqK5PZ0rSZGMh5xtTjDxpgnac';

    const API_OAUTH = 'https://openapi.baidu.com/oauth/2.0/token';
    const API_VOICE = 'https://tsn.baidu.com/text2audio';
    const CACHE_KEY = 'BAIDU_OAUTH_TOKEN';

    private $cuid = '123456';
    private $ctp = '1';
    private $lan = 'zh';

    private $accessToken = '';
    private $client = false;

    public function __construct()
    {

        $this->client = new Client();
        $accessToken = Cache::get(self::CACHE_KEY, function() {
            return $this->getAccessToken();
        });
        $this->accessToken = $accessToken;
    }

    private function getAccessToken(){
        $query = http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => self::ACCESS_KEY,
            'client_secret' => self::SECRET_Key,
        ]);

        $url = self::API_OAUTH . '?' . $query;

        try {
            $response = $this->client->request('GET', $url);
            $data = json_decode($response->getBody()->getContents(), true);
        }catch (\Exception $e){
            throw new ErrorMessageException($e);
        }

        if (!isset($data['access_token'], $data['expires_in'])){
            throw new ErrorMessageException('语音播报错误，请联系管理员。');
        }

        Cache::put(self::CACHE_KEY, $data['access_token'], Carbon::now()->addSeconds($data['expires_in']));

        return $data['access_token'];
    }

    public function generateVoice($text,$type=1){
        $voiceFileName = date('YmdHis') . uniqid() . '.mp3';

        $query = http_build_query([
            'tex' => $text, // 文本
            'tok' => $this->accessToken, // access_token
            'cuid' => $this->cuid, // 用户唯一标识
            'ctp' => $this->ctp, // 客户端类型选择，web端填写固定值1
            'lan' => $this->lan, // 固定值zh
            'spd' => 4, // 语速
            'pit' => 1, // 语调
            'vol' => 9, // 音量
            'per' => 0, // 发音人
        ]);

        $url = self::API_VOICE . '?' . $query;

        try {
            $response = $this->client->request('GET', $url);
            $content = $response->getBody()->getContents();

            // 调用失败返回json
            $decodedResponse = json_decode($content, true);
            if (is_array($decodedResponse)){
                Log::error($decodedResponse);
                throw new \Exception('');
            }

            Storage::disk('voice')->put($voiceFileName, $content);

        }catch (\Exception $e){
            Log::error($e->getMessage());
            throw new ErrorMessageException('语音播报错误，请联系管理员');
        }

        return $type?Storage::disk('voice')->url($voiceFileName):$voiceFileName;
    }
}