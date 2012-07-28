<?php if(!defined('DEDEINC')) exit('Request Error!');
require_once(DEDEROOT.'/plus/duoshuo.php');
require_once(DEDEROOT.'/include/taglib/feedback.lib.php');
function lib_duoshuo(&$ctag,&$refObj)
{
	global $dsql,$envs,$cfg_phpurl,$cfg_basehost,$cfg_multi_site;
	
	$plugin = Duoshuo_Dedecms::getInstance();
	
	if ($plugin->getOption('short_name') == '' || $plugin->getOption('secret') == '') 
		return '�ڹ�����̨����һ�����ã��Ϳ��Կ�ʼʹ�ö�˵��';
	
	$attlist='type|0';
	FillAttsDefault($ctag->CAttribute->Items,$attlist);
	extract($ctag->CAttribute->Items, EXTR_SKIP);
	
	if(empty($refObj->Fields['aid'])){
		return '';
	}
	$arcid = $refObj->Fields['aid'];
	
	$attrs = array();
	$attrs[] = ' data-source-thread-id="'.$arcid.'"';
	if(!empty($refObj->Fields['title'])){
		$attrs[] = 'data-title="'.htmlspecialchars($refObj->Fields['title'],ENT_QUOTES).'"';
	}
	ob_start();
	require (DEDEROOT.'/plus/duoshuo/templets/comments.htm');
	
	if($plugin->getOption('seo_enabled') && !empty($arcid)){
		// ÿƪ�����������
		$infolen = 200;
		// ÿƪ����seo��ʾ���������
		$totalrow = 100;
		
		$innertext  = file_get_contents(DEDEROOT.'/plus/duoshuo/templets/comments_seo.htm');

?>
<div id="ds-ssr" class="mt1">
	<dl class="tbox">
		<dt> <strong>�����б����������۽������ѱ�����˿���������������վͬ����۵��֤ʵ��������</strong> </dt>
		<dd>
			<div class="dede_comment">
				<div class="decmt-box1">
					<ul>
						<li id="commetcontentNew"></li>

<?php 		
		$wsql = " WHERE ischeck=1 AND aid = $arcid";
		$equery = "SELECT * FROM `#@__feedback` $wsql ORDER BY id DESC LIMIT 0 , $totalrow";
		$ctp = new DedeTagParse();
		$ctp->SetNameSpace('field','[',']');
		$ctp->LoadSource($innertext);
		
		$dsql->Execute('fb',$equery);
		while($arr=$dsql->GetArray('fb'))
		{
			$arr['msg'] = jsTrim(Html2Text($arr['msg']),$infolen);
			$arr['dtime'] = GetDateTimeMK($arr['dtime']);
			$arr['username'] = $str = str_replace('&lt;br/&gt;',' ',$arr['username']);
			foreach($ctp->CTags as $tagid=>$ctag)
			{
				if(!empty($arr[$ctag->GetName()]))
				{
					$ctp->Assign($tagid,$arr[$ctag->GetName()]);
				}
			}
			echo $ctp->GetResult();
		}
?> 
					</ul>
				</div>
			</div>
		</dd>
	</dl>
</div>

<?php 
	}

	return ob_get_clean();
}