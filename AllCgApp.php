<?php
require_once "common.php";
require_once "Plugin.php"; 
$appName = $_POST['app'];
$categoryName = $_POST['cat'];
$plglistHandle = fopen("conf/${categoryName}_${appName}.list", "r");
$hostlistHandle = fopen("conf/hosts_${categoryName}.list", "r");

$category = "ワークステーション";
if ( $categoryName === "ren" ) {
	$category = "レンダーサーバー";
}

$application = "CGアプリケーション";
//if ( $appName === "max" ){
//	$application = "3D Studio Max";
//} else if ( $appName === "si" ) {
//	$application = "Softimage";
//}

// ホスト情報一覧を作成
$hostslist = new HostsList( $hostlistHandle );

// プラグイン情報一覧集合を作成
$plugin_list_cluster = new PluginListCluster( "data", $plglistHandle );
$plugin_list_cluster->SetToHostList( $hostslist );
// 見出しの作成
$ctitle = new TableRow();
$ctitle->setRowTypeHeader();
$ctitle->setHostAllPluginColum( $plugin_list_cluster->getPluginListArray(), NULL );

// ホストリストの順にホスト毎に行(row)を作成
$crows_array = array();
foreach( $hostslist->getHostnameList() as $hname ){
		$row = new TableRow();
		$row->setRowTypeItem();
		$row->setHostAllPluginColum( $plugin_list_cluster->getPluginListArray(), $hostslist->getHostInfo( $hname ) );
		array_push( $crows_array, $row );
}

$rows = array();
$styles = array();
foreach ( $crows_array as $row ){
	if ( $row->getShowFlag() == TRUE ){
		$line = array();
		foreach ( $row->getRowArray() as $cell ){
			array_push( $line, $cell );
		}

		array_push( $rows, $line );
		$line = array();
		foreach ( $row->getStyleArray() as $cell ){
			array_push( $line, $cell );
		}
		array_push( $styles, $line ); 
	}
}

// Category Name
$smarty->assign( 'category', $category );
$smarty->assign( 'cat', $categoryName );
// Plug-in Name
$smarty->assign( 'AppName', $application );
$smarty->assign( 'app', $appName );

// 項目 プラグイン名 表示用
$smarty->assign( 'ctitle', $ctitle->getRowArray());
// 項目 プラグイン スタイル
$smarty->assign( 'cstyle', $ctitle->getStyleArray());
// 項目 プラグイン名 画面操作用
$smarty->assign( 'ctype', $ctitle->getPluginNameArray() );
// item 
$smarty->assign( 'rows', $rows );
// stylesheet
$smarty->assign( 'stylelist', $styles );
//テンプレートを表示する
$smarty->display("AllCgApp.tpl");

//print "</body>\n";
//print "</html>\n";



fclose($plglistHandle);
fclose($hostlistHandle);


?>

