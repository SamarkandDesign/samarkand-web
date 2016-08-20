<?php

namespace App\Search;

use Carbon\Carbon;
use Illuminate\Cache\Repository as Cache;
use Illuminate\Config\Repository as Config;

class TokenGenerator {

	protected $config;
	protected $cache;
	protected $expiry;
	protected $algolia;

	public function __construct(Config $config, Cache $cache)
	{
		$this->config = $config;
		$this->cache = $cache;

		$this->algolia = \SearchIndex::getClient();
		$this->expiry = Carbon::now()->addWeek();
	}

	public function getVisitorFilters()
	{
		$filters = 'listed:true';
		if (!$this->config->get('shop.show_out_of_stock')) {
			$filters .=  ' AND stock_qty>0';
		}
		return $filters;
	}

	/**
	 * Get a secured token for searching and cache it for future use
	 * @return String
	 */
	public function getProductSearchToken() 
	{

		return $this->cache->remember('shop_search_key.visitor', $this->expiry->subDay(), function () {
			return $this->algolia->generateSecuredApiKey($this->config->get('searchindex.algolia.search-only-api-key'), [
				'filters'    => $this.getVisitorFilters(),
				'validUntil' => $this->expiry->timestamp,
				]);
		});
	}

	/**
	 * Get a token for admin use
	 * @return String
	 */
	public function getAdminProductToken()
	{
		return $this->cache->remember('shop_search_key.admin', $this->expiry->subDay(), function () {

			return $this->algolia->generateSecuredApiKey($this->config->get('searchindex.algolia.search-only-api-key'), [
				'validUntil' => $this->expiry->timestamp,
				]);
		});
	}
}