<?php
namespace sabri\dblp;


class Springer {

    const TYPE_AUTHOR = 'name';
    const TYPE_TITLE = 'title';
    const TYPE_ALL = 'all';
 
    private $apiKey; 
    private $searchTerm;
    private $type;
    private $baseUrl = 'http://api.springer.com';

    function __construct($searchTerm, $type = self::TYPE_ALL) {
        $this->searchTerm = $searchTerm;
        $this->setType($type);
    }

    public function setSearchTerm($searchTerm) {
        $this->searchTerm = $searchTerm;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setApiKey($key) {
        $this->apiKey = $key;
    }

    public function result(){

        if ($this->searchTerm == null) {
            throw new Exception('Search term can not be empty.');
        }

        if ($this->apiKey == null) {
            throw new Exception('API key can not be empty.');
        }

        if ($this->type == self::TYPE_TITLE) {
            $request = $baseUrl . '/metadata/json?&api_key=' .
                        $this->apiKey . 
                        '&q='.$this->type . ':' . 
                        urlencode($this->searchTerm) . "&s=1&p=100";

        } else if ($this->type == self::TYPE_AUTHOR) {
            $request = $baseUrl . 
                    '/metadata/json?&api_key=' .
                    $this->apiKey.'&q=' . $this->type.':' . 
                    urlencode($this->searchTerm). "&s=1&p=100";
        } else {
            $request = $baseUrl . '/metadata/json?&api_key=' .
                        $this->apiKey.'&q=' . 
                        urlencode($this->searchTerm) . "&s=1&p=100";
        }

        $response = file_get_contents($request);
        $jsonobj_sp = json_decode($response);

        return $this->toArray($jsonobj_sp);
    }

    private function toArray($obj) {
        $resultsSpringer = [];

        for ($i=0; $i < sizeof($obj->records); $i++) {
            $authors = [];	
            foreach ($obj->records[$i]->creators as $creator) {
                $authors[] = $creator->creator;
            }
            $url = $obj->records[$i]->url;

            $resultsSpringer[] = [ 
                "url" => $url[0]->value,
                "title" => $obj->records[$i]->title,
                "authors" => $authors,
                "abstract" => $obj->records[$i]->abstract,
                "publisher" => "Springer"
            ];
        }

        return $resultsSpringer;
    }
}