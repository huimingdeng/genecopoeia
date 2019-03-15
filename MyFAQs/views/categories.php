<div class="wrap">
    <?php include 'header.php';?>
    <h2><?php echo $title;?></h2>
    <content>
        <div class="row">
            <div class="col-md-4">
                <h3>Add New FAQ Category</h3>
                <form id="AddNewC">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" class="form-control" name="name" >
                    </div>
                    <div class="form-group">
                        <label for="slug">Slug</label>
                        <input type="text" id="slug" class="form-control" name="slug">
                    </div>
                    <div class="form-group">
                        <label for="parent">Parent FAQ Category</label>
                        <br>
                        <select name="parent" id="parent">
                            <option value="0">Non</option>
                            <?php if(!empty($data)){
                                foreach ($data as $categories) {
                                    echo "<option value='".$categories['id']."'>".$categories['slug']."</option>\n";
                                }
                            }?>
                        </select>
                    </div>
                    <div class="form-group">
                        <a class="btn btn-default" href="javascript:void(0);" onclick="Category.add();">sublimt</a>
                    </div>
                </form>
            </div>
            <div class="col-md-8 pull-right">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Sum</th>
                        <th>Parent</th>
                        <th>EditDate</th>
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
                            </tr>
                    <?php  }
                        }else{ ?>
                        <tr>
                            <td colspan="5" align="center">No data....</td>
                        </tr>
                    <?php  } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </content>
</div>
