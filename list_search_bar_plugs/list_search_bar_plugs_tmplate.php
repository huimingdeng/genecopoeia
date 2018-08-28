<style type="text/css">
	#SBody<?php echo $atts['class'];?>{margin-bottom: 10px;}
	#searchbar-group<?php echo $atts['class'];?>{width: <?php echo($atts['width']+40);?>px;  }
	#searchbar<?php echo $atts['class'];?> input[type='text']{width:<?php echo($atts['width']);?>px; height: 20px;}
	#searchbar<?php echo $atts['class'];?> .input-group-btn button.btn {height: 34px;}
	#searchbar<?php echo $atts['class'];?> ul li{list-style: none; overflow: hidden; clear: both; margin: 0px 5px; }
	#searchbar<?php echo $atts['class'];?> .tags-group{padding: 10px; width: 305px;}
	#searchbar<?php echo $atts['class'];?> .tags-group a{margin: 0px 5px 5px 0px; display: inline-block;}
</style>
<div id="SBody<?php echo $atts['class'];?>">
	<form action="/product/search3/" id="searchbar<?php echo $atts['class'];?>">
		<div class="input-group" id="searchbar-group<?php echo $atts['class'];?>">
			<input type="text" id="searchtext<?php echo $atts['class'];?>" name="s" class="form-control" placeholder="<?php echo $atts['title'];?>"	autocomplete="off">
			<span class="input-group-btn">
				<button class="btn btn-default" id="searchbarsubmit<?php echo $atts['class'];?>" type="button">
					<i class="glyphicon glyphicon-search"></i>
				</button>
			</span>
		</div>
		<?php if(!empty($tags_arr)){
			if(!is_null($tags_arr['product_type']))
				echo "<input type=\"hidden\" name='tags[]' value=\"".$tags_arr['product_type']."\" />\n";
			if(!empty($tags_arr['format'])){
				foreach ($tags_arr['format'] as $k => $v) {
					echo "<input type=\"hidden\" name='tags[]' value=\"".$v."\" />\n";
				}
			}
			if(!empty($tags_arr['species'])){
				foreach ($tags_arr['species'] as $k => $v) {
					echo "<input type=\"hidden\" name='tags[]' value=\"".$v."\" />\n";
				}
			}
		}?>
		<input type="hidden" name="search_type" value="1">
	</form>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#searchbar<?php echo $atts['class'];?>").submit(function() {
			if($("#searchtext<?php echo $atts['class'];?>").val()==""){
				$("#searchtext<?php echo $atts['class'];?>").focus();
				return false;
			}
		});
		$("#searchbarsubmit<?php echo $atts['class'];?>").click(function() {
			$("#searchbar<?php echo $atts['class'];?>").submit();
		});
	});
</script>
