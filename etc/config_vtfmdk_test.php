<?php
//=================================================================
//
//   [Toppa]代理店向け申込フォームシステム オーソリ設定
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   新規作成　　　　　　　　　　　 2011/01/26 Rev 1.0.0 koba
//  --------------------------------------------------------------
//
//=================================================================

# マーチャントID
// テスト環境
if (CNF_SV_TYPE == 0){
	$MERCHANT_ID = "test-bsf";
	$PASS_PHRASE = "38f3asxuaj";
	$PAYTO_URL = "http://toppa.n-plan-ing.com/?no=".$f->no;				//	支払ページ (payment.php) URL
	$IMAGES_BASE = "http://toppa.n-plan-ing.com/img";		//	画像ファイルベースURL
}
// 本番環境
elseif (CNF_SV_TYPE == 1){
//	$MERCHANT_ID = "XP3753301002285";
//	$PASS_PHRASE = "kGbpDyvZw2";
	$MERCHANT_ID = "test-bsf";
	$PASS_PHRASE = "38f3asxuaj";
	$PAYTO_URL = "http://www.entry-t.tp1.jp/?no=".$f->no;;
	$IMAGES_BASE = "http://www.entry-t.tp1.jp/img";
}

# 取引タイプ
# お申込み頂いたタイプ（authonly|authcapture）を設定してください。
$TXN_TYPE = "authonly";

# 設定ファイル (jpgwlib.conf) パス
// テスト環境
if (CNF_SV_TYPE == 0){
	$CONFIG_PATH = CNF_LIB_PATH."vtfmdk/mdk/conf/jpgwlib_mirror.conf";
}
// 本番環境
elseif (CNF_SV_TYPE == 1){
	$CONFIG_PATH = CNF_LIB_PATH."vtfmdk/mdk/conf/jpgwlib.conf";
}

# エンコード
# 注意："EUC-JP", "SHIFT_JIS", "UTF-8"を指定可能
#       デフォルトは "EUC-JP" に設定されていますがそれ以外に変更する場合は
#       その他すべてのファイルエンコードを変更すること
$ENCODE = "UTF-8";

# ライブラリのパス
$LIBRARY_PATH = CNF_LIB_PATH."vtfmdk/mdk/lib/JPGWLib";

?>