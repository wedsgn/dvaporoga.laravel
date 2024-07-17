<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class FormatData
{
    public function cutArraysFromRequest($data, $array_keys)
    {
        foreach ($array_keys as $key) :
            $arreyIds[$key] = $data[$key] ?? null;
            unset($data[$key]);
        endforeach;
        return [
            'data' => $data,
            'arreyIds' => $arreyIds
        ];
    }
    public function changeTitleToId($data, $model, $key)
    {
        if (isset($data[$key])) :
            $data[$key] = $model::where('title', $data[$key])->first()->id;
        endif;
        return $data;
    }
    public function writeDataToTable($item, $arreyIds)
    {
        foreach ($arreyIds as $keyIds => $entityIds) :
            if (isset($entityIds)) :
                foreach ($entityIds as $key => $value) :
                    $entity_id = DB::table($keyIds)
                        ->where('title', $value)
                        ->first()->id;
                    $entityIds[$key] = $entity_id;
                endforeach;
                if ($keyIds == 'cars') :
                    $item->cars()->sync($entityIds);
                endif;
            else :
                if ($keyIds == 'cars') :
                    $item->cars()->sync($entityIds);
                endif;
            endif;
        endforeach;
    }
}
