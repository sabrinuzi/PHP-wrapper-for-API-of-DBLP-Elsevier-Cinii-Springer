<?php

namespace snuzi\dblp;

use Exception;

class Elsevier {

    private $baseUrl = 'https://api.elsevier.com/content/search/sciencedirect';//'http://api.elsevier.com/content/search/index:SCIDIR';
    private $queryParams = ['count' => 100];
    private $apiKey; 
    private $idx;
    private $offset;
    private $countTotal;
    // Let's pull a maximum of 50 publication results per API call	
    private $countIncrement;
    private $loopThrough;
    private $totalResults;
    private $pubCtr;

    function __construct() {
        $this->idx = 0;
        $this->offset = 0;
        $this->countTotal = 0;
        $this->countIncrement = 50;
        $this->loopThrough = 1;
        $this->totalResults = 0;
        $this->pubCtr = 0;
    }

    public function setApiKey($key)
    {
        $this->queryParams['APIKey'] = $key;
        $this->apiKey = $key;
    }

    public function setSearchTerm($searchTerm)
    {
        $this->queryParams['query'] = $searchTerm;
    }

    public function getPublisherName()
    {
        return 'Elsevier';
    }

    public function getResults(){
        $publications = [];
        
        if (empty($this->queryParams['query'])) {
            throw new Exception('Search term can not be empty.');
        }

        if (empty($this->queryParams['APIKey'])) {
            throw new Exception('API key must be set.');
        }

        
        $request = new Request();
        $response = $request->getResult(
            $this->baseUrl, 
            $this->queryParams,
            [
                'X-ELS-APIKey' => $this->apiKey,
                'Accept' => 'application/json'
            ],
            true
        );
        
        $publicationsList = $response['search-results']['entry'];
      
        return $this->toArray($publicationsList);
    }

    private function toArray($publicationList) {
        $publications = [];
        $resultsElsevier = [];

        foreach ($publicationList as $key => $pubInfo) {
            $authors = [];
            $publication = new Publication();

            if (isset( $pubInfo['authors'])) {
                foreach ($pubInfo['authors'] as $creator) {
                    $author = new Author();

                    if (is_array($creator)) {
                        for ($x = 0; $x < sizeof($creator); $x++) {  
                            if (isset($creator[$x]['given-name'])) {
                                $author->setFirstName($creator[$x]['given-name']);

                            }
                            if (isset($creator[$x]['surname'])) {
                                $author->setLastName($creator[$x]['surname']);
                            }
                        }
                    } else {
                        $author->setFirstName($creator);
                    }
                    $authors[] = $author;
                }
            }

            if (!isset($pubInfo['error'])) {
                $url = $pubInfo['link'][1];
                $publication->setLink($url['@href']);
                $publication->setTitle($pubInfo['dc:title']);
                $publication->setAbstract('');
                $publication->setPublisher($this->getPublisherName());
                $publication->setAuthors($authors);

                $publications[] = $publication;
            }
        }

        return $publications;
    }
}