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
    protected $userCode = '';

    /**
     * @var string
     */
    protected $userName = '';

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var int
     */
    protected $summaryLength = 150;

    /**
     * @var int
     */
    protected $limit = 5;

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
    public function crawl()
    {
        $response = $this->fetchUrl($this->getUrl());
        $xml = new \SimpleXMLElement($response);
        $result = [];
        $i = 0;
        foreach ($xml->channel->item as $item) {
            if ($this->limit > $i) {
                $news = new \stdClass;
                $news->code = (string)$item->HaberKodu;
                $news->title = (string)$item->title;
                $news->summary = (string)$this->shortenString($item->description, $this->summaryLength);
                $news->content = (string)$item->description;
                $news->created_at = (new Carbon($item->pubDate))->format('d.m.Y H:i:s');
                $news->category = $this->titleCase($item->Kategori);
                $news->city = (!empty($item->Sehir) ? $this->titleCase($item->Sehir) : '');
                $news->images = [];
                if (isset($item->images) > 0 && count($item->images->image) > 0) {
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
    public function setParameters($config)
    {

        if (!is_array($config))
            throw new \InvalidArgumentException('$config variable must be an array.');
        if (array_key_exists('userCode', $config))
            $this->userCode = $config['userCode'];
        if (array_key_exists('userName', $config))
            $this->userName = $config['userName'];
        if (array_key_exists('password', $config))
            $this->password = $config['password'];
        if (array_key_exists('summaryLength', $config))
            $this->summaryLength = $config['summaryLength'];
    }

    /**
     * Returns full url for crawling.
     * @return string
     */
    public function getUrl()
    {
        $url = 'http://abone.iha.com.tr/yeniabone/rss2.aspx?Sehir=0&UserCode='
            . $this->userCode
            . '&UserName='
            . $this->userName
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
    public function fetchUrl($url, $method = 'GET', $options = [])
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
    public function shortenString($str, $len)
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
    public function titleCase($str)
    {
        $str = mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
        return $str;

    }
}
