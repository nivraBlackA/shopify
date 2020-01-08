<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/** load the CI class for Modular Extensions **/
require dirname(__FILE__).'/Base.php';

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library replaces the CodeIgniter Controller class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Controller.php
 *
 * @copyright	Copyright (c) 2015 Wiredesignz
 * @version 	5.5
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 **/
class MX_Controller 
{
	public $autoload = array();
	
	var $template = 'default';
	var $section = array();
	var $data = array();
	
	public function __construct() 
	{
		$class = str_replace(CI::$APP->config->item('controller_suffix'), '', get_class($this));
		log_message('debug', $class." MX_Controller Initialized");
		Modules::$registry[strtolower($class)] = $this;	
		
		/* copy a loader instance and initialize */
		$this->load = clone load_class('Loader');
		$this->load->initialize($this);	
		$this->load->model("Site_model","sitex");
		/* autoload module items */
		$this->load->_autoloader($this->autoload);
		
		//custom start
		
		$currPage = implode('/', $this->uri->segments);
		$this->currentpage	= end($this->uri->segments);
		$active_ctrl = $this->router->fetch_class();
		$active_method = $this->router->fetch_method();
		/* autoload module items */
		$this->load->_autoloader($this->autoload);
		$this->user_data = $this->session->userdata("user_data");
		$this->user_log = $this->session->userdata("user_logged");


		if ($this->user_log AND $this->user_data AND $active_ctrl == 'login' AND empty($active_method))
			redirect(base_url());

		if ((empty($this->user_data) OR empty($this->user_log)) AND $active_ctrl != "login")
		{
			$uri = $this->uri->uri_string();
			redirect(base_url()."login?redirect=$uri");
		}

		$this->data['sidebar_count'] = $this->sitex->get_sidebar_count();
		
		$page_theme = $this->myview->get_theme();
		$this->data['theme_path'] = $page_theme;
		$this->data['theme_dir'] = $this->myview->get_theme_dir();
		$this->data['img_dir'] = $this->myview->get_theme_dir()."images/";
        $this->ci_css = $this->myview->get_theme_dir()."css/";
        $this->ci_js = $this->myview->get_theme_dir()."js/";
		$this->js_plugins = $this->myview->get_theme_dir()."plugins/";
        $this->module_js = $this->myview->get_theme_dir()."js/module/";
		
		$this->section['header'] = $this->load->view($page_theme. 'header',$this->data,true);
		$this->section['sidebar'] = $this->load->view($page_theme. 'sidebar',$this->data,true);
		$this->section['footer'] = $this->load->view($page_theme. 'footer',$this->data,true);

		//custom end
	}
	
	public function __get($class) 
	{
		return CI::$APP->$class;
	}

	public function download_excel($headers, $content,$filename,$with_footer = false){
		$this->load->library('excel');
		$objPHPExcel = new PHPExcel();

		$headlet = "A";
		foreach ($headers as $fields) {
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue($headlet.'1', $fields);
			$headlet++;
		}

		$let = "A";
		$count = 2;

		foreach ($content as $dVal) {

			foreach ($dVal as $cData) {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($let.$count, $cData);
				$let++;
			}

			$let = "A";
			$count++;

		}

		

		// Auto size columns for each worksheet
		foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {

			$objPHPExcel->setActiveSheetIndex($objPHPExcel->getIndex($worksheet));

			$sheet = $objPHPExcel->getActiveSheet();
			$cellIterator = $sheet->getRowIterator()->current()->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(true);
			/** @var PHPExcel_Cell $cell */
			foreach ($cellIterator as $cell) {
				$sheet->getColumnDimension($cell->getColumn())->setAutoSize(true);
			}
		}

		// apply header color 
		$objPHPExcel->getActiveSheet()->getStyle('A1:'.$headlet.'1')->applyFromArray(
			array(
				'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb' => 'FF0000'),
				),
				'font' => array(
					'color' => array('rgb' => 'FFFFFF'),
				)
			)
		);

		if ($with_footer){
			$last_row = $count - 1;
			$objPHPExcel->getActiveSheet()->getStyle("A$last_row:".$headlet.$last_row)->applyFromArray(
				array(
					'fill' => array(
						'type' => PHPExcel_Style_Fill::FILL_SOLID,
						'color' => array('rgb' => 'FF0000'),
					),
					'font' => array(
						'color' => array('rgb' => 'FFFFFF'),
					)
				)
			);
		}

		// $objPHPExcel->getActiveSheet()->setTitle("$filename");

		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		$d = date("YmdHis");
		header("Content-Disposition: attachment;filename=$filename.xls");
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
}