<?php 

if(!class_exists('WP_List_Table')){
	require_once ABSPATH.'wp-admin/includes/class-wp-list-table.php';
}

class IQR_Admin_Table extends WP_List_Table{
	
	public function __construct(){
		parent::__construct(array(
			'singular'=>'wp_list_text_link',
			'plural'=>'wp_list_text_links',
			'ajax'=>false
		));
	}
	
	public function extra_tablenav($which){
		if($which=='top'){
			echo '<h3 class="title">'.__('Quotes List').' <a href="'.admin_url('options-general.php?page=iqr-settings&action=edit').'" class="add-new-h2">'.__('Add New').'</a></h3>';
		}
	}
	
	public function get_columns(){
		$columns=array(
			'title'=>__('Title'),
			'contents'=>__('Contents')
		);
		return $columns;
	}
	
	public function get_sortable_columns(){
		$sortable=array(
			'title'=>array('title',false)
		);
		return $sortable;
	}
	
	public function prepare_items(){
		global $wpdb,$_wp_column_headers;
		$sql='SELECT * FROM `'.$wpdb->prefix.'iqr_quotes`';
		$orderby=!empty($_GET['orderby'])?$_GET['orderby']:'id';
		$order=!empty($_GET['order'])?$_GET['order']:'ASC';
		$sql.=' ORDER BY %s %s';
		$totalitems=$wpdb->query($wpdb->prepare($sql,$orderby,$order));
		$perpage=10;
		$paged=!empty($_GET['paged'])?$_GET['paged']:'';
		if(empty($paged)||!is_numeric($paged)||$paged<=0){
			$paged=1;
		}
		$totalpages=ceil($totalitems/$perpage);
		$offset=(($paged-1)*$perpage);
		$sql.=' LIMIT %d, %d';
		$this->set_pagination_args(array(
			'total_items'=>$totalitems,
			'total_pages'=>$totalpages,
			'per_page'=>$perpage
		));
		$columns=$this->get_columns();
		$this->_column_headers=array($columns,array(),$this->get_sortable_columns());
		$this->items=$wpdb->get_results($wpdb->prepare($sql,$orderby,$order,intval($offset),intval($perpage)));
	}
	
	public function column_title($item){
		$actions=array(
			'edit'=>sprintf('<a href="?page=%s&action=%s&item_id=%s">Edit</a>',$_REQUEST['page'],'edit',$item->id),
			'delete'=>sprintf('<a href="?page=%s&action=%s&item_id=%s">Delete</a>',$_REQUEST['page'],'delete',$item->id)
		);
		return sprintf('%1$s %2$s',$item->title,$this->row_actions($actions));
	}
	
	public function column_default($item,$column_name){
		switch($column_name){
			case 'title':
				return $item->{$column_name};
			case 'contents':
				return htmlspecialchars_decode(stripslashes($item->{$column_name}),ENT_NOQUOTES);
			default:
				return print_r($item,true);
		}
	}
	
}