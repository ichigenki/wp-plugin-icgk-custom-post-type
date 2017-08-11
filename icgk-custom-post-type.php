<?php
/*
Plugin Name: ICGK Custom Post Type
Plugin URI: 
Description: カスタムポストタイプを作成
Version: 1.0.1
Author: ICHIGENKI
Author URI: 
License: 
*/

$page_title = 'ICGK Create/Edit Custom Post Type';
$menu_title = 'ICGK Custom Post Type';


// 管理メニューに追加するフック
add_action('admin_menu', 'icgk_custom_post_type_menu');

// 上のフックに対する action 関数
function icgk_custom_post_type_menu() {
	// 「設定」下に新しいサブメニューを追加
	add_options_page('ICGK Create/Edit Custom Post Type', 'ICGK Custom Post Type', 'manage_options', 'icgk-custom-post-type', 'icgk_custom_post_type_options' );
}

// メニュー項目をクリックした際に表示されるページ、または画面の HTML 出力を作成
// mt_settings_page() は Test Settings サブメニューのページコンテンツを表示
function icgk_custom_post_type_options() {

	// ユーザーが必要な権限を持つか確認する必要がある
	if ( !current_user_can('manage_options') ) {
	  wp_die( __('You do not have sufficient permissions to access this page.') );
	}

	// フィールドとオプション名の変数
	$option_name = 'icgk-custom-post-type';
	$hidden_field_name = 'mt_submit_hidden';
	global $option_data;
	// データベースから既存のオプション値を取得
	if ( get_option( $option_name ) ) {
		$option_data = get_option( $option_name );
	} else {
		$option_data = array();
	}

	// ユーザーが何か情報を POST したかどうかを確認
	// POST していれば、隠しフィールドに 'Y' が設定されている
	if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		// POST されたデータを取得
		$option_data = $_POST[ 'icgk_post_type' ];
		// POST された値をデータベースに保存
		update_option( $option_name, $option_data );
		// 画面に「設定は保存されました」メッセージを表示
		$saved = 'settings saved.'
		//_e($saved, 'menu-test' );
?>
<div class="updated"><p><strong>設定は保存されました</strong></p></div>
<?php
	}

	// ここで設定編集画面を表示
	echo '<div class="wrap">';
	// ヘッダー
	echo "<h2>" . __( 'ICGK Create/Edit Custom Post Type', 'menu-test' ) . "</h2>";
	// 設定用フォーム
?>
<br />
<hr />

<form name="form1" method="post" action="">
<!--<form method="post" action="options.php">-->
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
	<?php settings_fields( 'icgk-custom-post-type' ); ?>

<!--<p><?php _e("Favorite Color:", 'menu-test' ); ?> 
<input type="text" name="icgk_post_type" value="<?php echo $option_data; ?>" size="20">
</p>-->

<?php 
	$pn = 0;
	foreach ( $option_data as $data ) :
		if ( $data['name'] ) :
?>
	<h3>カスタム・ポストタイプ (<?php echo $pn + 1; ?>)</h3>
	<table class="form-table">
		<tr valign="top">
		<th scope="row">名称</th>
		<td><input type="text" name="icgk_post_type[<?php echo $pn; ?>][name]" value="<?php echo $data['name']; ?>" />
		<p>半角英文字＋アンダーバー（削除する場合は空欄）</p></td>
		</tr>
		<tr valign="top">
		<th scope="row">表示名</th>
		<td><input type="text" name="icgk_post_type[<?php echo $pn; ?>][label]" value="<?php echo $data['label']; ?>" />
		<p>メニューに表示される名前（日本語可）</p></td>
		</tr>
		<tr valign="top">
		<th scope="row">スラッグ</th>
		<td><input type="text" name="icgk_post_type[<?php echo $pn; ?>][slug]" value="<?php echo $data['slug']; ?>" />
		<p>半角英文字＋ハイフン（空欄にした場合はポストタイプ名）</p></td>
		</tr>
		<tr valign="top">
		<th scope="row">階層構造</th>
		<td><label for="icgk_post_type_<?php echo $pn; ?>_hier_y"><input type="radio" name="icgk_post_type[<?php echo $pn; ?>][hier]" id="icgk_post_type_<?php echo $pn; ?>_hier_y" value="1"<?php if($data['hier'] == 1) echo ' checked="checked"'; ?> /> あり（固定ページ型）</label>　　<label for="icgk_post_type_<?php echo $pn; ?>_hier_n"><input type="radio" name="icgk_post_type[<?php echo $pn; ?>][hier]" id="icgk_post_type_<?php echo $pn; ?>_hier_n" value="0"<?php if($data['hier'] == 0) echo ' checked="checked"'; ?> /> なし（投稿型）</label></td>
		</tr>
		<tr valign="top">
		<th scope="row">表示位置</th>
		<td><input type="text" name="icgk_post_type[<?php echo $pn; ?>][pos]" value="<?php echo $data['pos']; ?>" />
		<p>5:投稿の下、10:メディアの下、15:リンクの下、20:固定ページの下、25:コメントの下、60:外観の下、65:プラグインの下、70:ユーザーの下、75:ツールの下、80:設定の下、100:最下部に独立させる</p></td>
		</tr>
	</table>
	<hr />
<?php 
			$pn++;
		endif;
	endforeach;
?>

	<div id="new-custom-post">
<?php
	if ( $pn == 0 ) {
		$name_val = 'contentpage';
		$label_val = 'コンテンツページ';
		$slug_val = 'content';
		$pos_val = 5;
		$submit_style = ' style="display:none;"';
	} else {
		$name_val = '';
		$label_val = '';
		$slug_val = '';
		$hier_val = '';
		$pos_val = '';
		$submit_style = '';
	}
	$nn = $pn + 1;
?>
		<div class="cpt-form" style="display:none;">
			<h3>新規カスタム・ポストタイプ (<?php echo $nn; ?>)</h3>
			<table class="form-table">
				<tr valign="top">
				<th scope="row">名称</th>
				<td><input type="text" name="icgk_post_type[<?php echo $nn; ?>][name]" value="<?php echo $name_val; ?>" placeholder="" />
				<p>半角英文字＋アンダーバー</p></td>
				</tr>
				<tr valign="top">
				<th scope="row">表示名</th>
				<td><input type="text" name="icgk_post_type[<?php echo $nn; ?>][label]" value="<?php echo $label_val; ?>" placeholder="" />
				<p>メニューに表示される名前（日本語可）</p></td>
				</tr>
				<tr valign="top">
				<th scope="row">スラッグ</th>
				<td><input type="text" name="icgk_post_type[<?php echo $nn; ?>][slug]" value="<?php echo $slug_val; ?>" placeholder="" />
				<p>半角英文字＋ハイフン（空欄にした場合はポストタイプ名）</p></td>
				</tr>
				<tr valign="top">
				<th scope="row">階層構造</th>
				<td><label for="icgk_post_type_<?php echo $nn; ?>_hier_y"><input type="radio" name="icgk_post_type[<?php echo $nn; ?>][hier]" id="icgk_post_type_<?php echo $nn; ?>_hier_y" value="1" checked="checked" /> あり（固定ページ型）</label>　　<label for="icgk_post_type_<?php echo $nn; ?>_hier_n"><input type="radio" name="icgk_post_type[<?php echo $nn; ?>][hier]" id="icgk_post_type_<?php echo $nn; ?>_hier_n" value="0" /> なし（投稿型）</label></td>
				</tr>
				<tr valign="top">
				<th scope="row">表示位置</th>
				<td><input type="text" name="icgk_post_type[<?php echo $nn; ?>][pos]" value="<?php echo $pos_val; ?>" placeholder="" />
				<p>5:投稿の下、10:メディアの下、15:リンクの下、20:固定ページの下、25:コメントの下、60:外観の下、65:プラグインの下、70:ユーザーの下、75:ツールの下、80:設定の下、100:最下部に独立させる</p></td>
				</tr>
			</table>
			<hr />
		</div>
		<p class="cpt-button"><a href="javascript:showhideCPT();" class="button button-secondary">ポストタイプを追加</a></p>
		<p class="submit"<?php echo $submit_style; ?>><input type="submit" name="submit" id="submit" class="button button-primary" value="変更を保存" /></p>
	</div>

</form>
</div>
<script type="text/javascript">
<!--
	function showhideCPT() {
		jQuery('#new-custom-post .cpt-form').show();
		jQuery('#new-custom-post .cpt-button').hide();
		jQuery('#new-custom-post p.submit').show();
	}
-->
</script>
<?php
}



// カスタム・ポストタイプを作成


function icgk_create_custom_post_type() {
	// データベースから既存のオプション値を取得
	$option_name = 'icgk-custom-post-type';
	if ( get_option( $option_name ) ) {
		$option_data = get_option( $option_name );
	} else {
		$option_data = array();
	}

	$i = 0;
	foreach ( $option_data as $data ) :
		if ( $data['name'] ) :
			$name = $data['name'];
			$label = $data['label'];
			$slug = $data['slug'];
			$hier = $data['hier'];
			$pos = $data['pos'];
			if ( $slug == '' ) $slug = $name;

			$args = array(
				'labels' => array(
					'name' => __( $label ),
					'singular_name' => __( $label )
				),
				'public' => true,
				'rewrite' => array('slug' => $slug),
				'hierarchical' => $hier,
				'menu_position' => $pos,
				'supports' => array(
					'title',
					'editor',
					'thumbnail',
					'custom-fields',
					//'excerpt',
					'author',
					'page-attributes',
					//'trackbacks',
					//'comments',
					//'revisions'
				),
				'exclude_from_search' => true,
			);
			register_post_type( $name, $args);
			$i++;
		endif;
	endforeach;
	flush_rewrite_rules();
}
// カスタムポストタイプ作成を実行
add_action( 'init', 'icgk_create_custom_post_type' );



//require_once( dirname(__FILE__) . '/register-post-type.php' );

