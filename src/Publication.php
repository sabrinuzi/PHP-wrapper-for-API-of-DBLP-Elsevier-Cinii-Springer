<?php

namespace snuzi\dblp;

class Publication {
    /** @var string */
    private $link;

    /** @var string */
    private $title;

    /** @var Author[] */
    private $authors;

    /** @var string */ 
    private $abstract;

    /** @var string */
    private $publisher;

    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param Author[] $authors
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;

        return $this;
    }

    public function getAuthors()
    {
        return $this->authors;
    }

    public function addAuthor($author)
    {
        $this->authors[] = $author;
        
        return $this;
    }

    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;

        return $this;
    }

    public function getAbstract()
    {
        return $this->abstract;
    }

    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    public function getPublisher()
    {
        return $this->publisher;
    }
}