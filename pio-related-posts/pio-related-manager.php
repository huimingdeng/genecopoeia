<!--== 插件结构=================================================
    pio-related-product.php：主文件，注册菜单，请求PIO，显示浮动窗
    pio-related-manager.php：前端，对Item的CURD
    pio-related-ajax.php：后台，处理ajax请求
    ============================================================-->
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__));?>/js/jquery-1.12.3.min.js">
</script>
<script type="text/javascript" language="javascript" src="<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__));?>/js/layer.js">
</script>

<h3>The configuration options for the related products</h3>
<table>
    <tr>
        <td>
            <label for="pio-per-num">PIO output related products quantity at a time</label>
        </td>
        <td>
            <input type="text" id="pio-per-num" style="width: 100px;text-align:center;" />
        </td>
        <td>
            <input type="button" value="Save" onclick="updatePerNum()" />
        </td>
    </tr>
    <tr>
        <td>
            <label for="pio-display-num">A total number of related products to show</label>
        </td>
        <td>
            <input type="text" id="pio-display-num" style="width: 100px;text-align:center;" />
        </td>
        <td>
            <input type="button" value="Save" onclick="updateDisplayNum()" />
        </td>
    </tr>    
</table>

<p style="color: red">*&nbsp;If the actual show conditions, the number of related products is less than the number of configuration will be the actual quantity shall prevail.</p>

<script type="text/javascript">

    jQuery(document).ready(function() {
        $.ajax({
            type: "GET",
            dataType: "html",
            url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/pio-related-ajax.php';?>",
            data: "action=select",
            success: function(d){//不存在重复
                var obj = JSON.parse(d);
                $('#pio-per-num').val(obj.per_num);
                $('#pio-display-num').val(obj.display_num);
            }
        });
    });

    function updatePerNum(){//pio每次输出的数量
        per_num = ""+$('#pio-per-num').val();
        $.ajax({
            type: "POST",
            dataType: "html",
            url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/pio-related-ajax.php';?>",
            data: "action=updatePerNum&per_num="+per_num,
            success: function(d){//不存在重复
                var obj = JSON.parse(d);
                if(obj.msg!==false){
                    layer.msg('Successful!', {
                        icon: 1,
                        time: 1000
                    });
                } else {
                    layer.msg('Failed!', {
                        icon: 2,
                        time: 1500
                    });
                }
            }
        });
    }

    function updateDisplayNum(){//浮动窗中显示的数量
        display_num = ""+$('#pio-display-num').val();
        $.ajax({
            type: "POST",
            dataType: "html",
            url: "<?php echo WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)).'/pio-related-ajax.php';?>",
            data: "action=updateDisplayNum&display_num="+display_num,
            success: function(d){//不存在重复
                var obj = JSON.parse(d);
                if(obj.msg!==false){
                    layer.msg('Successful!', {
                        icon: 1,
                        time: 1000
                    });
                } else {
                    layer.msg('Failed!', {
                        icon: 2,
                        time: 1500
                    });
                }
            }
        });
    }

</script>