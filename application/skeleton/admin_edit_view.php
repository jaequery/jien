<form class="form-horizontal trig_form">
	<input type="hidden" name="model" value="{model}">
	<input type="hidden" name="id" value="<?php echo $this->params('id'); ?>">
    <input type="submit">
    
	<div class="row">
		<div class="col-lg-12">
			<div id="title-bar" class="page-header">
				<div class="col-lg-6">
					<h1 class="page-title">Edit {model|ucfirst}</h1>
				</div>
				<div id="header-btns">
					<div class="inner">
                        <button class="btn btn-default trig_go" rel="/admin/{model|plural}"><span class="icon-arrow-left"></span> View List</button>
						<button type="submit" class="btn btn-default trig_save"><span class="icon-save"></span> Save</button>
						<?php if($this->params('id') != ''){ ?>
						<button rel="model=<?php echo $this->model; ?>|id=<?php echo $this->params('id'); ?>" class="btn btn-default trig_delete"><span class="icon-trash"></span> Delete</button>
						<?php } ?>
					</div><!--.inner-->
				</div><!--#header-btns.col-lg-4-->
			</div><!--#title-bar-->
		</div><!--.col-lg-12-->
	</div><!--.row-->

	<?php
	/*
	// enable when tab needed
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-basic" data-toggle="tab">Basic Information</a></li>
        <li><a href="#tab-advanced" data-toggle="tab">Advanced</a></li>
    </ul>
    */
    ?>

    <div class="tab-content">
    	<div class="tab-pane active" id="tab-basic">
    		<div class="row">
    			<div class="col-lg-6">
                    {edit_fields}
    			</div><!--.col.col-lg-6-->
    		</div><!--.row-->
    	</div><!--.tab-pane-->
    </div><!--.tab-content-->
</form>