<?php

namespace TrubinEN;

use Curl;

class getInfoYouTube
{
    const API_URL = 'https://www.googleapis.com/youtube/v3/videos';
    private $token = "AIzaSyBxS2s_PyDsv_vv6kWSonD9O1zGklS9nm0";
    private $code = '';
    private $video = [];


    public function __construct(string $code, string $token = '')
    {
        $this->code = $code;
        $this->setToken($token);
        $this->handleData();
    }

    /**
     * Add user token
     *
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token ? $token : $this->token;
    }

    /**
     * Get video title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getVideoData('title');
    }

    /**
     * Get video date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->getVideoData('date');
    }

    /**
     * Send request
     * https://www.googleapis.com/youtube/v3/videos?id=85swsl4UW30&key=AIzaSyBxS2s_PyDsv_vv6kWSonD9O1zGklS9nm0&part=snippet
     *
     * @param $code - youtube video code
     * @return string     *
     */
    private function request()
    {
        try {
            $curl = new Curl\Curl();
            $curl->get(self::API_URL, [
                'id' => $this->code,
                'key' => $this->token,
                'part' => 'snippet',
            ]);

            if ($curl->error || empty($curl->response)) {
                return false;
            }

            return json_decode($curl->response);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Handle responce youtube data
     *
     */
    private function handleData()
    {
        $responce = $this->request();

        if (empty($responce) && empty($responce->items) && is_array($responce->items))
            return;

        $item = array_shift($responce->items);

        if (empty($item->snippet))
            return;

        $this->setData($item->snippet);
    }

    /**
     * Set data
     *
     * @param $data
     */
    private function setData($data)
    {
        if (!empty($data->publishedAt))
            $this->video['date'] = $data->publishedAt;

        if (!empty($data->title))
            $this->video['title'] = $data->title;
    }

    /**
     * Get video data
     *
     * @param string $key
     * @return mixed|string
     */
    private function getVideoData(string $key)
    {
        try {
            if (empty($this->video[$key]))
                throw new \Exception('error');

            return $this->video[$key];
        } catch (\Exception $exception) {
            return 'Error: video data could not be found!';
        }
    }
}