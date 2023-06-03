<?php
/**
 * Page helper
 * @author Athellow
 * @version 1.0
 */
namespace common\helpers;

use Yii;
use yii\data\Pagination;

class Page
{
    /**
     * 分页
     * @param  int    $totalCount   total number of items.
     * @param  int    $pageSize     number of items on each page.
     * @param  bool    $curPage
     * @return Pagination
     */
    public static function getPage($totalCount = 0, $pageSize = 10, $curPage = false)
    {
        $pagination = new Pagination();
        $pagination->totalCount = $totalCount;
        $pagination->pageSize = $pageSize;
        // $pagination->pageSizeParam = false;
        // $pagination->validatePage = false;
        
        if ($curPage !== false) {
            $pagination->setPage($curPage - 1, false);
        }
        
        return $pagination;
    }

}