<!DOCTYPE html>
<html>
<head>
	<script>
		function subst() {
			var vars = {};
			var x = window.location.search.substring(1).split('&');
			for (var i in x) {
				var z = x[i].split('=', 2);
				vars[z[0]] = unescape(z[1]);
			}
			var x = ['frompage', 'topage', 'page', 'webpage', 'section', 'subsection', 'subsubsection'];
			for (var i in x) {
				var y = document.getElementsByClassName(x[i]);
				for (var j = 0; j < y.length; ++j) y[j].textContent = vars[x[i]];
			}
		}
	</script>
</head>
<body style="border:0; margin: 0;" onload="subst()">
<style>
	#header {
		height: 120px;
		background: <?php echo $headerBackgroundColor ?>;
	<?php if ($showLines) { ?> border-bottom: 1px solid <?php echo $lineColor ?>;
	<?php } ?>
	}

	#header > table {
		width: 100%;
		margin-bottom: 0px;
		color: #555;
		font-family: arial, sans-serif;
		padding: 20px;
		padding-top: 20px;
		padding-bottom: 0
	}

	.title {
		color: <?php echo $headerTitleColor; ?>;
		font-weight: bold;
	}

	.subtitle {
		color: <?php echo $headerSubTitleColor; ?>;
		font-weight: normal;
	}

	.paging {
		color: <?php echo $headerSubTitleColor; ?>;
		text-align: right;
	}
</style>
<div id="header">
	<table>
		<tr>
			<td style="width: 180px"><img src="<?php echo  $logo ?>" style="max-height: 82px; max-width: 180px"></td>
			<td>
				<span class="title"><?php echo  $headerTitle ?></span><br>
				<span class="subtitle"><?php echo  $headerSubTitle ?></span>
			</td>
			<td class="paging">
				Page <span class="page"></span> of <span class="topage"></span>
			</td>
		</tr>
	</table>
</div>
</body>
</html>