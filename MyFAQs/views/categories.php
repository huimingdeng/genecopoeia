<div class="wrap">
    <?php include 'header.php';?>
    <h2><?php echo $title;?></h2>
    <content>
        <div class="row">
            <div class="col-md-4">
                <h3>Add New FAQ Category</h3>
                <form id="AddNewC">
                    <div class="form-group">
                        <label for="name"><?php _e('Name','myfaqs');?></label>
                        <input type="text" id="name" class="form-control" name="name" >
                    </div>
                    <div class="form-group">
                        <label for="slug"><?php _e('Slug','myfaqs');?></label>
                        <input type="text" id="slug" class="form-control" name="slug">
                    </div>
                    <div class="form-group">
                        <label for="parent"><?php _e('Parent FAQ Category', 'myfaqs');?></label>
                        <br>
                        <select name="parent" class="form-control" id="parent">
                            <option value="0"><?php _e('Non', 'myfaqs');?></option>
                            <?php if(!empty($data)){
                                foreach ($data as $categories) {
                                    echo "<option value='".$categories['id']."'>".$categories['slug']."</option>\n";
                                }
                            }?>
                        </select>
                    </div>
                    <div class="form-group">
                        <a class="btn btn-default" href="javascript:void(0);" onclick="Category.add();"><?php _e('sublimt','myfaqs');?></a>
                    </div>
                </form>
            </div>
            <div class="col-md-8 pull-right">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th><?php _e('Name', 'myfaqs');?></th>
                        <th><?php _e('Slug', 'myfaqs');?></th>
                        <th><?php _e('Sum', 'myfaqs');?></th>
                        <th><?php _e('Parent', 'myfaqs');?></th>
                        <th><?php _e('EditDate', 'myfaqs');?></th>
                        <th><?php _e('Action','myfaqs');?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php  if(!empty($data)){
                        foreach ($data as $categories){?>
                            <tr>
                                <td><?php echo $categories['name'];?></td>
                                <td><?php echo $categories['slug'];?></td>
                                <td><?php echo $categories['sumfaq'];?></td>
                                <td><?php echo $categories['parent'];?></td>
                                <td><?php echo $categories['editdate'];?></td>
                                <td><a href="javascript:void(0);" class="btn btn-primary btn-sm" onclick="Category.edit(<?php echo $categories['id'];?>)"><?php _e('Edit', 'myfaqs');?></a>&nbsp;<a
                                            href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="Category.delete(<?php echo $categories['id'];?>)"><?php _e('Delete', 'myfaqs');?></a></td>
                            </tr>
                    <?php  }
                        }else{ ?>
                        <tr>
                            <td colspan="5" align="center"><?php _e('No data', 'myfaqs');?>....</td>
                        </tr>
                    <?php  } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </content>
    <aside></aside>
</div>
