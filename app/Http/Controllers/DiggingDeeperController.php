<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateCatalog\GenerateCatalogMainJob;
use App\Models\BlogPost;
use Faker\Generator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DiggingDeeperController extends Controller
{
    public function collections()
    {
        $result = [];
        $eloqCollection = BlogPost::withTrashed()->get();

        $collection = collect($eloqCollection->toArray());

        $result['first'] = $collection->first();
        $result['last'] = $collection->last();

        $result['where']['data'] = $collection
            ->where('category_id', 10)
            ->keyBy('id');

        $result['where_first'] = $collection->firstWhere('created_at', '>', '2020-09-01 00:00:00');

        $result['map']['all'] = $collection->map(function (array $item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];

            return $newItem;
        });


        dd(
            __METHOD__,
            $result
        );
    }

    public function prepareCatalog()
    {
        GenerateCatalogMainJob::dispatch();
    }


    public function debug()
    {
        $result = $this->dbgs(true);
    }


    private function dbgs($skipExit = false, $time = null, $out = 'print')
    {
        global $dbgs;

        $time = @((!$time) ? LARAVEL_START : $time);
        if (class_exists('Lt_Db')) {
            $script['q'] = Lt_Db::getInstance()->getQueryCount();
        } else {
            $script['q'] = '-';
        }
        $script['time'] = round(microtime(true) - $time, 5);
        $script['mem'] = round(memory_get_usage() / 1024, 2);
        $script['memPeak'] = round(memory_get_peak_usage() / 1024, 2);

        $script['text'] = "\r\n";
        $script['text'] .= 'Queries       : <b>' . $script['q'] . '</b>' . "\r\n";
        $script['text'] .= 'Executed at   : <b>' . $script['time'] . '</b> s' . "\r\n";
        $script['text'] .= 'Memory usage  : <b>' . $script['mem'] . '</b> kb' . "\r\n";
        $script['text'] .= 'Memory peak   : <b>' . $script['memPeak'] . '</b> kb' . "\r\n";
        $script['text'] .= 'Files / Ext   : <b>' . count(get_included_files()) . ' / ' . count(get_loaded_extensions()) . '</b>' . "\r\n";

        if ($dbgs) {
            foreach ($dbgs as $array) {
                $script['text'] .= str_pad($array[0], 14) . ': <b>' . $array[1] . '</b>' . "\r\n";
            }
        }


        if ($out == 'print') {
            echo '<pre>';
            echo $script['text'];
            echo '</pre>';

        } elseif ($out == 'array') {
            return $script;
        }

        if (!$skipExit) {
            exit;
        }
    }

    public function test1(Request $request)
    {
        dd(__METHOD__, $request);
    }
    public function test2()
    {
        dd(__METHOD__);
    }
    public function test3()
    {
        dd(__METHOD__);
    }

    public function cache()
    {
        // Cache::flush();

        $cache['int-add'] = Cache::add('int', [1,2,3, 'name' => 'user', 'pass' => 'password']);
        $cache['int-add10'] = Cache::add('int-10', [4,5,6, 'name' => 'user', 'pass' => 'password'], 10);
        $cache['int-get'] = Cache::get('int');
        $cache['int-get10'] = Cache::get('int-10');

        dd($cache);


    }

}
