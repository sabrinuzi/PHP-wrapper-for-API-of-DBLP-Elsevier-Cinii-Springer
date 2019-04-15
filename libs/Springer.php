<?php
namespace sabri\dblp;


class Springer {

    public static $TYPE_AUTHOR = 'name';
    public static $TYPE_TITLE = 'title';
    public static $TYPE_ALL = 'all';
 
    private $api_key; 
    private $searchTerm;
    private $type;
    private $baseUrl = 'http://api.springer.com';


    function __construct($searchTerm, $type='all'){
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
        $this->api_key = $key;
    }

    public function result(){

        if ($this->searchTerm == null) {
            throw new Exception('Search term can not be empty.');
        }

        if ($this->api_key == null) {
            throw new Exception('API key can not be empty.');
        }

        if ($this->type=='title') {
            $request = $baseUrl . '/metadata/json?&api_key='.$this->api_key.'&q='.$this->type.':' . urlencode( $this->searchTerm)."&s=1&p=100";
        } else if ($this->type=='author') {
            $request =$baseUrl . '/metadata/json?&api_key='.$this->api_key.'&q='.$this->type.':' . urlencode( $this->searchTerm )."&s=1&p=100";
        } else {
            $request = $baseUrl . '/metadata/json?&api_key='.$this->api_key.'&q=' . urlencode( $this->searchTerm )."&s=1&p=100";
        }

        $response = file_get_contents($request);
        $jsonobj_sp = json_decode($response);

        return $this->toArray($jsonobj_sp);
    }

    private function toArray($obj) {

        $results_springer = [];

        for($i=0; $i < sizeof($obj->records); $i++) {
            $authors = [];	
            foreach($obj->records[$i]->creators as $creator) {
                $authors[] = $creator->creator;
            }
            $url = $obj->records[$i]->url;

            $results_springer[] = [ 
                    "url" = >$url[0]->value,
                    "title" => $obj->records[$i]->title,
                    "authors" => $authors,
                    "abstract" => $obj->records[$i]->abstract,
                    "publisher" => "Springer"
                ];

        }

        return $results_springer;
    }

}

?>
