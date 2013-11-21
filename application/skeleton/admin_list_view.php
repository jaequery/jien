<div id="title-bar" class="page-header">
	<div class="col-lg-6">
		<h1 class="page-title">Listing {model|label|plural}</h1>
	</div>
	<div id="header-btns">
		<div class="inner">
			<a href="/admin/{model|url}" class="btn btn-default"><span class="icon-plus"></span> Add New {model|ucfirst}</a>
		</div><!--.inner-->
	</div><!--#header-btns.col-lg-4-->
</div>

<div class="row-fluid">
	<div class="span12">

		<div class="boot-datatable">

			<table class="table table-bordered table-striped datatable">
				<thead>
					<tr>
						<th width="10"><input type="checkbox" class="trig_bulk_check" name="title-checkbox" /></th>
						<th class="header" rel="{model|lower}.{model|col}_id">#</th>
						{table_headers}
			            <th class="header" rel="{model|lower}.created">Created</th>
			            <th width="200"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($this->data->rows() AS $key=>$value){ ?>
					<tr>
						<td><input type="checkbox" data-id="<?php echo $value[$this->primary]; ?>" class="bulk_check"/></td>
						<td><?php echo $value[$this->primary]; ?></td>
						{table_rows}
						<td><?php echo date("m/d/y", strtotime($value['created'])); ?></td>
						<td>
							<a href="/admin/{model|url}/id/<?php echo $value[$this->primary]; ?>" class="btn btn-default"><span class="icon-eye-open"> View</span></a>
							<a href="/admin/delete/?model={model}&id=<?php echo $value[$this->primary];; ?>" rel="model={model}|id=<?php echo $value[$this->primary]; ?>" class="btn btn-default trig_delete"><i class="icon-trash icon-white"></i> Delete</a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="row">
    <div class="col-md-4">
        <form class="form-inline trig_bulk_submit">
            <input type="hidden" id="model" value="{model}">
            <div class="form-group">
                <select class="form-control bulk_type">
                    <option value=""></option>
                    <option value="delete">Delete</option>
                </select>
            </div>
            <div class="form-group">
                <button class="form-control" disabled type="submit">Submit</button>
            </div>
        </form>
    </div>
</div>
<?php echo $this->pager($this->data->pager(), 'partials/pager/pager.phtml'); ?>