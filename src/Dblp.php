<?php
namespace snuzi\dblp;

use Exception;

class Dblp extends ClientAbstract {

    private $searchTerm;

    private $queryParams = [
        'h' => 100,
        'c' => 0,
        'rd' => '1a',
        'format' => 'json',
    ];

    private $baseUrl = 'http://dblp.uni-trier.de/search/publ/api';

    public function setSearchTerm($searchTerm)
    {
        $this->queryParams['q'] = $searchTerm;
    }

    public function getResults()
    {
        if (empty($this->queryParams['q'])) {
            throw new Exception('Search term can not be empty.');
        }
        
        $request = new Request();
        $response = $request->getResult($this->baseUrl, $this->queryParams);

        return $this->toArray($response);
    }

    public function getPublisherName()
    {
        return 'DBLP';
    }

    protected function toArray($obj)
    {
        $publications = [];

        if (!isset($obj->result->hits->hit)) {
            return $publications;
        }

        $hits = $obj->result->hits->hit;

        foreach ($hits as $hit) {
            $publication = new Publication();
            $authors = [];

            if(isset($hit->info->authors->author)){
                $author = new Author();
                if(is_array($authorItem = $hit->info->authors->author)) {
                    for( $c=0; $c < sizeof($authorItem); $c++) {
                        $author = new Author();
                        $author->setFirstName($authorItem[$c]);
                        $authors[] = $author;
                    }
                } else {
                    $author->setFirstName($authorItem);
                    $authors[] = $author;
                }
            }

            $publication->setLink($hit->info->url);
            $publication->setTitle($hit->info->title);
            $publication->setAbstract('');
            $publication->setPublisher($this->getPublisherName());
            $publication->setAuthors($authors);

            $publications[] = $publication;
        }

        return $publications;
    }
}
