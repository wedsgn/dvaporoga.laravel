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
  public static function writeDataToTable($item, $arreyIds, $requestMethod = null)
  {
      foreach ($arreyIds as $keyIds => $entityIds) {
          if (!isset($entityIds)) {
              continue;
          }
          foreach ($entityIds as $key => $value) {
              $entity = DB::table($keyIds)
                  ->where('slug', $value)
                  ->first();
              if (!$entity) {
                  continue;
              }
              $entityIds[$key] = $entity->id;
          }
          if (!method_exists($item, $keyIds)) {
              continue;
          }
              $item->{$keyIds}()->sync($entityIds);
      }
  }
}
