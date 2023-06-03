<?php
/**
 * Export Helper
 * @author Athellow
 * @version 1.0
 */
namespace common\helpers;

use Yii;
use \common\tool\XLSXWriter;

class Export
{
    /**
	 * 导出
     * @param array $header 文件头字段数组
     * @param array $data 导出数据
     * @param string $filename 文件名
	 */
	public static function export($headers, $data, $filename = null)
	{
        $filename = empty($filename) ? 'export_'. date('YmdHis') .'.xlsx' : $filename;

        $models = [];
        $models[] = array_values($headers);
        
        foreach($data as $value) {
            $item = [];
            foreach($headers as $key => $v) {
                $item[$key] = empty($value[$key]) ? '' : $value[$key];
            }
            $models[] = $item;
        }

		$writer = new XLSXWriter();
		header('Content-disposition: attachment; filename="'.$writer->sanitize_filename($filename).'"');
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Transfer-Encoding: binary');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		
		$writer->setTempDir(Yii::getAlias('@frontend/runtime'));
		$writer->writeSheet($models);
	
		$writer->writeToStdOut();
		exit(0);
	}
    
}