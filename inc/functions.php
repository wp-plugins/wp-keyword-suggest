<?php

class wpks_api
{
    private $keywords = NULL;

    function __construct()
    {
    
    }
    
    function __destruct()
    {
        $this->keywords = NULL;
    }
    
    public function request( $keyword = NULL )
    {
        if($keyword == NULL)
            return;

        /*
        This plugin makes to calls to seowp.es, the first one to create the URLs of the autocomplete service, and the second one to filter and complete the results.
        */
        $q = str_replace(' ', '+', $keyword);
        $url = 'http://www.seowp.es/wpks/generate_urls.php?q='.$q;

        $rsp_json = wp_remote_fopen($url);
        $array_urls = json_decode($rsp_json);

        foreach($array_urls as $item)
        {
            $params[$item->service] = wp_remote_fopen($item->url);
        }

        foreach ($params as $k => $v)
        {
            $v = utf8_encode($v);
            $p[] = $k . '=' . urlencode($v);
        }

        $url = 'http://www.seowp.es/wpks/keywords_list.php?q='.$q . '&' . implode('&', $p);
        $keywords_list = wp_remote_fopen($url);

        return $keywords_list;
    }
}

?>
