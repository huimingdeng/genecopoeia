<style type="text/css">
	.wrap ul li ul{
		list-style: circle;
		margin-left: 0px;
		padding-left: 30px;
		display: none;
	}
</style>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.faq-toggle').click(function(event) {
			$(this).siblings('ul').toggle(1000);
		});
	});
</script>
<div class="wrap">
	<?php if ($atts['title']): ?>
		<h2><?php echo $atts['title']; ?></h2>
	<?php endif ?>
	<?php if(!empty($faqs)){ ?>
		<ul>
			<?php foreach($faqs as $faq) {?>
				<li>
					<a href="javascript:void(0);" class="faq-toggle"><?php echo $faq['title']; ?></a>
					<ul>
						<li><?php echo $faq['answer']; ?></li>
					</ul>
				</li>
			<?php } ?>
		</ul>
	<?php } ?>
</div>