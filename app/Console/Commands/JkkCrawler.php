<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Config;
use Goutte\Client;
use Illuminate\Console\Command;
use Log;

/**
 * Jkk Data Crawler
 */
class JkkCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jkkcrawler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Jkk Data Crawler';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
		Log::info('jkkcrawler start.');
        parent::__construct();
		Log::info('jkkcrawler end.');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

		$client = new Client();
		$client->setHeader('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36');
		$client->setClient(new \GuzzleHttp\Client([\GuzzleHttp\RequestOptions::VERIFY => false,]));

		// 入口
		// $url = 'https://jhomes.to-kousya.or.jp/search/jkknet/service/akiyaJyokenDirect';
		$url = 'https://jhomes.to-kousya.or.jp/search/jkknet/service/akiyaJyoukenStartInit';
		$crawler = $client->request('GET', $url);
		$forwardForm = $crawler->filter('form')->form();
		$crawler = $client->submit($forwardForm);

		$searchUrl = 'https://jhomes.to-kousya.or.jp/search/jkknet/service/akiyaJyoukenRef';
		$crawler = $client->request('post', $searchUrl);
		Log::debug("==========================akiyaJyoukenStartInit start==================================");
		// Log::debug($crawler->html());
		Log::debug("==========================akiyaJyoukenStartInit end  ==================================");

		$searchUrl = 'https://jhomes.to-kousya.or.jp/search/jkknet/service/akiyaJyoukenRef';
		$crawler = $client->request('post', $searchUrl);
		Log::debug("==========================akiyaJyoukenRef start==================================");
		// Log::debug($crawler->html());
		Log::debug("==========================akiyaJyoukenRef end  ==================================");



		Log::debug("==========================page 2==================================");
		$frmMain = $crawler->filter('form')->form();
		$crawler = $client->submit($frmMain);
		Log::debug($crawler->html());
		Log::debug("==========================page 2==================================");

		// $pageUrl = 'https://jhomes.to-kousya.or.jp/search/jkknet/service/AKIYAafterPage';
		// $crawler = $client->request('post', $pageUrl);
		// Log::debug($crawler->html());

		// $frmMain = $crawler->filter('form')->form();
		// $crawler = $client->submit($frmMain);
		// Log::debug($crawler->html());

		// $url = 'https://jhomes.to-kousya.or.jp/search/jkknet/service/AKIYApageNum';
		// $crawler = $client->request('GET', $url);
		// $frmMainForm = $crawler->filter('form')->form();
		// $crawler = $client->submit($frmMainForm);
		// Log::debug($crawler->html());

		// // record count(all)
		// $count = 0;
		//
		// // first page
		// $first->filter('tr.ListTXT2')->each(function($node) use (&$count) {
		// 	$count++;
		// });
		//
		// $first->filter('tr.ListTXT1')->each(function($node) use (&$count) {
		// 	$count++;
		// });
		// Log::debug("レコード数：". $count);








		// $kw = 'movePagingInputGridPageAbs';
		// $first->filter('table table table table span.navigationTXT')->eq(1)->each(function($node) use ($kw, &$count, $client) {
		// 	// get page number link
		// 	// $idx = 1;
		// 	// $node->filter('a')->each(function($e) use (&$idx, $kw) {
		// 	// 	$target = $e->attr('onclick');
		// 	// 	if (strpos($target, $kw) !== false) {
		// 	// 		$idx++;
		// 	// 	}
		// 	// });
		//
		// 	// next page
		//
		// 	// for ($i = 1; $i < $idx; $i++) {
		// 	// 	$crawler=$client->click($node->selectLink(strval($i+1))->link());
		// 	// 	Log::debug($crawler->html());
		// 	// 	break;
		// 	// }
		// });
    }
}
// $html = mb_convert_encoding($node->html(), 'HTML-ENTITIES', 'UTF-8');
