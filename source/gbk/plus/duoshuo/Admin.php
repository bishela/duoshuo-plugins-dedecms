<?php 

class Duoshuo_Admin{
	function manageComments(){
		$ajax_comment_file = DEDETEMPLATE.'/default/ajaxfeedback.htm';

		$params = array(
				'template'		=>	'dedecms',
				//'remote_auth'	=>	Duoshuo_Abstract::remoteAuth(),
		);
		
		require DEDEROOT.'/plus/duoshuo/templets/manage_comments.htm';
		
	}
	
	function localConfig(){
		require DEDEROOT.'/plus/duoshuo/templets/local_config.htm';
	}
	
	function saveLocalConfig(){
		$keys = array('short_name','secret','seo_enabled');
		$duoshuoPlugin = Duoshuo_Dedecms::getInstance();
		
		foreach ($keys as $key){
			if(isset($_POST[$key])){
				 $duoshuoPlugin->updateOption($key,$_POST[$key]);
			}
		}
		
		Showmsg('����ɹ���',-1,0,1000);
	}
	
	
	function helpDocument(){
		require DEDEROOT.'/plus/duoshuo/templets/help_document.htm';
	}
	
	function replaceCommentTag(){
		$ajax_comment_file = DEDETEMPLATE.'/default/ajaxfeedback.htm';
		$ajax_comment_file_bak = DEDETEMPLATE.'/default/ajaxfeedback.bak.htm';
		if(file_exists($ajax_comment_file)){
			if(!file_exists($ajax_comment_file_bak)){
				$ret = copy($ajax_comment_file, $ajax_comment_file_bak);
				if(!$ret){
					Showmsg('����'.$ajax_comment_file.'ʧ�ܣ������Ŀ¼Ȩ�ޣ����ֶ����Ƶ�'.$ajax_comment_file_bak,-1,0,2000);
				}
			}
			file_put_contents($ajax_comment_file, Duoshuo_Dedecms::$commentTag);
			Showmsg('���ݳɹ���','duoshuo.php?action=localConfig',0,1000);
		}else{
			Showmsg(DEDETEMPLATE.'/default/ajaxfeedback.bak.htm'.'�����ڣ������в����ǩ',-1,0,2000);
		}
	}
}
