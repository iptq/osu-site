<?php

include("../includes/functions.php");

function DOMinnerHTML(DOMNode $element) 
{ 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child) 
    { 
        $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    return $innerHTML; 
}

$data = file_get_contents("https://osu.ppy.sh/p/pp");
$dom = new DOMDocument();
$dom->loadHTML($data);
$xpath = new DOMXPath($dom);
$tags = $xpath->query("//table[@class='beatmapListing']");
$table = DOMinnerHTML($tags->item(0));

$dom2 = new DOMDocument();
$dom2->loadHTML($table);
$xpath2 = new DOMXPath($dom2);
$rows = $xpath2->query("//tr[@class]");

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Global Ranking | osu!</title>
		<link rel="stylesheet" href="/css/main.css" />
    </head>
    <body>
        <?php include("../includes/header.php"); ?>
        
        <div class="page-header">
            <h1>Global Ranking</h1>
        </div>
        
        <!-- <?php foreach($rows as $row) { var_dump($row); } ?> -->
        
        <?php
        $count = 0;
        ?>
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Player</th>
                    <th>Accuracy</th>
                    <th>PP</th>
                </tr>
            </thead>
            <?php
            foreach ($rows as $row) {
                $content = DOMinnerHTML($row);
                $dom2 = new DOMDocument();
                $dom2->loadHTML($content);
                $xpath2 = new DOMXPath($dom2);
                $cols = $xpath2->query("//td");
                $info = array();
                foreach($cols as $col) { array_push($info, $col); }
            ?>
                <tr>
                    <td><?php echo $count + 1; ?></td>
                    <td><a href="/u/<?php echo trim($info[1]->nodeValue); ?>"><?php echo trim($info[1]->nodeValue); ?></a></td>
                    <td><?php echo trim($info[2]->nodeValue); ?></td>
                    <td><?php echo trim($info[4]->nodeValue); ?></td>
                </tr>
            <?php
                $count += 1;
            }
            ?>
        </table>
        
        <?php include("../includes/footer.php"); ?>
		
		<script type="text/javascript" src="/js/jquery.min.js"></script>
		<script type="text/javascript" src="/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/js/main.js"></script>
    </body>
</html>