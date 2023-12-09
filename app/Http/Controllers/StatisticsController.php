<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
        public function top(){

            $data=DB::table('data')
                ->select('order', DB::raw('COUNT(`order`) as total_orders'))
                ->where('status','!=','null')
                ->groupBy('order')
                ->orderByDesc('total_orders')
                ->limit(30)
                ->get();

            return View::make('top',['data'=>$data]);
        }

        public function status(){
            $statusList=DB::table('data')
                ->select('status', DB::raw('COUNT(DISTINCT status) as status_count'))
                ->whereNotNull('status')
                ->groupBy('status')
                ->get();

            $dataJson=DB::table('data')
                ->select('status','format', DB::raw('COUNT(status) as status_count'))
                ->whereNotNull('status')
                ->where('format', '=', 'json')
                ->groupBy('status','format')
                ->get();

            $dataCsv=DB::table('data')
                ->select('status','format', DB::raw('COUNT(status) as status_count'))
                ->whereNotNull('status')
                ->where('format', '=', 'csv')
                ->groupBy('status','format')
                ->get();

            $dataLdif=DB::table('data')
                ->select('status','format', DB::raw('COUNT(status) as status_count'))
                ->whereNotNull('status')
                ->where('format', '=', 'ldif')
                ->groupBy('status','format')
                ->get();


            $data = [];

            for ($i = 0; $i < sizeof($statusList); $i++) {
                $item = new \stdClass();
                $jsonStatus = $dataJson[$i];
                $csvStatus = $dataCsv[$i];
                $ldifStatus = $dataLdif[$i];

                if ($jsonStatus->status_count >= $csvStatus->status_count && $jsonStatus->status_count >= $ldifStatus->status_count) {
                    $item->status = $jsonStatus->status;
                    $item->status_count = $jsonStatus->status_count;
                    $item->format = $jsonStatus->format;

                    if ($jsonStatus->status_count == $ldifStatus->status_count) {
                        $item->format .= ',' . $ldifStatus->format;
                    }

                    if ($jsonStatus->status_count == $csvStatus->status_count) {
                        $item->format .= ',' . $csvStatus->format;
                    }
                } elseif ($csvStatus->status_count > $jsonStatus->status_count && $csvStatus->status_count >= $ldifStatus->status_count) {
                    $item->status = $csvStatus->status;
                    $item->status_count = $csvStatus->status_count;
                    $item->format = $csvStatus->format;

                    if ($jsonStatus->status_count == $ldifStatus->status_count) {
                        $item->format .= ',' . $ldifStatus->format;
                    }

                    if ($jsonStatus->status_count == $csvStatus->status_count) {
                        $item->format .= ',' . $csvStatus->format;
                    }
                } else {
                    $item->status = $ldifStatus->status;
                    $item->status_count = $ldifStatus->status_count;
                    $item->format = $ldifStatus->format;

                    if ($jsonStatus->status_count == $ldifStatus->status_count) {
                        $item->format .= ',' . $ldifStatus->format;
                    }

                    if ($jsonStatus->status_count == $csvStatus->status_count) {
                        $item->format .= ',' . $csvStatus->format;
                    }
                }

                $data[] = $item;
            }





            return View::make('status',['data'=>$data]);

        }

        public function grup(){
            $data = DB::table('data')
                ->select( 'country', DB::raw('COUNT(country) as client_count'))
                ->groupBy('country')
                ->orderByDesc('client_count')
                ->get();

            return View::make('grup',['data'=>$data]);
        }


        public function consonants(){

            $tabel = DB::table('data')->get();

            $consonantCount = 0;

            foreach ($tabel as $customer) {
                $customerName = str_replace(' ', '', $customer->customer);
                $consonantCount += preg_match_all('/[bcdfghjklmnpqrstvwxyz]/i', $customerName);
            }


            return View::make('consonants',['data'=>$consonantCount]);
        }

}
