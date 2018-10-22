<div class="row">
	<div class="col-md-12">
		<h1>General</h1>
		<h2><?php _e("Generate A Json File.",'getwpcategories') ?></h2>
		<p><?php _e("Select the link or directory in the form below, and then enter the file name to generate the json file.",'getwpcategories') ?></p>
	</div>
	<div class="col-md-12">
		<form method="post">
			<table class="form-table">
				<tbody>
					<tr>
						<th>Type</th>
						<td>
							<label for="link"><input type="radio" name="type" id="link" checked="true" value="link">link</label>
							<br>
							<label for="category"><input type="radio" name="type" id="category" value="category">category</label>
						</td>
					</tr>
					<tr>
						<th>File Name</th>
						<td>
							<input type="text" name="filename">

						</td>
					</tr>
					<tr>
						<td colspan="2"><button type="button" class="btn btn-primary" onclick="GetWPCategories.generated()">genreate</button></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
</div>