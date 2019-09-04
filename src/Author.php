<?php

namespace snuzi\dblp;

class Author {
    /** @var string */
    private $firstName;
    
    /** @var string */
    private $lastName;

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getFullName()
    {
        return implode(' ', [$this->firstName, $this->lastName]);
    }
}