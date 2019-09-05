<?php

namespace snuzi\dblp;

class Cinii extends ClientAbstract {

    const TYPE_AUTHOR = 'name';
    const TYPE_TITLE = 'title';
    const TYPE_ALL = 'all';

    private $request;
    private $searchTerm;

    private $baseUrl = 'http://ci.nii.ac.jp/opensearch/search';
    
    public function __construct()
    {
        $this->request = new Request();
    }

    private $queryParams = [
        'count' => 100,
        'start' => 1,
        'lang' => 'en',
        'format' => 'json',
        'title' => '',
        'affiliation' => '',
        'journal' => '',
        'issn' => '',
        'volume' => '',
        'issue' => '',
        'page' => '',
        'publisher' => '',
        'references' => '',
        'year_from' => '',
        'year_to' => '',
        'range' => '',
        'sortorder' => ''
    ];

    public function setSearchTerm($searchTerm, $type = self::TYPE_ALL)
    {
        $this->queryParams['author'] = '';
        $this->queryParams['title'] = '';

        if ($type == self::TYPE_AUTHOR) {
            $this->queryParams['author'] = $searchTerm;
        } elseif ($type == self::TYPE_TITLE) {
            $this->queryParams['title'] = $searchTerm;
        } else {
            $this->queryParams['q'] = $searchTerm;
        }
    }

    public function getResults()
    {
        if (
            empty($this->queryParams['title']) &&
            empty($this->queryParams['author']) &&
            empty($this->queryParams['q'])
        ) {
            throw new Exception('Search term can not be empty.');
        }

        $response = $this->request->getResult($this->baseUrl, $this->queryParams);

        return $this->toArray($response);
    }

    public function getPublisherName()
    {
        return 'Cinii';
    }

    protected function toArray($obj)
    {
        $publications = [];

        foreach( $obj as $itemObject) {
            $publication = new Publication();
            $authors = [];
            if(is_array($itemObject)) {
                foreach($itemObject[0] as $item) {
                    $c = 0;
                    if (is_array($item)) {
                        foreach ($item as $i) { 
                            foreach ($i as $itm => $info) {
                                if (is_object($info)) {
                                    $exit = true;
                                    foreach ($info as $b) {
                                        $url = $b;  
                                        if ($exit) break 2;
                                    }   
                                }
                            }
                
                            $publication->setLink($url);
                            $publication->setTitle($i->title);
                            $publication->setAbstract('');
                            $publication->setPublisher($this->getPublisherName());
                            $publication->setAuthors([]);

                            $publications[] = $publication;
                        }
                    }
                }
            }
        }

        return $publications;
    }
}
