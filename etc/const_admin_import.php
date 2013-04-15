<?php
//=================================================================
//
//   [Toppa]代理店向け申込フォームシステム データ取込レイアウト管理
//
//  ------変更内容----------------------DATE -----Rev----Auther---
//   新規作成　　　　　　　　　　　 2011/01/28 Rev 1.0.0 koba
//  --------------------------------------------------------------
//
//=================================================================

/* 取込レイアウト
----------------------------------------------------------------*/
$admin_import_layout = array(
	'mst_agency[name]',	// 代理店名称
	'mst_agency[code]',	// 代理店コード
	'mst_agency[code_2]', // 代理店コード2
	'mst_agency[code_3]', // 代理店コード3
	'mst_manage[hcsf_code]', // HCSF_代理店ID	// Add by.koba@NP 2011-02-23
	'mst_agency[owner_code]',	// オーナーコード
	'mst_manage[name]',	// キャンペーン名称
	'mst_manage[code]',	// キャンペーンコード
	'mst_manage[plan_name]',	// サービスプラン名称
	'mst_manage[plan_code]',	// サービスプランコード
	'mst_manage[hnetsf_flg]', // H-netSFフラグ	// Add by.koba@NP 2011-02-23
	'mst_manage[sales_code]',	// 営業パターンコード
	'mst_manage[delivery_code]',	// 引渡コード
	'mst_manage[payment_code]',	// 決済方法コード
	'mst_manage[agency_open_code]',	// 代理店名公開コード
	'mst_manage[mail_send_code]', // サンクスメール送信コード
	'mst_agency[ts_url]',	// 特定商取引約款URL
	'mst_agency[site_url]',	// サイト運営会社URL
	'mst_agency[privacy_url]',	// 個人情報取り扱いURL
	'mst_agency[ts_url_m]',	// [携帯]特定商取引約款URL
	'mst_agency[site_url_m]',	// [携帯]サイト運営会社URL
	'mst_agency[privacy_url_m]',	// [携帯]個人情報取り扱いURL
	'mst_agency[ts_url_s]',	// [スマフォ]特定商取引約款URL
	'mst_agency[site_url_s]',	// [スマフォ]サイト運営会社URL
	'mst_agency[privacy_url_s]',	// [スマフォ]個人情報取り扱いURL
	'mst_manage[campaign_code]', // キャンペーン設定名称
	
)

?>