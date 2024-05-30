<?php
declare(strict_types=1);

namespace Liuxinyang\HyperfAdmin\Model;

use Hyperf\DbConnection\Db;

class AdminStats extends Model
{

    protected ?string $table = 'admin_stats';

    protected string $primaryKey = 'id';

    protected array $fillable = ['id',  'uri', 'ip', 'province', 'city', 'created_at', 'updated_at'];

    protected array $casts = ['id' => 'integer'];

    public static function log($uri, $ip, $province, $city){
        return self::create(
            [
                'uri' => $uri,
                'ip' => $ip,
                'province' => $province,
                'city' => $city
            ]
        );
    }

    public static function getPieData(){
        $data = self::getCityData();
        $legends = [];
        $seriesData = [];
        foreach($data as $key => $val){
            $legends[] = $val->province.'('.$val->num.')';
            $seriesData[] = ['name' =>$val->province , 'value' =>$val->num ];
        }
        return compact('legends', 'seriesData');
    }

    public static function getHotUrlList(){
        return self::select('uri',Db::raw('count(*) as num'))
            ->groupBy('uri')
            ->orderBy(Db::raw('count(*)'), 'desc')
            ->limit(7)
            ->get()
            ->toArray();
    }

    public static function getCityData(){
        return self::where('province', '<>', '')->whereNotNull('city')->select('province',Db::raw('count(*) as num'))
            ->groupBy('province')
            ->get()
            ->toArray();
    }

    public static function getLineStatData(): array
    {
        $data1 = array_fill(0, 12, 0);
        $data2 = array_fill(0, 12, 0);

        for($i = 1; $i<=12; $i++){
            $data1[$i-1] = self::getMonthPvCount($i, 1);
            $data2[$i-1] = count(self::getMonthUvCount($i, 2));
        }

        return [$data1, $data2];
    }

    private static function getMonthPvCount($month){
        $startTime = date('Y').'-'.$month.'-01';
        $endTime = date('Y').'-'.$month.'-31';
        return self::whereBetween('created_at', [$startTime, $endTime])
            ->count();
    }

    private static function getMonthUvCount($month){
        $startTime = date('Y').'-'.$month.'-01';
        $endTime = date('Y').'-'.$month.'-31';
        return self::whereBetween('created_at', [$startTime, $endTime])
            ->groupBy('ip')
            ->select('ip')
            ->get()->toArray();
    }

}