<?php
/**
 * 把下列的链接转换成?p=xxx形式的链接
 */
require_once(dirname(__FILE__).'/../wp-blog-header.php');
header('HTTP/1.1 200 OK');

$url_need_to_get_post_id = array(
	'http://www.genecopoeia.com/product/cellfree-clone-kit/',
	'http://www.genecopoeia.com/?p=4294',
	'http://www.genecopoeia.com/?p=4294',
	'http://www.genecopoeia.com/product/search/detail.php?prt=7&key=Mm24188',
	'http://www.genecopoeia.com/product/cellfree/kit.php',
	'http://www.genecopoeia.com/product/ndy-fluor-350-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/ndy-fluor-350-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/ndy-fluor-350-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/ndy-fluor-350-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/ndy-fluor-350-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/ndy-fluor-350-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/ndy-fluor-350-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/ndy-fluor-350-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/ndy-fluor-350-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/ndy-fluor-350-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/ndy-fluor-350-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-350-goat-anti-mouse-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-350-goat-anti-mouse-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/safe-harbor/',
	'http://www.genecopoeia.com/product/greenview-dna-gel-stain/',
	'http://www.genecopoeia.com/product/greenview-dna-gel-stain/',
	'http://www.genecopoeia.com/product/greenview-dna-gel-stain/',
	'http://www.genecopoeia.com/product/cy3-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/dna-gel-stains/',
	'http://www.genecopoeia.com/product/cellfree/kit.php',
	'http://www.genecopoeia.com/product/aavs1-safe-harbor/',
	'http://www.genecopoeia.com/product/aavs1-safe-harbor/',
	'http://www.genecopoeia.com/product/aavs1-safe-harbor/',
	'http://www.genecopoeia.com/product/secrete-pair/dual-luminescence-assay/',
	'http://www.genecopoeia.com/product/creb-tre/',
	'http://www.genecopoeia.com/product/orf/orf-clone/',
	'http://www.genecopoeia.com/product/reactive-dyes-biotin-derivatives/',
	'http://www.genecopoeia.com/product/genome-editing-tools/genome-editing/',
	'http://www.genecopoeia.com/product/caspase-activity-assays/',
	'http://www.genecopoeia.com/product/ezshuttle-lr/',
	'http://www.genecopoeia.com/product/orf/orf-clone/',
	'http://www.genecopoeia.com/category/product/',
	'http://www.genecopoeia.com/about/overview/',
	'http://www.genecopoeia.com/product/chloride-indicators/',
	'http://www.genecopoeia.com/category/product/',
	'http://www.genecopoeia.com/product/biotin-alkyne/',
	'http://www.genecopoeia.com/product/biotin-alkyne/',
	'http://www.genecopoeia.com/product/biotin-alkyne/',
	'http://www.genecopoeia.com/product/biotin-alkyne/',
	'http://www.genecopoeia.com/product/biotin-alkyne/',
	'http://www.genecopoeia.com/product/biotin-alkyne/',
	'http://www.genecopoeia.com/product/biotin-alkyne/',
	'http://www.genecopoeia.com/product/biotin-alkyne/',
	'http://www.genecopoeia.com/product/biotin-azide/',
	'http://www.genecopoeia.com/resource/brochures-and-product-profiles/',
	'http://www.genecopoeia.com/resource/brochures-and-product-profiles/',
	'http://www.genecopoeia.com/resource/brochures-and-product-profiles/',
	'http://www.genecopoeia.com/resource/publication/',
	'http://www.genecopoeia.com/product/ion-indicators/',
	'http://www.genecopoeia.com/product/reactive-dyes-biotin-derivatives/',
	'http://www.genecopoeia.com/product/56-fam-se-5-and-6-carboxyfluorescein-succinimidyl-ester/',
	'http://www.genecopoeia.com/category/product/',
	'http://www.genecopoeia.com/product/biotin-dbco/',
	'http://www.genecopoeia.com/product/biotin-dbco/',
	'http://www.genecopoeia.com/product/biotin-dbco/',
	'http://www.genecopoeia.com/product/biotin-dbco/',
	'http://www.genecopoeia.com/product/biotin-dbco/',
	'http://www.genecopoeia.com/product/biotin-dbco/',
	'http://www.genecopoeia.com/product/biotin-dbco/',
	'http://www.genecopoeia.com/product/crispr-cas9/',
	'http://www.genecopoeia.com/resource/manuals/',
	'http://www.genecopoeia.com/product/reporter-vector-controls/',
	'http://www.genecopoeia.com/product/nucleic-acid-stains/',
	'http://www.genecopoeia.com/product/calcein-blue-am/',
	'http://www.genecopoeia.com/product/cytoskeleton-stains/',
	'http://www.genecopoeia.com/product/membrane-stains/',
	'http://www.genecopoeia.com/product/fitc-annexin-v-and-pi-apoptosis-kit/',
	'http://www.genecopoeia.com/product/andy-fluor-568-x-dutp-lyophilized-powder/',
	'http://www.genecopoeia.com/product/hoechst-33342/',
	'http://www.genecopoeia.com/product/hoechst-33342/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/cy3-dbco/',
	'http://www.genecopoeia.com/product/hoechst-33342/',
	'http://www.genecopoeia.com/product/hoechst-33342/',
	'http://www.genecopoeia.com/product/cy3-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/cy3-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/cy3-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/hoechst-33342/',
	'http://www.genecopoeia.com/product/pluronic-f-127/',
	'http://www.genecopoeia.com/product/cell-quant-alamarblue-cell-viability-reagent/',
	'http://www.genecopoeia.com/product/d-biotin-se/',
	'http://www.genecopoeia.com/product/d-biotin-se/',
	'http://www.genecopoeia.com/product/d-biotin-se/',
	'http://www.genecopoeia.com/product/d-biotin-se/',
	'http://www.genecopoeia.com/product/d-biotin-se/',
	'http://www.genecopoeia.com/product/d-biotin-se/',
	'http://www.genecopoeia.com/product/d-biotin-se/',
	'http://www.genecopoeia.com/product/ion-indicators-2-2/',
	'http://www.genecopoeia.com/publications-by-technology-area/indelcheck-a-powerful-crisprtalen-validation-screening-tool/',
	'http://www.genecopoeia.com/product/caspase-37-inhibitor-ac-devd-cho/',
	'http://www.genecopoeia.com/product/caspase-37-inhibitor-ac-devd-cho/',
	'http://www.genecopoeia.com/product/caspase-37-inhibitor-ac-devd-cho/',
	'http://www.genecopoeia.com/product/fitc-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/hrp-goat-anti-mouse-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/fluo-3-am/',
	'http://www.genecopoeia.com/product/mqae/',
	'http://www.genecopoeia.com/product/biotin-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/mitarget-3%E2%80%B2-utr-mirna-target-clone-publications/',
	'http://www.genecopoeia.com/product/pluronic-f-127/',
	'http://www.genecopoeia.com/tech/publication/mirna-complete-solutions/',
	'http://www.genecopoeia.com/publications-by-technology-area/orfcdna/',
	'http://www.genecopoeia.com/product/ezshuttle-cloning/',
	'http://www.genecopoeia.com/product/ezshuttle-cloning/',
	'http://www.genecopoeia.com/product/andy-fluor-555-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-568-goat-anti-mouse-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-568-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-594-goat-anti-mouse-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-594-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-647-goat-anti-mouse-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-647-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-680-goat-anti-mouse-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-680-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-405-goat-anti-rabbit-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-610-goat-anti-mouse-igg-hl-antibody/',
	'http://www.genecopoeia.com/product/andy-fluor-610-goat-anti-rabbit-igg-hl-antibody/',

);
$post_ids = array();
foreach ($url_need_to_get_post_id as $url) {
	$post_ids[] = url_to_postid( $url );
}


print_r($post_ids);