<div class="wrap">
    <ul class="nav nav-tabs">
        <?php if(!empty($tabs)){
            foreach ($tabs as $tabname => $tabinfo){
                $actived = ($tabname==='categories')?'active':'';
                $uri = ($tabname!=='categories')?'options-general.php?page='.$tabname:'';
                ?>
            <li role="presentating" class="<?php echo $actived;?>"><a href="<?php echo $uri;?>" title="<?php echo $tabinfo['title'];?>"><i class="<?php echo $tabinfo['icon'];?>"></i>&nbsp;&nbsp;<?php echo $tabinfo['name'];?></a></li>
        <?php }
        }?>
    </ul>
    <h1><?php echo $pluginname;?></h1>
</div>