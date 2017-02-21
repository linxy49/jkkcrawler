<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Config;
use Goutte\Client;
use Illuminate\Console\Command;
use Log;
use Redis;

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
     * 検索画面(初期化)
     *
     * @var string
     */
    const AKIYAJYOUKENSTARTINIT = 'https://jhomes.to-kousya.or.jp/search/jkknet/service/akiyaJyoukenStartInit';

    /**
     * 検索結果
     *
     * @var string
     */
    const AKIYAJYOUKENREF = 'https://jhomes.to-kousya.or.jp/search/jkknet/service/akiyaJyoukenRef';

    private $result;

    /**
     * 区部
     *
     * @var array
     */
    const KU = array(
        "01" => "千代田区",
        "02" => "中央区",
        "03" => "港区",
        "04" => "新宿区",
        "05" => "文京区",
        "06" => "台東区",
        "07" => "墨田区",
        "08" => "江東区",
        "09" => "品川区",
        "10" => "目黒区",
        "11" => "大田区",
        "12" => "世田谷区",
        "13" => "渋谷区",
        "14" => "中野区",
        "15" => "杉並区",
        "16" => "豊島区",
        "17" => "北区",
        "18" => "荒川区",
        "19" => "板橋区",
        "20" => "練馬区",
        "21" => "足立区",
        "22" => "葛飾区",
        "23" => "江戸川区"
    );

    /**
    * 市部
    */
    const SI = array(
        "31" => "八王子市",
        "32" => "立川市",
        "33" => "武蔵野市",
        "34" => "三鷹市",
        "35" => "青梅市",
        "36" => "府中市",
        "37" => "昭島市",
        "38" => "調布市",
        "39" => "町田市",
        "40" => "小金井市",
        "41" => "小平市",
        "42" => "日野市",
        "43" => "東村山市",
        "44" => "国分寺市",
        "45" => "国立市",
        "46-47" => "西東京市",
        "48" => "福生市",
        "49" => "狛江市",
        "50" => "東大和市",
        "51" => "清瀬市",
        "52" => "東久留米市",
        "53" => "武蔵村山市",
        "54" => "多摩市",
        "55" => "稲城市",
        "56-64" => "あきるの市",
        "57" => "羽村市",
        "62" => "瑞穂町",
        "63" => "日の出町",
        "65" => "檜原村",
        "66" => "奥多摩町"
    );

    /**
     * 間取り
     */
    const MADORI = array(
        "1" => "1R・1K ～ 1LDK",
        "2" => "2K ～ 2LDK",
        "3" => "3K ～ 3LDK",
        "4" => "4K以上",
    );

    /**
     * 家賃
     */
    const YACHIN = array(
        "0" => "30000",
        "30000" => "40000",
        "40000" => "50000",
        "50000" => "60000",
        "60000" => "70000",
        "70000" => "80000",
        "80000" => "90000",
        "90000" => "100000",
        "100000" => "120000",
        "120000" => "140000",
        "140000" => "160000",
        "160000" => "180000",
        "180000" => "200000",
        "200000" => "300000",
        "300000" => "999999999",
    );

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->result = array();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info('jkkcrawler start.');
        // 初期化
        $client = $this->initClient();

        // フォームを取得する
        $crawler = $client->request('GET', JkkCrawler::AKIYAJYOUKENSTARTINIT);
        $forwardForm = $crawler->filter('form')->form();
        $crawler = $client->submit($forwardForm);

        // 区部を検索する
        $this->search($client, JkkCrawler::KU);

        // TODO
        // 市部を検索する
        // $this->search($client, JkkCrawler::SI);

		// エンコード
		$set = $this->result;
		$get = json_decode(Redis::get ( "jkk" ));

		// 差分を取得する
		if (!empty($get)) {
			$diff = $this->diff($get, $set);
			$recent = json_decode(Redis::get ( "recent" ));
			Log::info($recent);

			if (0 < count($diff)) {
				Log::info($diff);
				foreach($diff as $data) {
					$recent[] = $data;
				}
			}
			Redis::set ( "recent", json_encode($recent) );
		}

		// データを保存する
		Redis::set ( "jkk", json_encode($set) );

		$now = date('Y/m/d H:i:s');
		Redis::set ( "updated_at",  $now);

		Log::info('jkkcrawler end.');
    }

    /**
     * init
     *
     * @return $client
     */
    public function initClient() {
        $client = new Client();
        // ブラウザの偽装
        $client->setHeader('User-Agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36');
        // SSL無効化
        $client->setClient(new \GuzzleHttp\Client([\GuzzleHttp\RequestOptions::VERIFY => false,]));
        // 戻り値
        return $client;
    }

    /**
     * search
     *
     * @return null
     */
    public function search($client, $conditions)
    {
        // Log::debug("search start.");
        // 市区部を繰り返す
        foreach ($conditions as $key => $value) {
            $crawler = $client->request('POST', JkkCrawler::AKIYAJYOUKENREF, array(
                'akiyaInitRM.akiyaRefM.checks' => $key,
                'akiyaInitRM.akiyaRefM.madoris' => "",
                'akiyaInitRM.akiyaRefM.yachinFrom' => "",
                'akiyaInitRM.akiyaRefM.yachinTo' => ""
            ));

            $count = 0;
            $crawler->filter('strong')->eq(0)->each(function($node) use (&$count) {
                $count = preg_replace('/[^0-9]/', '', $node->text());
            });


			$sikubu['key'] = $key;
			$sikubu['value'] = $value;

            if (10 < $count) {
                // Log::debug($value . "：[" . $count . "]");
                // 10件以外場合,間取り条件で再検索する
                $this->rearchByMadori($client, JkkCrawler::MADORI, $sikubu);
            } else {
                if (0 == $count) {
                    if (strpos($crawler->text(), "mousikomi") !== false) {
                        $this->getBuildingOnlyOne($crawler, $sikubu);
                        // １件のみ場合、詳細画面を取得する。
                        $count++;
                    }
                } else {
                    $this->getBuilding($crawler, $sikubu);
                }
                // Log::debug($value . "：[" . $count . "]");
            }
        }
        // Log::debug("search end.");
    }

    // JkkCrawler::MADORI
    public function rearchByMadori($client, $conditions, $sikubu)
    {
        // Log::debug("rearchByMadori start.");
        // 間取りを繰り返す
        foreach ($conditions as $key => $value) {
            $crawler = $client->request('POST', JkkCrawler::AKIYAJYOUKENREF, array(
                'akiyaInitRM.akiyaRefM.checks' => $sikubu['key'],
                'akiyaInitRM.akiyaRefM.madoris' => $key,
                'akiyaInitRM.akiyaRefM.yachinFrom' => "",
                'akiyaInitRM.akiyaRefM.yachinTo' => ""
            ));

            $count = 0;
            $crawler->filter('strong')->eq(0)->each(function($node) use (&$count) {
                $count = preg_replace('/[^0-9]/', '', $node->text());
            });

            if (10 < $count) {
                // Log::debug($value . "：[" . $count . "]");
                // 10件以外場合,家賃条件で再検索する
                $madori['key']=$key;
                $madori['value']=$value;
                $this->rearchByYachin($client, JkkCrawler::YACHIN, $sikubu, $madori);
            } else {
                if (0 == $count) {
                    if (strpos($crawler->text(), "mousikomi") !== false) {
                        $this->getBuildingOnlyOne($crawler, $sikubu);
                        // １件のみ場合、詳細画面を取得する。
                        $count++;
                    }
                } else {
                    $this->getBuilding($crawler, $sikubu);
                }
                // Log::debug($value . "：[" . $count . "]");
            }
        }
        // Log::debug("rearchByMadori end.");
    }

    // JkkCrawler::YACHIN
    public function rearchByYachin($client, $conditions, $sikubu, $madori)
    {
        // Log::debug("rearchByYachin start.");
        // 家賃を繰り返す
        foreach ($conditions as $from => $to) {
            $crawler = $client->request('POST', JkkCrawler::AKIYAJYOUKENREF, array(
                'akiyaInitRM.akiyaRefM.checks' => $sikubu['key'],
                'akiyaInitRM.akiyaRefM.madoris' => $madori['key'],
                'akiyaInitRM.akiyaRefM.yachinFrom' => $from,
                'akiyaInitRM.akiyaRefM.yachinTo' => $to
            ));

            $count = 0;
            $crawler->filter('strong')->eq(0)->each(function($node) use (&$count) {
                $count = preg_replace('/[^0-9]/', '', $node->text());
            });

            if (10 < $count) {
                // TODO
                // Log::debug($from . "-" . $to . "：[" . $count . "]");
            } else {
                if (0 == $count) {
                    if (strpos($crawler->text(), "mousikomi") !== false) {
                        $this->getBuildingOnlyOne($crawler, $sikubu);
                        // １件のみ場合、詳細画面を取得する。
                        $count++;
                    }
                }
                // Log::debug($from . "-" . $to . "：[" . $count . "]");
            }
        }
        // Log::debug("rearchByYachin end.");
    }


    function getBuilding($crawler, $sikubu) {
        $crawler->filter('tr.ListTXT1')->each(function($node) use ($sikubu) {
            $name = $this->format($node->filter('td.ListTXT1')->eq(1)->text());
            $madori = $this->format($node->filter('td.ListTXT1')->eq(5)->text());
            $yukamenseki = $this->format($node->filter('td.ListTXT1')->eq(6)->text());
            $yachin = $this->format($node->filter('td.ListTXT1')->eq(7)->text());
            $kyoekihi = $this->format($node->filter('td.ListTXT1')->eq(8)->text());
            $kosu = $this->format($node->filter('td.ListTXT1')->eq(9)->text());

            $this->result[] = array (
				"sikubu" => $sikubu['value'],
                "name" => $name,
                "madori" => $madori,
                "yukamenseki" => $yukamenseki,
                "yachin" => $yachin,
                "kyoekihi" => $kyoekihi,
                "kosu" => $kosu
            );
            // Log::debug("[" . $name . "-" . $madori . "-" . $yukamenseki . "-" . $yachin . "-" . $kyoekihi . "-" . $kosu . "]");
        });

        $crawler->filter('tr.ListTXT2')->each(function($node) use ($sikubu) {
            $name = $this->format($node->filter('td.ListTXT2')->eq(1)->text());
            $madori = $this->format($node->filter('td.ListTXT2')->eq(5)->text());
            $yukamenseki = $this->format($node->filter('td.ListTXT2')->eq(6)->text());
            $yachin = $this->format($node->filter('td.ListTXT2')->eq(7)->text());
            $kyoekihi = $this->format($node->filter('td.ListTXT2')->eq(8)->text());
            $kosu = $this->format($node->filter('td.ListTXT2')->eq(9)->text());

            $this->result[] = array (
				"sikubu" => $sikubu['value'],
                "name" => $name,
                "madori" => $madori,
                "yukamenseki" => $yukamenseki,
                "yachin" => $yachin,
                "kyoekihi" => $kyoekihi,
                "kosu" => $kosu
            );
            // Log::debug("[" . $name . "-" . $madori . "-" . $yukamenseki . "-" . $yachin . "-" . $kyoekihi . "-" . $kosu . "]");
        });
    }

    function getBuildingOnlyOne($crawler, $sikubu) {
        // Log::debug("getBuildingOnlyOne start.");
        $name = $this->format($crawler->filter('td.Data_cell')->eq(2)->text());
        $madori = $this->format($crawler->filter('td.ListTXT1')->eq(4)->text());
        $yukamenseki = $this->format($crawler->filter('td.ListTXT1')->eq(11)->text());
        $yachin = $this->format($crawler->filter('td.ListTXT1')->eq(6)->text());
        $kyoekihi = $this->format($crawler->filter('td.ListTXT1')->eq(8)->text());
        $kosu = "1";

        $this->result[] = array (
			"sikubu" => $sikubu['value'],
            "name" => $name,
            "madori" => $madori,
            "yukamenseki" => $yukamenseki,
            "yachin" => $yachin,
            "kyoekihi" => $kyoekihi,
            "kosu" => $kosu
        );
        //Log::debug("[" . $name . "-" . $madori . "-" . $yukamenseki . "-" . $yachin . "-" . $kyoekihi . "-" . $kosu . "]");
        // Log::debug("getBuildingOnlyOne end.");
    }

	function diff ($old, $new) {
		Log::debug("diff start.");

		$now = date('Y/m/d H:i:s');

		$diff = array();
		foreach($old as $v1) {
			$delFlag = true;
			foreach($new as $v2) {
				if (($v1->sikubu == $v2['sikubu'])
					&& ($v1->name == $v2['name'])
					&& ($v1->madori == $v2['madori'])
					&& ($v1->yukamenseki == $v2['yukamenseki'])
					&& ($v1->yachin == $v2['yachin'])
					&& ($v1->kyoekihi == $v2['kyoekihi'])) {
						$delFlag = false;
						break;
				}
			}

			if ($delFlag) {
				$array = array();
				$array['sikubu'] = $v1->sikubu;
				$array['name'] = $v1->name;
				$array['madori'] = $v1->madori;
				$array['yukamenseki'] = $v1->yukamenseki;
				$array['yachin'] = $v1->yachin;
				$array['kyoekihi'] = $v1->kyoekihi;
				$array['kosu'] = "-".$v1->kosu;
				$array['updated_at'] = $now;
				$diff[] = $array;
			}
		}

		foreach($new as $v2) {
			$addFlag = true;
			foreach($old as $v1) {
				if ($v2['sikubu'] == $v1->sikubu
					&& $v2['name'] == $v1->name
					&& $v2['madori'] == $v1->madori
					&& $v2['yukamenseki'] == $v1->yukamenseki
					&& $v2['yachin'] == $v1->yachin
					&& $v2['kyoekihi'] == $v1->kyoekihi) {
						if ($v2['kosu'] != $v1->kosu) {
							$count = strval (intval($v2['kosu']) - intval($v1->kosu));
							$v2['kosu'] = $count;
							$v2['updated_at'] = $now;
							$diff[] = $v2;
						}
						$addFlag = false;
						break;
				}
			}

			if ($addFlag) {
				$v2['updated_at'] = $now;
				$diff[] = $v2;
			}
		}

		Log::debug("diff end.");
		return $diff;
	}


    function format($str) {
		// 両サイドのスペースを消す
        $str = trim($str);
        // 改行、タブをスペースへ
        $str = preg_replace('/[\n\r\t]/', '', $str);
        // 複数スペースを一つへ
        $str = preg_replace('/\s(?=\s)/', '', $str);
        return $str;
    }
}
