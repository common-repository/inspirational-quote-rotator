<?php 
if(is_admin()){
	require_once dirname(__FILE__).'/iqr-admin-table.php';
}

class IQR_Main{
	protected static $__instance=null;
	protected $_quote=null;
	
	protected function __construct(){
		register_activation_hook(IQR_ROOT,array($this,'__install'));
		add_action('admin_init',array($this,'adminInit'));
		add_action('admin_menu',array($this,'addMenu'));
		add_action('wp_enqueue_scripts',array($this,'loadScripts'));
		add_action('wp_footer',array($this,'addPopup'));
	}
	
	public function __install(){
		global $wpdb;
		add_option('iqr_plugin_activated','inspirational-quote-rotator');
		$table=$wpdb->prefix.'iqr_quotes';
		$sql='CREATE TABLE IF NOT EXISTS `'.$table.'`(';
		$sql.='`id` INT NOT NULL AUTO_INCREMENT,';
		$sql.='`title` VARCHAR(256) NOT NULL,';
		$sql.='`contents` TEXT NOT NULL DEFAULT "",';
		$sql.='PRIMARY KEY(`id`)';
		$sql.=');';
		require_once ABSPATH.'wp-admin/includes/upgrade.php';
		dbDelta($sql);
	}
	
	public static function init(){
		if(self::$__instance===null){
			self::$__instance=new self();
		}
		return self::$__instance;
	}
	
	public function adminInit(){
		register_setting('iqr-settings','_inspirational_quotes');
		if(is_admin()&&get_option('iqr_plugin_activated')=='inspirational-quote-rotator'){
			
		}
	}
	
	public function addMenu(){
		add_options_page(__('Inspirational Quote Rotator'),__('Inspirational Quote Rotator'),'manage_options','iqr-settings',array($this,'optionsPage'));
	}
	
	public function optionsPage(){
		if(!current_user_can('manage_options')){
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		global $wpdb;
		if(isset($_GET['action'])&&trim($_GET['action'])=='edit'){
			$current_title='';
			$current_contents='';
			if(isset($_POST['_iqr_title'])&&isset($_POST['_iqr_contents'])){
				if(isset($_GET['item_id'])&&intval($_GET['item_id'])>0){
					$sql=$wpdb->prepare('UPDATE `'.$wpdb->prefix.'iqr_quotes` SET `title`= %s, `contents` = %s  WHERE `id`= %d',trim($_POST['_iqr_title']),trim($_POST['_iqr_contents']),intval($_GET['item_id']));
				}else{
					$sql=$wpdb->prepare('INSERT INTO `'.$wpdb->prefix.'iqr_quotes`(`title`,`contents`) VALUES(%s, %s);',trim($_POST['_iqr_title']),trim($_POST['_iqr_contents']));
				}
				$wpdb->query($sql);
				$current_title=trim($_POST['_iqr_title']);
				$current_contents=trim($_POST['_iqr_contents']);
				unset($_POST);
				unset($_GET);
				header('Location: '.admin_url('options-general.php?page=iqr-settings'));
			}else{
				if(isset($_GET['item_id'])&&intval($_GET['item_id'])>0){
					$sql=$wpdb->prepare('SELECT * FROM `'.$wpdb->prefix.'iqr_quotes` WHERE `id`=%d',intval($_GET['item_id']));
					$result=$wpdb->get_results($sql);
					$current_title=$result[0]->title;
					$current_contents=$result[0]->contents;
				}
			}
		}
		if(isset($_GET['action'])&&trim($_GET['action'])=='delete'){
			if(isset($_GET['item_id'])&&intval($_GET['item_id'])>0){
				$sql=$wpdb->prepare('DELETE FROM `'.$wpdb->prefix.'iqr_quotes` WHERE `id`=%d',intval($_GET['item_id']));
				$wpdb->query($sql);
				unset($_POST);
				unset($_GET);
				header('Location: '.admin_url('options-general.php?page=iqr-settings'));
			}
		}
		ob_start();
		$table=new IQR_Admin_Table();
		$table->prepare_items();
		require dirname(dirname(__FILE__)).'/tpl/admin.php';
		$html=ob_get_contents();
		ob_clean();
		echo $html;
	}
	
	public function loadScripts(){
		if(is_home()||is_front_page()){
			$this->_quote=$this->_getRandomQuote();
			if($this->_quote!==null){
				wp_enqueue_script('jquery');
				wp_enqueue_script('thickbox',null,array('jquery'));
				wp_enqueue_script('inspirational-quote-rotator',plugins_url('js/inspirational-quote-rotator.js',dirname(__FILE__)),array('thickbox','jquery'));
			}
		}
		if(is_admin()){
			wp_enqueue_script('jquery');
			wp_enqueue_script('inspirational-quote-rotator-admin',plugins_url('js/inspirational-quote-rotator-admin.js',dirname(__FILE__)),array('jquery'));
		}
	}
	
	public function addPopup(){
		$this->_quote=$this->_getRandomQuote();
		if($this->_quote!==null){
			ob_start();
			require dirname(dirname(__FILE__)).'/tpl/popup.php';
			$html=ob_get_contents();
			ob_clean();
			echo $html;
		}
	}
	
	protected function _getRandomQuote(){
		global $wpdb;
		$quotes=$wpdb->get_results($wpdb->prepare('SELECT * FROM `'.$wpdb->prefix.'iqr_quotes` ORDER BY RAND() LIMIT %d',1));
		if(isset($quotes[0])){
			return $quotes[0];
		}
		return null;
	}
	
}