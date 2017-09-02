<?php

namespace BilginPro\Agency\Iha;

use Carbon\Carbon;
use GuzzleHttp;

/**
 * Class Crawler
 * @package BilginPro\Ajans\Iha
 */
class Crawler
{
    /**
     * @var string
     */
    protected $user_code = '';

    /**
     * @var string
     */
    protected $user_name = '';

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var int
     */
    protected $summary_length = 150;

    /**
     * @var array
     */
    protected $attributes = [
        'limit' => '5',
    ];

    /**
     * Create a new Crawler Instance
     */
    public function __construct($config)
    {
        $this->setParameters($config);
    }

    /**
     * Does the magic.
     * @return array
     */
    public function crawl($attributes = [])
    {
        $this->setAttributes($attributes);

        $response = $this->fetchUrl($this->getUrl());
        $xml = new \SimpleXMLElement($response);
        $result = [];
        $i = 0;
        foreach ($xml->channel->item as $item) {
            if ($this->attributes['limit'] > $i) {
                $news = new \stdClass;
                $news->code = (string)$item->HaberKodu;
                $news->title = (string)$item->title;
                $news->summary = (string)$this->shortenString($item->description, $this->summary_length);
                $news->content = (string)$item->description;
                $news->created_at = (new Carbon($item->pubDate))->format('d.m.Y H:i:s');
                $news->category = $this->titleCase($item->Kategori);
                $news->city = (!empty($item->Sehir) ? $this->titleCase($item->Sehir) : '');
                $news->images = [];
                if (isset($item->images) && count($item->images->image) > 0) {
                    foreach ($item->images->image as $image) {
                        $news->images[] = (string)$image;
                    }
                }

                $result[] = $news;
                $i++;
            }
        }

        return $result;
    }

    /**
     * Sets config parameters.
     */
    protected function setParameters($config)
    {
        if (!is_array($config)) {
            throw new \InvalidArgumentException('$config variable must be an array.');
        }
        if (array_key_exists('user_code', $config)) {
            $this->user_code = $config['user_code'];
        }
        if (array_key_exists('user_name', $config)) {
            $this->user_name = $config['user_name'];
        }
        if (array_key_exists('password', $config)) {
            $this->password = $config['password'];
        }
        if (array_key_exists('summary_length', $config)) {
            $this->summary_length = $config['summary_length'];
        }
    }

    /**
     * Sets filter attributes.
     * @param $attributes array
     */
    protected function setAttributes($attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }

    /**
     * Returns full url for crawling.
     * @return string
     */
    protected function getUrl()
    {
        $url = 'http://abone.iha.com.tr/yeniabone/rss2.aspx?Sehir=0&UserCode='
            . $this->user_code
            . '&UserName='
            . $this->user_name
            . '&UserPassword='
            . $this->password;

        return $url;
    }


    /**
     * Fethches given url and returns response as string.
     * @param $url
     * @param string $method
     * @param array $options
     *
     * @return string
     */
    protected function fetchUrl($url, $method = 'GET', $options = [])
    {
        $client = new GuzzleHttp\Client();
        $res = $client->request($method, $url, $options);
        if ($res->getStatusCode() == 200) {
            return (string)$res->getBody();
        }
        return '';
    }

    /**
     * Cuts the given string from the end of the appropriate word.
     * @param $str
     * @param $len
     * @return string
     */
    protected function shortenString($str, $len)
    {
        if (strlen($str) > $len) {
            $str = rtrim(mb_substr($str, 0, $len, 'UTF-8'));
            $str = substr($str, 0, strrpos($str, ' '));
            $str .= '...';
            $str = str_replace(',...', '...', $str);
        }
        return $str;
    }

    /**
     * Converts a string to "Title Case"
     * @param $str
     * @return string
     */
    protected function titleCase($str)
    {
        $str = mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
        return $str;
    }
}
