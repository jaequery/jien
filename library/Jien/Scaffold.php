<?php



class Jien_Scaffold {

	protected function renderPlaceholders($model, $content){
		$content = str_replace("{model|lcfirst}", lcfirst($model), $content);
		$content = str_replace("{model|ucfirst}", ucfirst($model), $content);
		$content = str_replace("{model|plural}", lcfirst(Jien_Plural::pluralize($model)), $content);
		$content = str_replace("{model|label|plural}", ucwords(substr(preg_replace_callback ('/([A-Z])/', create_function('$matches','return \' \' . strtolower($matches[1]);'), $model), 1)), $content);
		$content = str_replace("{model|lower}", strtolower($model), $content);
		$content = str_replace("{model|col}", strtolower(substr(preg_replace_callback ('/([A-Z])/', create_function('$matches','return \'_\' . strtolower($matches[1]);'), $model), 1)), $content);
		$content = str_replace("{model|url}", strtolower(substr(preg_replace_callback ('/([A-Z])/', create_function('$matches','return \'-\' . strtolower($matches[1]);'), $model), 1)), $content);
		$content = str_replace("{model}", ucfirst($model), $content);
		return $content;
	}

	public function generateAdminListView($model){
		// create admin list view
		$content = file_get_contents(APPLICATION_PATH . "/skeleton/admin_list_view.php");

		$schema = Jien::model($model)->scheme();
		$table_headers = '';
		$table_rows = '';
		foreach($schema AS $field=>$value){

			$label = ucwords(str_replace('_', ' ', $field));

  			// skip these
  			if($value['PRIMARY'] == 1 || $field == 'created' || $field == 'updated' || $field == 'deleted' || $field == 'accessed' || $field == 'active'){
      			continue;
      		}

  			// table headers
			$table_headers .= "<th class='header' rel='".strtolower($model).".{$value['COLUMN_NAME']}'>{$label}</th>";
			$table_rows .= '<td><?php echo $value[\'' . $value['COLUMN_NAME'] . '\']; ?></td>';

		}

		$content = str_replace("{table_headers}", $table_headers, $content);
		$content = str_replace("{table_rows}", $table_rows, $content);

		$content = $this->renderPlaceholders($model, $content);

		$dest_file = substr(preg_replace_callback ('/([A-Z])/', create_function('$matches','return \'-\' . strtolower($matches[1]);'), Jien_Plural::pluralize($model)), 1);
		$dest_path = APPLICATION_PATH . "/views/".THEME."/admin/{$dest_file}.phtml";
		if(!file_exists($dest_path)){
			$dest_fh = fopen($dest_path, 'w') or die("can't open file");
			fwrite($dest_fh, $content);
			fclose($dest_fh);
			//echo "<p>{$dest_path} created</p>";
		}else{
			//echo "<p>{$dest_path} already exists</p>";
		}
		return true;
	}

	public function generateAdminEditView($model){
		// create admin edit view
		$content = file_get_contents(APPLICATION_PATH . "/skeleton/admin_edit_view.php");
		$content = $this->renderPlaceholders($model, $content);

		$schema = Jien::model($model)->scheme();
		$edit_fields = '';
		foreach($schema AS $field=>$value){

			$label = ucwords(str_replace('_', ' ', $field));

  			// skip these
  			if($value['PRIMARY'] == 1 || $field == 'created' || $field == 'updated' || $field == 'deleted' || $field == 'accessed' || $field == 'active'){
      			continue;
      		}
            $edit_fields .= '
            <div class="form-group">
                <label for="name" class="col-lg-4 control-label">'.$label.'</label>
                <div class="col-lg-8">
                    <input class="form-control" name="'.$field.'" value="<?php echo $this->data->row("'.$field.'"); ?>" type="text">
                </div>
            </div>' . "\r\n";

		}

		$content = str_replace("{edit_fields}", $edit_fields, $content);
		$dest_file = substr(preg_replace_callback ('/([A-Z])/', create_function('$matches','return \'-\' . strtolower($matches[1]);'), $model), 1);
		$dest_path = APPLICATION_PATH . "/views/".THEME."/admin/{$dest_file}.phtml";
		if(!file_exists($dest_path)){
			$dest_fh = fopen($dest_path, 'w') or die("can't open file");
			fwrite($dest_fh, $content);
			fclose($dest_fh);
			//echo "<p>{$dest_path} created</p>";
		}else{
			//echo "<p>{$dest_path} already exists</p>";
		}
		return true;
	}

	public function generateModel($model){
		// create model
		$content = file_get_contents(APPLICATION_PATH . "/skeleton/model.php");
		$content = $this->renderPlaceholders($model, $content);
		$dest_path = APPLICATION_PATH . "/models/DbTable/{$model}.php";
		if(!file_exists($dest_path)){
			$dest_fh = fopen($dest_path, 'w') or die("can't open file");
			fwrite($dest_fh, $content);
			fclose($dest_fh);
			//echo "<p>{$dest_path} created</p>";
		}else{
			//echo "<p>{$dest_path} already exists</p>";
		}
		return true;
	}

	public function appendAdminActions($model){
		// append admin action
		$admin_content = file_get_contents(APPLICATION_PATH . "/controllers/AdminController.php");

		if(!strstr($admin_content, lcfirst($model).'Action')){

			$content = file_get_contents(APPLICATION_PATH . "/skeleton/admin_controller_actions.php");
			$content = $this->renderPlaceholders($model, $content);
			$content = str_replace("// skeleton - dont remove this line, it's for scaffolding reason //", $content, $admin_content);
			$dest_path = APPLICATION_PATH . "/controllers/AdminController.php";
			$dest_fh = fopen($dest_path, 'w') or die("can't open file");

			fwrite($dest_fh, $content);
			fclose($dest_fh);
			//echo "<p>{$dest_path} actions appended</p>";
		}else{
			//echo "<p>AdminController already has {$model} actions</p>";
		}

		return true;
	}

    public function removeAdminActions($model){
        // append admin action
        $admin_content = file_get_contents(APPLICATION_PATH . "/controllers/AdminController.php");

        $scaffold_regex = "|(//scaffolder $model start)(.*)(//scaffolder $model end\n)|s";

        if( preg_match($scaffold_regex, $admin_content) ){
            $content = preg_replace($scaffold_regex, '', $admin_content);

            $dest_path = APPLICATION_PATH . "/controllers/AdminController.php";
            $dest_fh = fopen($dest_path, 'w') or die("can't open file");

            fwrite($dest_fh, $content);
            fclose($dest_fh);

            $res = true;
        }else{
            $res = false;
        }

        return $res;
    }

	public function generateFromTable($model){
		try {
			$this->generateModel($model);
			$this->appendAdminActions($model);
			$this->generateAdminListView($model);
			$this->generateAdminEditView($model);

            $row = Jien::model("Datatype")->orderBy("rank DESC")->limit(1)->get()->row();
            $res = Jien::model("Datatype")->save(array(
               "datatype" => $model,
               "rank" => $row['rank'] + 1,
            ));
            return $res;
		}catch(Exception $e){
            error_log($e->getMessage());
            return $e->getMessage();
		}
	}

    public function createTable($tbl_column_info){
        $tbl_name = $tbl_column_info['tbl_name'];

        //ensure table name doesn't start with number and table name doesn't have special characters
        if( $this->tableNameValidation($tbl_name) ){
            $check_table_q = "SHOW TABLES LIKE '$tbl_name'";
            $check_table = Jien::db()->query($check_table_q)->rowCount();
            if( $check_table > 0 ){
                return 'table exists';
            }else{
                $sql = $this->createTableSQL($tbl_column_info);

                try{
                    $res = Jien::db()->query($sql);
                }catch(Exception $e){
                    //echo $e->getMessage();
                    return $e->getMessage();
                };

                return true;
            }
        }else{
            return 'table name must start with a character';
        }
    }

    public function tableNameValidation($table_name){
        //check if table name starts with a character from a-z
        $number_start = '/^[a-zA-Z]/';

        return preg_match($number_start,$table_name);
    }

    public function createTableSQL($tbl_column_info){
        
        $grouped_array = array();
        $tbl_name = $tbl_column_info['tbl_name'];

        foreach($tbl_column_info['column_name'] as $key=>$name){
            $grouped_array[$name] = array(
                'column_type' => $tbl_column_info['column_type'][$key],
                'length' => $tbl_column_info['length'][$key],
                'default' => $tbl_column_info['default'][$key]
            );
        }

        $primary_col = strtolower('`'.$tbl_name) . '_id` INT AUTO_INCREMENT';

        $sql = "CREATE TABLE `$tbl_name` ($primary_col,";
        foreach($grouped_array as $col=>$attrs){
            $sql .= '`'.$col.'` ' . $attrs['column_type'];

            if( !empty($attrs['length']) ){
                $sql .= '(' . $attrs['length'] . ')';
            }

            if( $attrs['default'] == "NOT NULL" || $attrs['default'] == "NULL"  ){
                $sql .= ' ' . $attrs['default'] . ',';
            }else{
                $sql .= " default '{$attrs['default']}',";
            }
        }

        $sql .= 'created datetime NOT NULL,';
        $sql .= 'updated timestamp ON UPDATE CURRENT_TIMESTAMP,';
        $sql .= 'deleted datetime,';
        $sql .= 'active tinyint(1) default 1 NOT NULL,';
        $sql .= 'PRIMARY KEY(`' . strtolower($tbl_name) . '_id`)';
        $sql .= ")";

        //echo $sql;

        return $sql;
    }

    public function delete($model){
        $this->removeAdminActions($model);
        Jien::db()->query("DROP Table $model");
        Jien::cache()->clean(Zend_Cache::CLEANING_MODE_ALL);
        unlink(getcwd() . '/../application/models/DbTable/' . $model . '.php');
        unlink(getcwd() . '/../application/views/default/admin/' . strtolower($model).'.phtml');
        unlink(getcwd() . '/../application/views/default/admin/' . strtolower(Jien_Plural::pluralize($model)) .'.phtml' );
    }
}