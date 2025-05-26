<?php
defined('BASEPATH') OR die;

/**
 * Seo Controller
 *
 * Handles stuff related to SEO.
 *
 * @package     CodeIgniter
 * @subpackage  Skeleton
 * @category    Controllers
 * @author      Kader Bouyakoub <bkader[at]mail[dot]com>
 * @link        http://bit.ly/KaderGhb
 * @copyright   Copyright (c) 2024, Kader Bouyakoub (http://bit.ly/KaderGhb)
 * @since       2.103
 */
class Seo extends Public_Controller
{
	/**
	 * Array of default robots rules.
	 * @var array
	 */
	protected $robots_rules = array(
		'User-agent: *',
		'Disallow: /'.KB_ADMIN
	);

	// --------------------------------------------------------------------

	/**
	 * Class constructor.
	 *
	 * @return 	void
	 */
	public function __construct()
	{
		parent::__construct();

		// Load cache driver to cache robots and sitemap
		isset($this->cache) OR $this->load->driver('cache', array('adapter' => 'file'));
	}

	// --------------------------------------------------------------------
	// robots.txt Methods
	// --------------------------------------------------------------------

	/**
	 * Dynamically generates site's 'robots.txt'.
	 *
	 * @return 	void
	 */
	public function robots()
	{
		// Just a redirection if not the proper route.
		if ($this->uri->segment(1) !== 'robots.txt')
		{
			redirect('robots.txt');
		}

		elseif (($robots_txt = $this->cache->get('seo_robots_txt')) === false)
		{
			// Retrieve rules from options.
			if ( ! empty($rules = $this->config->item('robots_rules')))
			{
				foreach ($rules as $rule)
				{
					$this->robots_rules[] = ($rule['allow'] ? 'Allow' : 'Disallow').': '.$rule['path'];
				}
			}

			// Generate the TXT and cache it.
			$robots_txt = implode("\n", $this->robots_rules);
			$this->cache->save('seo_robots_txt', $robots_txt, DAY_IN_SECONDS);
		}

		$this->output
			->set_content_type('text')
			->set_output($robots_txt);
	}

	// --------------------------------------------------------------------
	// sitemap.xml Methods
	// --------------------------------------------------------------------

	/**
	 * Dynamically generates site's 'sitemap.xml'.
	 *
	 * @return 	void
	 */
	public function sitemap()
	{
		// Redirect if URL is not exactly sitemap.xml
		if ($this->uri->segment(1) !== 'sitemap.xml')
		{
			redirect('sitemap.xml');
		}

		// Try to get cached sitemap XML
		elseif (($sitemap_xml = $this->cache->get('seo_sitemap_xml')) === false)
		{
			// Create root <urlset> with default namespace
			$xml = new SimpleXMLElement('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"/>');

			// Add extra namespaces
			$xml->addAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
			$xml->addAttribute('xmlns:xhtml', 'http://www.w3.org/TR/xhtml11/xhtml11_schema.html');
			$xml->addAttribute(
				'xsi:schemaLocation',
				'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd ' .
				'http://www.w3.org/TR/xhtml11/xhtml11_schema.html http://www.w3.org/2002/08/xhtml/xhtml1-strict.xsd',
				'http://www.w3.org/2001/XMLSchema-instance'
			);

			// Add homepage URL
			$this->add_sitemap_url($xml, '', TIME, 'daily', '1.0');

			// Add all sitemap children URLs
			if ($sitemaped = $this->core->sitemaped())
			{
				foreach ($sitemaped as $child)
				{
					$this->add_sitemap_url(
						$xml,
						$child->subtype.'/'.($child->username ?? $child->id),
						$child->updated_at,
						'weekly',
						'0.7'
					);
				}
			}

			// Generate XML string without the default XML declaration
			// then remove the XML declaration added by asXML()
			$sitemap_xml = $xml->asXML();
			$sitemap_xml = preg_replace('/^<\?xml.*?\?>\s*/', '', $sitemap_xml);

			// Add XML declaration with encoding, e.g. UTF-8
			$sitemap_xml = sprintf(
				"<?xml version=\"1.0\" encoding=\"%s\"?>\n%s",
				$this->config->item('charset') ?? 'UTF-8',
				$sitemap_xml
			);

			// Cache the sitemap XML for 1 day
			$this->cache->save('seo_sitemap_xml', $sitemap_xml, DAY_IN_SECONDS);
		}

		// Output with proper content type header and charset
		$this->output
			->set_content_type('xml', $this->config->item('charset'))
			->set_output($sitemap_xml);
	}

	// --------------------------------------------------------------------

	/**
	 * Adds a new URL to SimpleXMLElement.
	 *
	 * @param 	object 	$xml
	 * @param 	string 	$loc
	 * @param 	int 	$lastmod
	 * @param 	int 	$changefreq
	 * @param 	string 	$priority
	 * @return 	void
	 */
	private function add_sitemap_url($xml, $loc, $lastmod, $changefreq, $priority)
	{
		$url = $xml->addChild('url');
		$url->addChild('loc', htmlspecialchars($this->config->site_url($loc)));
		$url->addChild('lastmod', date('c', $lastmod));
		$url->addChild('changefreq', $changefreq);
		$url->addChild('priority', $priority);
	}

	// --------------------------------------------------------------------
	// manifest.json Methods
	// --------------------------------------------------------------------

	/**
	 * Dynamically generates site's 'manifest.json'.
	 *
	 * @return 	void
	 */
	public function manifest()
	{
		// Manifest disabled?
		if ( ! $this->config->item('use_manifest'))
		{
			redirect('');
		}
		// Just a redirection if not the proper route.
		elseif ($this->uri->segment(1) !== 'manifest.json')
		{
			redirect('manifest.json');
		}

		// Cache it per language.
		$lang = $this->i18n->current('code');
		$cache_key = 'seo_manifest_json_'.$lang;

		// See if already cached.
		if (($manifest_json = $this->cache->get($cache_key)) === false)
		{
			// Start 'manifest_json' array.
			$manifest_json = array(
				'name'             => $this->theme->site_name,
				'short_name'       => $this->config->item('site_short_name', null, $this->theme->site_name),
				'lang'             => $lang,
				'dir'              => $this->i18n->current('direction'),
				'id'               => '/',
				'start_url'        => $this->config->base_url(),
				'display'          => 'standalone',
				'background_color' => '#'.str_replace('#', '', $this->config->item('site_background_color', null, 'ffffff')),
				'theme_color'      => '#'.str_replace('#', '', $this->config->item('site_theme_color', null, '134d78')),
				'orientation'      => 'portrait',
				'scope'            => '/',
				'description'      => $this->theme->site_description
			);

			/**
			 * Generate icons with sizes.
			 * @uses 'site_icons' filter from current theme.
			 */
			if ( ! empty($icon_base_url = apply_filters('site_icons', $this->config->common_url('img/'))))
			{
				$icon_sizes = array(
					'57x57',
					'60x60',
					'72x72',
					'76x76',
					'114x114',
					'120x120',
					'144x144',
					'152x152',
					'180x180',
					'192x192',
					'512x512'
				);

				foreach ($icon_sizes as $size)
				{
					$manifest_json['icons'][] = array(
						'src' => "{$icon_base_url}apple-touch-icon-{$size}.png",
						'type' => 'image/png',
						'sizes' => $size
					);
				}
			}

			$manifest_json = json_encode($manifest_json, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
			$this->cache->save($cache_key, $manifest_json, DAY_IN_SECONDS);
		}

		$this->output
			->set_content_type('json')
			->set_output($manifest_json);
	}

}
