<?php
namespace snuzi\dblp;

abstract class ClientAbstract {
    abstract public function getResults();
    abstract public function setSearchTerm($searchTerm);
    abstract protected function toArray($publicationObjects);
    abstract public function getPublisherName();
}