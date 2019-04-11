
<div class="wrap">
	<?php if ($atts['title']): ?>
		<h2><?php echo $atts['title']; ?></h2>
	<?php endif ?>
	<?php if(!empty($faqs)){ ?>
		<ul id="<?php echo $atts['class']; ?>">
			<?php foreach($faqs as $faq) {?>
				<li>
					<a href="javascript:void(0);" class="faq-toggle" onclick="MyFAQs.faqToggle(this);"><?php echo $faq['title']; ?></a>
					<ul>
						<li><?php echo $faq['answer']; ?></li>
					</ul>
				</li>
			<?php } ?>
		</ul>
	<?php } ?>
</div>