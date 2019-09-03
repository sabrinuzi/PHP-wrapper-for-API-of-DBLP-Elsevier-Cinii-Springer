<?php
require __DIR__ . '../../vendor/autoload.php';

use snuzi\dblp\Dblp;
use snuzi\dblp\Cinii;
use snuzi\dblp\Elsevier;
use snuzi\dblp\Springer;

/*
$dbplClient = new Dblp();
$dbplClient->setSearchTerm('elton'); 
$dbplResults = $dbplClient->getResults();
var_dump($dbplResults);
*/

/*
$ciniiClient = new Cinii();
$ciniiClient->setSearchTerm('elton'); 
$ciniiResults = $ciniiClient->getResults();
var_dump($ciniiResults);
*/

/*

$elseiverAPIKey = 'xxx';

$elseiverClient = new Elsevier();
$elseiverClient->setSearchTerm('elton'); 
$elseiverClient->setApiKey($elseiverAPIKey); 
$elseiverResults = $elseiverClient->getResults();
var_dump($elseiverResults);
*/

$springerAPIKey = 'xxx';

$springerClient = new Springer();
$springerClient->setSearchTerm('elton' ); 
$springerClient->setApiKey($springerAPIKey); 
$springerResults = $springerClient->getResults();
var_dump($springerResults);