<?php
namespace sabri\dblp;


class Dblp {

    private $searchTerm;

    function __construct($searchTerm) {
        $this->searchTerm = $searchTerm;
    }

    public function setSearchTerm($searchTerm) {
        $this->searchTerm = $searchTerm;
    }

    public function result() {
        $request = 'http://dblp.uni-trier.de/search/publ/api?q=' .
                    urlencode( $this->searchTerm) .
                    '&h=1000&c=0&rd=1a&format=json';

        $response = file_get_contents($request);
        $jsonobj = json_decode($response);
        
        return $this->toArray($jsonobj);
    }

    private function toArray($obj) {
        $results = [];

        if (!isset($obj->result->hits->hit)) {
            return $results;
        }

        $hits = $obj->result->hits->hit;

        foreach ($hits as $hit) {
            $authors='';
            $author = null;
            if(isset($hit->info->authors->author)){
                if(is_array($author = $hit->info->authors->author)) {
                    for( $c=0; $c < sizeof($author); $c++) {
                        $authors[] = $author[$c];
                    }
                } else {
                    $authors[] = $author;
                }
            }

            $item = [
                'url' => $hit->info->url,
                'title' => $hit->info->title,
                'authors' => implode(' / ', $authors),
                'abstract' => '',
                'publisher' => 'DBLP'
            ];
            array_push($results, $item);
        }

        return $results;
    }
}
