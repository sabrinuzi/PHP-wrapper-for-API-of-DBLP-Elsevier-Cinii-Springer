<?php


namespace sabri\dblp;


class Cinii {

    const TYPE_AUTHOR = 'name';
    const TYPE_TITLE = 'title';
    const TYPE_ALL = 'all';

    private $searchTerm;
    private $type;

    function __construct($searchTerm, $type = self::TYPE_ALL) {
        $this->searchTerm = $searchTerm;
        $this->setType($type);
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setSearchTerm($searchTerm) {
        $this->searchTerm = $searchTerm;
    }

    public function result() {
        if ($this->searchTerm == null) {
            throw new Exception('Search term can not be empty.');
        }

        if ($this->type == self::TYPE_TITLE) {
            $request = 'http://ci.nii.ac.jp/opensearch/search?q=' 
                        . urlencode($this->searchTerm) .
                        '&count=100&start=1&lang=en&title=&author=&affiliation=&journal=&issn=&volume=&issue=&page=&publisher=&references=&year_from=&year_to=&range=&sortorder=&format=json';
        } elseif ($this->type == self::TYPE_AUTHOR) {
            $request = 'http://ci.nii.ac.jp/opensearch/search?q=&count=100&start=1&lang=en&title=' 
                    . urlencode($this->searchTerm) . 
                    '&author=&affiliation=&journal=&issn=&volume=&issue=&page=&publisher=&references=&year_from=&year_to=&range=&sortorder=&format=json';
        } else {
            $request = 'http://ci.nii.ac.jp/opensearch/search?q=&count=100&start=1&lang=en&title=&author=' .
                        urlencode($this->searchTerm) .
                        '&affiliation=&journal=&issn=&volume=&issue=&page=&publisher=&references=&year_from=&year_to=&range=&sortorder=&format=json';
        }
        $response = file_get_contents($request);
        $jsonobj  = json_decode($response);

        return $this->toArray($jsonobj);
    }

    private function toArray($obj) {
        $results = [];
        foreach( $obj as $ob) {
            if(is_array($ob)) {
                $res_array = $ob; 
                foreach($ob[0] as $item) {
                    $c = 0;
                    if (is_array($item)) {
                        foreach ($item as $i) { 
                            foreach ($i as $itm => $info) {
                                if (is_object($info)) {
                                    $exit=true;
                                    foreach ($info as $b) {
                                        $url = $b;  
                                        if ($exit) break 2;
                                    }   
                                }
                            }

                            $item = [
                                'url' => $url,
                                'title'=>$i->title,
                                'authors'=>'',
                                'abstract'=>'',
                                'publisher'=>'CiNii' 
                            ];

                            array_push($results, $item);
                        }
                    }
                }
            }
        }

        return $results;
    }
}
