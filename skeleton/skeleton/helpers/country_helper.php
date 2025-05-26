<?php
/**
 * @package 	CodeIgniter Skeleton
 * @subpackage 	Helpers
 * @category 	country_helper
 *
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @link        http://bit.ly/KaderGhb
 */
defined('BASEPATH') OR die;

/**
 * country_helper
 *
 * Country helper.
 *
 * @since 2.72
 */

if ( ! function_exists('countries'))
{
	/**
	 * Countries
	 *
	 * Returns an array of countries. This is a helper function for
	 * various other ones.
	 *
	 * @param 	string 	$country
	 * @return 	mixed
	 */
	function countries($country = null)
	{
		static $countries;

		if (empty($countries))
		{
			$CI =& get_instance();
			$CI->lang->load('country');

			$countries = array(
				'ad' => $CI->lang->line('country_ad'),
				'ae' => $CI->lang->line('country_ae'),
				'af' => $CI->lang->line('country_af'),
				'ag' => $CI->lang->line('country_ag'),
				'ai' => $CI->lang->line('country_ai'),
				'al' => $CI->lang->line('country_al'),
				'am' => $CI->lang->line('country_am'),
				'ao' => $CI->lang->line('country_ao'),
				'aq' => $CI->lang->line('country_aq'),
				'ar' => $CI->lang->line('country_ar'),
				'as' => $CI->lang->line('country_as'),
				'at' => $CI->lang->line('country_at'),
				'au' => $CI->lang->line('country_au'),
				'aw' => $CI->lang->line('country_aw'),
				'ax' => $CI->lang->line('country_ax'),
				'az' => $CI->lang->line('country_az'),
				'ba' => $CI->lang->line('country_ba'),
				'bb' => $CI->lang->line('country_bb'),
				'bd' => $CI->lang->line('country_bd'),
				'be' => $CI->lang->line('country_be'),
				'bf' => $CI->lang->line('country_bf'),
				'bg' => $CI->lang->line('country_bg'),
				'bh' => $CI->lang->line('country_bh'),
				'bi' => $CI->lang->line('country_bi'),
				'bj' => $CI->lang->line('country_bj'),
				'bl' => $CI->lang->line('country_bl'),
				'bm' => $CI->lang->line('country_bm'),
				'bn' => $CI->lang->line('country_bn'),
				'bo' => $CI->lang->line('country_bo'),
				'bq' => $CI->lang->line('country_bq'),
				'br' => $CI->lang->line('country_br'),
				'bs' => $CI->lang->line('country_bs'),
				'bt' => $CI->lang->line('country_bt'),
				'bv' => $CI->lang->line('country_bv'),
				'bw' => $CI->lang->line('country_bw'),
				'by' => $CI->lang->line('country_by'),
				'bz' => $CI->lang->line('country_bz'),
				'ca' => $CI->lang->line('country_ca'),
				'cc' => $CI->lang->line('country_cc'),
				'cd' => $CI->lang->line('country_cd'),
				'cf' => $CI->lang->line('country_cf'),
				'cg' => $CI->lang->line('country_cg'),
				'ch' => $CI->lang->line('country_ch'),
				'ci' => $CI->lang->line('country_ci'),
				'ck' => $CI->lang->line('country_ck'),
				'cl' => $CI->lang->line('country_cl'),
				'cm' => $CI->lang->line('country_cm'),
				'cn' => $CI->lang->line('country_cn'),
				'co' => $CI->lang->line('country_co'),
				'cr' => $CI->lang->line('country_cr'),
				'cu' => $CI->lang->line('country_cu'),
				'cv' => $CI->lang->line('country_cv'),
				'cw' => $CI->lang->line('country_cw'),
				'cx' => $CI->lang->line('country_cx'),
				'cy' => $CI->lang->line('country_cy'),
				'cz' => $CI->lang->line('country_cz'),
				'de' => $CI->lang->line('country_de'),
				'dj' => $CI->lang->line('country_dj'),
				'dk' => $CI->lang->line('country_dk'),
				'dm' => $CI->lang->line('country_dm'),
				'do' => $CI->lang->line('country_do'),
				'dz' => $CI->lang->line('country_dz'),
				'ec' => $CI->lang->line('country_ec'),
				'ee' => $CI->lang->line('country_ee'),
				'eg' => $CI->lang->line('country_eg'),
				'eh' => $CI->lang->line('country_eh'),
				'er' => $CI->lang->line('country_er'),
				'es' => $CI->lang->line('country_es'),
				'et' => $CI->lang->line('country_et'),
				'fi' => $CI->lang->line('country_fi'),
				'fj' => $CI->lang->line('country_fj'),
				'fk' => $CI->lang->line('country_fk'),
				'fm' => $CI->lang->line('country_fm'),
				'fo' => $CI->lang->line('country_fo'),
				'fr' => $CI->lang->line('country_fr'),
				'ga' => $CI->lang->line('country_ga'),
				'gb' => $CI->lang->line('country_gb'),
				'gd' => $CI->lang->line('country_gd'),
				'ge' => $CI->lang->line('country_ge'),
				'gf' => $CI->lang->line('country_gf'),
				'gg' => $CI->lang->line('country_gg'),
				'gh' => $CI->lang->line('country_gh'),
				'gi' => $CI->lang->line('country_gi'),
				'gl' => $CI->lang->line('country_gl'),
				'gm' => $CI->lang->line('country_gm'),
				'gn' => $CI->lang->line('country_gn'),
				'gp' => $CI->lang->line('country_gp'),
				'gq' => $CI->lang->line('country_gq'),
				'gr' => $CI->lang->line('country_gr'),
				'gs' => $CI->lang->line('country_gs'),
				'gt' => $CI->lang->line('country_gt'),
				'gu' => $CI->lang->line('country_gu'),
				'gw' => $CI->lang->line('country_gw'),
				'gy' => $CI->lang->line('country_gy'),
				'hk' => $CI->lang->line('country_hk'),
				'hm' => $CI->lang->line('country_hm'),
				'hn' => $CI->lang->line('country_hn'),
				'hr' => $CI->lang->line('country_hr'),
				'ht' => $CI->lang->line('country_ht'),
				'hu' => $CI->lang->line('country_hu'),
				'id' => $CI->lang->line('country_id'),
				'ie' => $CI->lang->line('country_ie'),
				'im' => $CI->lang->line('country_im'),
				'in' => $CI->lang->line('country_in'),
				'io' => $CI->lang->line('country_io'),
				'iq' => $CI->lang->line('country_iq'),
				'ir' => $CI->lang->line('country_ir'),
				'is' => $CI->lang->line('country_is'),
				'it' => $CI->lang->line('country_it'),
				'je' => $CI->lang->line('country_je'),
				'jm' => $CI->lang->line('country_jm'),
				'jo' => $CI->lang->line('country_jo'),
				'jp' => $CI->lang->line('country_jp'),
				'ke' => $CI->lang->line('country_ke'),
				'kg' => $CI->lang->line('country_kg'),
				'kh' => $CI->lang->line('country_kh'),
				'ki' => $CI->lang->line('country_ki'),
				'km' => $CI->lang->line('country_km'),
				'kn' => $CI->lang->line('country_kn'),
				'kp' => $CI->lang->line('country_kp'),
				'kr' => $CI->lang->line('country_kr'),
				'kw' => $CI->lang->line('country_kw'),
				'ky' => $CI->lang->line('country_ky'),
				'kz' => $CI->lang->line('country_kz'),
				'la' => $CI->lang->line('country_la'),
				'lb' => $CI->lang->line('country_lb'),
				'lc' => $CI->lang->line('country_lc'),
				'li' => $CI->lang->line('country_li'),
				'lk' => $CI->lang->line('country_lk'),
				'lr' => $CI->lang->line('country_lr'),
				'ls' => $CI->lang->line('country_ls'),
				'lt' => $CI->lang->line('country_lt'),
				'lu' => $CI->lang->line('country_lu'),
				'lv' => $CI->lang->line('country_lv'),
				'ly' => $CI->lang->line('country_ly'),
				'ma' => $CI->lang->line('country_ma'),
				'mc' => $CI->lang->line('country_mc'),
				'md' => $CI->lang->line('country_md'),
				'me' => $CI->lang->line('country_me'),
				'mf' => $CI->lang->line('country_mf'),
				'mg' => $CI->lang->line('country_mg'),
				'mh' => $CI->lang->line('country_mh'),
				'mk' => $CI->lang->line('country_mk'),
				'ml' => $CI->lang->line('country_ml'),
				'mm' => $CI->lang->line('country_mm'),
				'mn' => $CI->lang->line('country_mn'),
				'mo' => $CI->lang->line('country_mo'),
				'mp' => $CI->lang->line('country_mp'),
				'mq' => $CI->lang->line('country_mq'),
				'mr' => $CI->lang->line('country_mr'),
				'ms' => $CI->lang->line('country_ms'),
				'mt' => $CI->lang->line('country_mt'),
				'mu' => $CI->lang->line('country_mu'),
				'mv' => $CI->lang->line('country_mv'),
				'mw' => $CI->lang->line('country_mw'),
				'mx' => $CI->lang->line('country_mx'),
				'my' => $CI->lang->line('country_my'),
				'mz' => $CI->lang->line('country_mz'),
				'na' => $CI->lang->line('country_na'),
				'nc' => $CI->lang->line('country_nc'),
				'ne' => $CI->lang->line('country_ne'),
				'nf' => $CI->lang->line('country_nf'),
				'ng' => $CI->lang->line('country_ng'),
				'ni' => $CI->lang->line('country_ni'),
				'nl' => $CI->lang->line('country_nl'),
				'no' => $CI->lang->line('country_no'),
				'np' => $CI->lang->line('country_np'),
				'nr' => $CI->lang->line('country_nr'),
				'nu' => $CI->lang->line('country_nu'),
				'nz' => $CI->lang->line('country_nz'),
				'om' => $CI->lang->line('country_om'),
				'pa' => $CI->lang->line('country_pa'),
				'pe' => $CI->lang->line('country_pe'),
				'pf' => $CI->lang->line('country_pf'),
				'pg' => $CI->lang->line('country_pg'),
				'ph' => $CI->lang->line('country_ph'),
				'pk' => $CI->lang->line('country_pk'),
				'pl' => $CI->lang->line('country_pl'),
				'pm' => $CI->lang->line('country_pm'),
				'pn' => $CI->lang->line('country_pn'),
				'pr' => $CI->lang->line('country_pr'),
				'ps' => $CI->lang->line('country_ps'),
				'pt' => $CI->lang->line('country_pt'),
				'pw' => $CI->lang->line('country_pw'),
				'py' => $CI->lang->line('country_py'),
				'qa' => $CI->lang->line('country_qa'),
				're' => $CI->lang->line('country_re'),
				'ro' => $CI->lang->line('country_ro'),
				'rs' => $CI->lang->line('country_rs'),
				'ru' => $CI->lang->line('country_ru'),
				'rw' => $CI->lang->line('country_rw'),
				'sa' => $CI->lang->line('country_sa'),
				'sb' => $CI->lang->line('country_sb'),
				'sc' => $CI->lang->line('country_sc'),
				'sd' => $CI->lang->line('country_sd'),
				'se' => $CI->lang->line('country_se'),
				'sg' => $CI->lang->line('country_sg'),
				'sh' => $CI->lang->line('country_sh'),
				'si' => $CI->lang->line('country_si'),
				'sj' => $CI->lang->line('country_sj'),
				'sk' => $CI->lang->line('country_sk'),
				'sl' => $CI->lang->line('country_sl'),
				'sm' => $CI->lang->line('country_sm'),
				'sn' => $CI->lang->line('country_sn'),
				'so' => $CI->lang->line('country_so'),
				'sr' => $CI->lang->line('country_sr'),
				'ss' => $CI->lang->line('country_ss'),
				'st' => $CI->lang->line('country_st'),
				'sv' => $CI->lang->line('country_sv'),
				'sx' => $CI->lang->line('country_sx'),
				'sy' => $CI->lang->line('country_sy'),
				'sz' => $CI->lang->line('country_sz'),
				'tc' => $CI->lang->line('country_tc'),
				'td' => $CI->lang->line('country_td'),
				'tf' => $CI->lang->line('country_tf'),
				'tg' => $CI->lang->line('country_tg'),
				'th' => $CI->lang->line('country_th'),
				'tj' => $CI->lang->line('country_tj'),
				'tk' => $CI->lang->line('country_tk'),
				'tl' => $CI->lang->line('country_tl'),
				'tm' => $CI->lang->line('country_tm'),
				'tn' => $CI->lang->line('country_tn'),
				'to' => $CI->lang->line('country_to'),
				'tr' => $CI->lang->line('country_tr'),
				'tt' => $CI->lang->line('country_tt'),
				'tv' => $CI->lang->line('country_tv'),
				'tw' => $CI->lang->line('country_tw'),
				'tz' => $CI->lang->line('country_tz'),
				'ua' => $CI->lang->line('country_ua'),
				'ug' => $CI->lang->line('country_ug'),
				'um' => $CI->lang->line('country_um'),
				'us' => $CI->lang->line('country_us'),
				'uy' => $CI->lang->line('country_uy'),
				'uz' => $CI->lang->line('country_uz'),
				'va' => $CI->lang->line('country_va'),
				'vc' => $CI->lang->line('country_vc'),
				've' => $CI->lang->line('country_ve'),
				'vg' => $CI->lang->line('country_vg'),
				'vi' => $CI->lang->line('country_vi'),
				'vn' => $CI->lang->line('country_vn'),
				'vu' => $CI->lang->line('country_vu'),
				'wf' => $CI->lang->line('country_wf'),
				'ws' => $CI->lang->line('country_ws'),
				'ye' => $CI->lang->line('country_ye'),
				'yt' => $CI->lang->line('country_yt'),
				'za' => $CI->lang->line('country_za'),
				'zm' => $CI->lang->line('country_zm'),
				'zw' => $CI->lang->line('country_zw')
			);

			// Sort alphabetically.
			asort($countries);
		}

		return (empty($country) OR ! isset($countries[$country])) ? $countries : $countries[$country];
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('country_menu'))
{
	/**
	 * Country Menu
	 *
	 * Generates a drop-down menu of countries.
	 *
	 * @param   string	$default 	The selected country.
	 * @param   string	$class 		The CSS class name.
	 * @param   string	$name 		The form input name.
	 * @param   mixed	$attrs 		Array of other attributes.
	 * @return  string
	 */
	function country_menu($default = null, $class = '', $name = 'country', $attrs = '')
	{
		$menu = '<select name="'.$name.'"';

		empty($class) OR $menu .= ' class="'.$class.'"';

		$menu .= array_to_attr($attrs);

		// Firefox/Browser 'selected' hack (autocomplete)
		(strpos($menu, 'autocomplete') === false) && $menu .= ' autocomplete="off"';

		$menu .= ">\n";

		foreach (countries() as $key => $val)
		{
			$selected = ($default === $key) ? ' selected="selected"' : '';
			$menu .= '<option value="'.$key.'"'.$selected.'>'.$val."</option>\n";
		}

		return $menu.'</select>';
	}
}
