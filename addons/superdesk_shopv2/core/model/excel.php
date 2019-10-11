<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Excel_SuperdeskShopV2Model
{
    protected function column_str($key)
    {
        $array = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ', 'BA', 'BB', 'BC', 'BD', 'BE', 'BF', 'BG', 'BH', 'BI', 'BJ', 'BK', 'BL', 'BM', 'BN', 'BO', 'BP', 'BQ', 'BR', 'BS', 'BT', 'BU', 'BV', 'BW', 'BX', 'BY', 'BZ', 'CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ', 'EA', 'EB', 'EC', 'ED', 'EE', 'EF', 'EG', 'EH', 'EI', 'EJ', 'EK', 'EL', 'EM', 'EN', 'EO', 'EP', 'EQ', 'ER', 'ES', 'ET', 'EU', 'EV', 'EW', 'EX', 'EY', 'EZ');
        return $array[$key];
    }

    protected function column($key, $columnnum = 1)
    {
        return $this->column_str($key) . $columnnum;
    }

    public function export($list, $params = array(), $save2server = false)
    {
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }

        set_time_limit(0);
        ini_set("memory_limit", "1024M");  // 根据电脑配置不够继续增加

        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
//        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/CachedObjectStorageFactory.php';

        // cache_to_memcache start
//        $cacheMethod   = PHPExcel_CachedObjectStorageFactory::cache_to_memcache;
//        $cacheSettings = array(
//            'memcacheServer' => 'localhost',
//            'memcachePort'   => 11211,
//            'cacheTime'      => 1000 * 60
//
//        );
        // cache_to_memcache end

        // cache_to_phpTemp start
//        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
//        $cacheSettings = array(
//            'memoryCacheSize' => '800MB'
//        );
        // cache_to_phpTemp end

        // cache_in_memory_gzip start
        $cacheMethod   = PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip;
        $cacheSettings = [];
        // cache_in_memory_gzip end

        if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings)) {
            die($cacheMethod . " 缓存方法不可用" . PHP_EOL);
        }


        $excel = new PHPExcel();
        $excel->getProperties()
            ->setCreator('超级前台商城')
            ->setLastModifiedBy('超级前台商城')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('report file');

        $sheet = $excel->setActiveSheetIndex(0);

        $rownum = 1;

        foreach ($params['columns'] as $key => $column) {
            $sheet->setCellValue($this->column($key, $rownum), $column['title']);
            if (!empty($column['width'])) {
                $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
            }
        }
        ++$rownum;
        $len = count($params['columns']);
        foreach ($list as $row) {
            $i = 0;
            while ($i < $len) {
                $value = ((isset($row[$params['columns'][$i]['field']]) ? $row[$params['columns'][$i]['field']] : ''));

//                $sheet->setCellValue($this->column($i, $rownum), $value);// 修正 php在处理大数字时采用的科学计数法
                $sheet->setCellValueExplicit($this->column($i, $rownum), $value, PHPExcel_Cell_DataType::TYPE_STRING);

                ++$i;
            }
            ++$rownum;
        }
        $excel->getActiveSheet()->setTitle($params['title']);


        $filename = $params['title'] . '-' . date('Y-m-d_H_i', time());

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');

        if ($save2server) {

            $writer->save(ATTACHMENT_ROOT . $filename . '.xls');

            $callEndTime = microtime(true);
            $callTime = $callEndTime - STARTTIME;

//            echo date('H:i:s'), " 设置生成的文件为： ", str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)), PHP_EOL, '<br/>';
            echo date('H:i:s'), " 设置生成的文件为： ", ATTACHMENT_ROOT . $filename . '.xls', PHP_EOL, '<br/>';
            echo date('H:i:s'), ' 写入Workbook中耗时 ', sprintf('%.4f', $callTime), " 秒", PHP_EOL, '<br/>';
            echo date('H:i:s'), ' 当前内存使用情况: ', (memory_get_usage(true) / 1024 / 1024), " MB", PHP_EOL, '<br/>';
            echo date('H:i:s'), " 内存使用峰值: ", (memory_get_peak_usage(true) / 1024 / 1024), " MB", PHP_EOL, '<br/>';
            echo date('H:i:s'), " 完成写入文件", PHP_EOL, '<br/>';
//            echo date('H:i:s'), ' 文件被创建在： ', getcwd(), '目录', PHP_EOL, '<br/>';
            echo date('H:i:s'), ' 文件被创建在： ', ATTACHMENT_ROOT, '目录', PHP_EOL, '<br/>';


        } else {

            $filename = urlencode($filename);

            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
        }


        exit();
    }

    public function temp($title, $columns = array())
    {
        if (PHP_SAPI == 'cli') {
            exit('This example should only be run from a Web Browser');
        }
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
        $excel = new PHPExcel();

        $excel->getProperties()
            ->setCreator('超级前台商城')
            ->setLastModifiedBy('超级前台商城')
            ->setTitle('Office 2007 XLSX Test Document')
            ->setSubject('Office 2007 XLSX Test Document')
            ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
            ->setKeywords('office 2007 openxml php')
            ->setCategory('report file');

        $sheet  = $excel->setActiveSheetIndex(0);
        $rownum = 1;
        foreach ($columns as $key => $column) {
            $sheet->setCellValue($this->column($key, $rownum), $column['title']);
            if (!empty($column['width'])) {
                $sheet->getColumnDimension($this->column_str($key))->setWidth($column['width']);
            }
        }
        ++$rownum;
        $len = count($columns);
        $k   = 1;
        while ($k <= 5000) {
            $i = 0;
            while ($i < $len) {
                $sheet->setCellValue($this->column($i, $rownum), '');
                ++$i;
            }
            ++$rownum;
            ++$k;
        }

        $excel->getActiveSheet()->setTitle($title);

        $filename = urlencode($title);

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
        $writer->save('php://output');

        exit();
    }

    public function import($excefile)
    {
        global $_W;
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/IOFactory.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Reader/Excel5.php';
        $path = IA_ROOT . '/addons/superdesk_shop/data/tmp/';

        if (!is_dir($path)) {
            load()->func('file');
            mkdirs($path, '0777');
        }

        $filename = $_FILES[$excefile]['name'];
        $tmpname  = $_FILES[$excefile]['tmp_name'];
        if (empty($tmpname)) {
            message('请选择要上传的Excel文件!', '', 'error');
        }

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (($ext != 'xlsx') && ($ext != 'xls')) {
            message('请上传 xls 或 xlsx 格式的Excel文件!', '', 'error');
        }

        $file       = time() . $_W['uniacid'] . '.' . $ext;
        $uploadfile = $path . $file;
        $result     = move_uploaded_file($tmpname, $uploadfile);

        if (!$result) {
            message('上传Excel 文件失败, 请重新上传!', '', 'error');
        }

        $reader             = PHPExcel_IOFactory::createReader(($ext == 'xls' ? 'Excel5' : 'Excel2007'));
        $excel              = $reader->load($uploadfile);
        $sheet              = $excel->getActiveSheet();
        $highestRow         = $sheet->getHighestRow();
        $highestColumn      = $sheet->getHighestColumn();
        $highestColumnCount = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $values             = array();
        $row                = 2;

        while ($row <= $highestRow) {
            $rowValue = array();
            $col      = 0;

            while ($col < $highestColumnCount) {
                $rowValue[] = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                ++$col;
            }
            $values[] = $rowValue;
            ++$row;
        }
        return $values;
    }

    /**
     * @param        $excefile
     * @param string $plugin_names      插件名称.
     * @param string $diy_name          自定义文件名称
     * @param string $file_name_primary 文件名称标识
     *
     * @return array
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     */
    public function importByPath($excefile, $plugin_names = '', $edit_type = '', $diy_name = '', $file_name_primary = '')
    {
        global $_W;
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/IOFactory.php';
        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Reader/Excel5.php';
        $path = ATTACHMENT_ROOT . '/' . $plugin_names . '/excel/' . $edit_type . '/' . date('Y') . '/' . date('m') . '/' . date('d') . '/';

        if (!is_dir($path)) {
            load()->func('file');
            mkdirs($path, '0777');
        }

        $filename = $_FILES[$excefile]['name'];
        $tmpname  = $_FILES[$excefile]['tmp_name'];
        if (empty($tmpname)) {
            if($_W['isajax']){
                show_json(0,'请选择要上传的Excel文件!');
            }
            message('请选择要上传的Excel文件!', '', 'error');
        }

        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (($ext != 'xlsx') && ($ext != 'xls')) {
            if($_W['isajax']){
                show_json(0,'请上传 xls 或 xlsx 格式的Excel文件!');
            }
            message('请上传 xls 或 xlsx 格式的Excel文件!', '', 'error');
        }

        $file       = !empty($diy_name) ? $diy_name . '.' . $ext : time() . '_' . rand(1000, 9999) . '_' . $_W['uniacid'] . $file_name_primary . '.' . $ext;
        $uploadfile = $path . $file;
        $result     = move_uploaded_file($tmpname, $uploadfile);

        if (!$result) {
            if($_W['isajax']){
                show_json(0,'上传Excel 文件失败, 请重新上传!');
            }
            message('上传Excel 文件失败, 请重新上传!', '', 'error');
        }

        $reader             = PHPExcel_IOFactory::createReader(($ext == 'xls' ? 'Excel5' : 'Excel2007'));
        $excel              = $reader->load($uploadfile);
        $sheet              = $excel->getActiveSheet();
        $highestRow         = $sheet->getHighestRow();
        $highestColumn      = $sheet->getHighestColumn();
        $highestColumnCount = PHPExcel_Cell::columnIndexFromString($highestColumn);
        $values             = array();
        $row                = 2;

        while ($row <= $highestRow) {
            $rowValue = array();
            $col      = 0;

            while ($col < $highestColumnCount) {
                $rowValue[] = $sheet->getCellByColumnAndRow($col, $row)->getValue();
                ++$col;
            }
            $values[] = $rowValue;
            ++$row;
        }
        return $values;
    }
}