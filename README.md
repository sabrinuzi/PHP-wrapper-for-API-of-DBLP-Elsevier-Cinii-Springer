# PHP-wrapper-for-API-of-DBLP-Elsevier-Cinii-Springer
PHP wrapper for API of DBLP, Elsevier, Cinii, Springer

How to use:
1- include('libs/Springer.php');
2- $springer=new Springer($_GET["q"],$type);
	 $results_springer=$springer->result();
3- Loop the $results_springer or var_dump($results_springer) to see the structure.
