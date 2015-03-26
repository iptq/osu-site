<?php

include("includes/functions.php");

$username = $_GET['user'];

$user = json_decode(file_get_contents("http://osu.ppy.sh/api/get_user?k=" . $APIKEY . "&u=" . $username))[0];
$username = $user->username;

echo "<!--";
print_r($user);
echo "-->";

$user_best = json_decode(file_get_contents("http://osu.ppy.sh/api/get_user_best?k=" . $APIKEY . "&limit=10&u=" . $username));

echo "<!--";
print_r($user_best);
echo "-->";

$user_recent = json_decode(file_get_contents("http://osu.ppy.sh/api/get_user_recent?k=" . $APIKEY . "&limit=50&u=" . $username));

echo "<!--";
print_r($user_recent);
echo "-->";

?>

<html>
	<head>
		<title><?php echo $username; ?> | osu!</title>
		<link rel="stylesheet" href="/css/main.css" />
	</head>
	<body data-spy="scroll" data-target="#sidecol">
		<?php include("includes/header.php"); ?>

		<div class="jumbotron">
			<table cellpadding="5" cellspacing="5">
				<tr style="vertical-align:middle;">
					<td rowspan="2" style="padding-right:10px;"><img src="http://a.ppy.sh/<?php echo $user->user_id; ?>" class="img_responsive" width="128" /></td>
					<td>
						<h1 style="margin:0;"><?php echo $username; ?></h1>
						<?php echo $user->country; ?> player. Rank <?php echo $user->pp_rank; ?> with <?php echo round($user->pp_raw,2); ?> pp.
					</td>
				</tr>
			</table>
		</div>
		<div class="row" id="content">
			<nav class="col-md-3 hidden-sm" id="sidecol">
			    <ul class="nav nav-stacked" id="sidebar">
			        <li><a href="#general"><?php echo $username; ?></a></li>
			        <li><a href="#ranks">Top Ranks</a></li>
			        <li><a href="#historical">Historical</a></li>
			        <li><a href="#beatmaps">Beatmaps</a></li>
			        <li><a href="#achievements">Achievements</a></li>
			    </ul>
			</nav>
			<div class="col-md-9">
			    <div class="page-header">
			    	<h2 id="general"><?php echo $username; ?></h2>
			    </div>
			    
			    <table class="table table-hover table-striped table-bordered">
			    	<tr>
			    		<td scope="row">Ranked Score</td>
			    		<td><?php echo number_format($user->ranked_score); ?></td>
			    	</tr>
			    	<tr>
			    		<td scope="row">Accuracy</td>
			    		<td><?php echo round($user->accuracy,2); ?>%</td>
			    	</tr>
			    	<tr>
			    		<td scope="row">Play Count</td>
			    		<td><?php echo number_format($user->playcount); ?></td>
			    	</tr>
			    	<tr>
			    		<td scope="row">Total Score</td>
			    		<td><?php echo number_format($user->total_score); ?></td>
			    	</tr>
			    	<tr>
			    		<td scope="row">Level</td>
			    		<td><?php echo round($user->level, 2); ?></td>
			    	</tr>
			    	<tr>
			    		<td scope="row">Total Hits</td>
			    		<td><?php echo number_format($user->count300 + $user->count100 + $user->count50); ?></td>
			    	</tr>
			    </table>
			    
			    <div class="page-header">
			    	<h2 id="ranks">Top Ranks</h2>
			    </div>
			    
			    <table class="table table-hover table-striped table-bordered">
			    	<thead>
				    	<tr>
				    		<th>#</th>
				    		<th>Rank</th>
				    		<th>Beatmap</th>
				    		<th>Accuracy</th>
				    		<th>PP</th>
				    	</tr>
			    	</thead>
			    	<?php
			    	$count = 0;
			    	foreach($user_best as $score) {
			    		$beatmap_info = json_decode(file_get_contents("http://osu.ppy.sh/api/get_beatmaps?k=" . $APIKEY . "&b=" . $score->beatmap_id))[0];
						
						echo "<!--";
						print_r($beatmap_info);
						echo "-->";
			    		?>
			    		<tr>
			    			<td><?php echo $count + 1; ?></td>
			    			<td><img src="https://osu.ppy.sh/images/<?php echo $score->rank; ?>.png" width="32" /></td>
			    			<td><?php echo $beatmap_info->artist . " - " . $beatmap_info->title; ?><br /><small><?php echo $beatmap_info->version; ?></small></td>
			    			<td><?php echo calc_acc($score->count300, $score->count100, $score->count50, $score->countmiss) * 100; ?>%</td>
			    			<td><?php echo round($score->pp,2); ?><br /><small><?php echo round(pow(0.95, $count) * $score->pp,2); ?></small></td>
			    		</tr>
			    	<?php $count += 1;
			    	} ?>
			    </table>
			    
			    <div class="page-header">
			    	<h2 id="historical">Historical</h2>
			    </div>
			    
			    <table class="table table-hover table-striped table-bordered">
			    	<thead>
				    	<tr>
				    		<th>#</th>
				    		<th>Rank</th>
				    		<th>Beatmap</th>
				    		<th>PP</th>
				    	</tr>
			    	</thead>
			    	<?php
			    	$count = 0;
			    	foreach($user_recent as $score) {
						if($score->rank == "F") { continue; }
						
			    		$beatmap_info = json_decode(file_get_contents("http://osu.ppy.sh/api/get_beatmaps?k=" . $APIKEY . "&b=" . $score->beatmap_id))[0];
						
						echo "<!--";
						print_r($beatmap_info);
						echo "-->";
			    		?>
			    		<tr>
			    			<td><?php echo $count + 1; ?></td>
			    			<td><img src="https://osu.ppy.sh/images/<?php echo $score->rank; ?>.png" width="32" /></td>
			    			<td><?php echo $beatmap_info->artist . " - " . $beatmap_info->title; ?><br /><small><?php echo $beatmap_info->version; ?></small></td>
			    			<td><?php echo round($score->pp,2); ?><br /><small><?php echo round(pow(0.95, $count) * $score->pp,2); ?></small></td>
			    		</tr>
			    	<?php $count += 1; if ($count == 5) { break; }
			    	} ?>
			    </table>
			    
			    <div class="page-header">
			    	<h2 id="beatmaps">Beatmaps</h2>
			    </div>
			    
			    <div class="page-header">
			    	<h2 id="achievements">Achievements</h2>
			    </div>
			</div>
		</div>
		
		<?php include("includes/footer.php"); ?>
		
		<script type="text/javascript" src="/js/jquery.min.js"></script>
		<script type="text/javascript" src="/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/js/main.js"></script>
	</body>
</html>