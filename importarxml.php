<?php

require_once('tarefas.php');

ob_clean();
header ('Content-Disposition: attachment; filename="receita1.xml"');
header ('Content-Type: text/xml');
echo $oXml->asXML();
die();

?>