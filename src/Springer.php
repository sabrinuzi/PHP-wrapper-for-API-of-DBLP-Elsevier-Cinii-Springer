<?php

namespace snuzi\dblp;

use Exception;

class Springer extends ClientAbstract {

    const TYPE_AUTHOR = 'name';
    const TYPE_TITLE = 'title';
    const TYPE_ALL = 'all';
 
    private $apiKey; 
    private $searchTerm;

    private $queryParams = [
        'p' => 100,
        's' => 1,
    ];

    private $baseUrl = 'http://api.springer.com/metadata/json';

    public function setSearchTerm($searchTerm, $type = self::TYPE_ALL)
    {
        if ($type == self::TYPE_AUTHOR || $type == self::TYPE_TITLE) {
            $this->queryParams['q:' . $type] = $searchTerm;
        } else {
            $this->queryParams['q'] = $searchTerm;
        }
    }

    public function setApiKey($key)
    {
        $this->queryParams['api_key'] = $key;
    }

    public function getResults()
    {
        if (empty($this->queryParams['api_key'])) {
            throw new Exception('API key can not be empty.');
        }

        if (
            empty($this->queryParams['q:' . self::TYPE_TITLE]) &&
            empty($this->queryParams['q:' . self::TYPE_AUTHOR]) &&
            empty($this->queryParams['q'])
        ) {
            throw new Exception('Search term can not be empty.');
        }
        
        $request = new Request();
        $response = $request->getResult($this->baseUrl, $this->queryParams);

        return $this->toArray($response);
    }

    public function getPublisherName()
    {
        return 'Springer';
    }

    protected function toArray($obj)
    {
        $publications = [];

        for ($i=0; $i < sizeof($obj->records); $i++) {
            $publication = new Publication();
            $author = new Author();
            $authors = [];	
            foreach ($obj->records[$i]->creators as $creator) {
                $author->setFirstName($creator->creator);
                $authors[] = $author;
            }
            $url = $obj->records[$i]->url;

            $publication->setLink($url[0]->value);
            $publication->setTitle($obj->records[$i]->title);
            $publication->setAbstract($obj->records[$i]->abstract);
            $publication->setPublisher($this->getPublisherName());
            $publication->setAuthors($authors);

            $publications[] = $publication;
        }

        return $publications;
    }
}