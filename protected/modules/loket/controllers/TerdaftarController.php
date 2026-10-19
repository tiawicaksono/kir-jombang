<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TerdaftarController extends Controller {

    public function filters() {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }

    public function actionIndex() {
        $this->pageTitle = 'TERDAFTAR';
        $this->render('terdaftar');
    }

    public function actionProsesGantiTglUji() {
        $id_retribusi = $_POST['dlg_id_retribusi'];
        $ganti_tgl_uji = date("m/d/Y", strtotime($_POST['ganti_tgl_uji']));
        $updateRetribusi = "Select edit_retribusi(" . $id_retribusi . ",'" . $ganti_tgl_uji . "','tgluji',0,0,0)";
        Yii::app()->db->createCommand($updateRetribusi)->query();
    }

    public function actionGetListDataTerdaftar() {
//        $selectCategory = $_POST['selectCategory'];
        $textCategory = strtoupper($_POST['textCategory']);
        $selectDate = strtoupper($_POST['selectDate']);
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id_daftar';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;
        if (!empty($textCategory)) {
            $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('%" . $textCategory . "'),' ',''))");
        }
        $criteria->addCondition("tgl_uji = TO_DATE('" . $selectDate . "', 'DD-Mon-YY')");
        $result = VDaftar::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $p) {
            $dataJson[] = array(
                "id_retribusi" => $p->id_retribusi,
                "numerator" => $p->numerator,
                "no_uji" => $p->no_uji,
                "no_kendaraan" => $p->no_kendaraan,
                "nm_komersil" => $p->nm_komersil,
                "nama_pemilik" => $p->nama_pemilik,
                "nm_uji" => $p->nm_uji,
                "penerima" => strtoupper($p->penerima),
                "bahan_bakar" => $p->bahan_bakar,
                "sifat" => $p->sifat,
                "tglmati" => date("d F Y", strtotime($p->tglmati))
            );
        }

        header('Content-Type: application/json');
        echo CJSON::encode(
                array(
                    'total' => VDaftar::model()->count($criteria),
                    'rows' => $dataJson,
                )
        );
        Yii::app()->end();
    }

    public function actionReportTerdaftarExcel() {
        $selectDate = strtoupper($_GET['tgl']);
        $tglIndonesia = date("d", strtotime($selectDate)) . " " . strtoupper(Yii::app()->params['bulanArrayInd'][date("n", strtotime($selectDate)) - 1]) . " " . date("Y", strtotime($selectDate));

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $xls->getActiveSheet();
        $xls->setActiveSheetIndex(0);
//        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
//        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
//        $sheet->getPageSetup()->setFitToPage(false);
//        $sheet->getPageSetup()->setHorizontalCentered(true);
//        $sheet->getPageSetup()->setVerticalCentered(true);
//        $sheet->getPageSetup()->setScale(90);
        //======================================================================
        $styleTengah = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleTengahHorizontal = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        //======================================================================
        //HEADER
        $sheet->mergeCells("A1:J1");
        $sheet->setCellValue("A1", "DINAS PERHUBUNGAN");
        $sheet->getStyle("A1")->getFont()->setSize(16);
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A1")->getFont()->setBold(true);

        $sheet->mergeCells("A2:J2");
        $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR - KABUPATEN SAMPANG");
        $sheet->getStyle("A2")->getFont()->setSize(16);
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A2")->getFont()->setBold(true);

        $sheet->mergeCells("A3:J3");
        $sheet->setCellValue("A3", "LAPORAN KENDARAAN TERDAFTAR UJI");
        $sheet->getStyle("A3")->getFont()->setSize(12);
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A3")->getFont()->setBold(true);

        $sheet->mergeCells("A4:J4");
        $sheet->setCellValue("A4", $tglIndonesia);
        $sheet->getStyle("A4")->getFont()->setSize(12);
        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A4")->getFont()->setBold(true);

        $sheet->setCellValue("A6", "NO");
        $sheet->getStyle("A6")->applyFromArray($styleTengah);
        $sheet->getStyle("A")->applyFromArray($styleTengah);
        $sheet->getColumnDimension('A')->setAutoSize(true);

        $sheet->setCellValue("B6", "NO UJI");
        $sheet->getStyle("B6")->applyFromArray($styleTengah);
        $sheet->getStyle("B")->applyFromArray($styleTengahHorizontal);
        $sheet->getStyle("B")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
//        $sheet->getColumnDimension('B')->setWidth(14);

        $sheet->setCellValue("C6", "NO KENDARAAN");
        $sheet->getStyle("C6")->applyFromArray($styleTengah);
        $sheet->getStyle("C")->applyFromArray($styleTengahHorizontal);
        $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("C")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth(12);

        $sheet->setCellValue("D6", "NAMA PEMILIK");
        $sheet->getStyle("D6")->applyFromArray($styleTengah);
        $sheet->getStyle("D")->applyFromArray($styleTengahHorizontal);
        $sheet->getStyle("D")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('D')->setWidth(30);

        $sheet->setCellValue("E6", "JENIS KENDARAAN");
        $sheet->getStyle("E6")->applyFromArray($styleTengah);
        $sheet->getStyle("E")->applyFromArray($styleTengah);
        $sheet->getStyle("E6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("E")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('E')->setWidth(15);

        $sheet->setCellValue("F6", "NAMA KOMERSIL");
        $sheet->getStyle("F6")->applyFromArray($styleTengah);
        $sheet->getStyle("F")->applyFromArray($styleTengah);
        $sheet->getStyle("F6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("F")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('F')->setWidth(15);

        $sheet->setCellValue("G6", "JENIS KENDARAAN");
        $sheet->getStyle("G6")->applyFromArray($styleTengah);
        $sheet->getStyle("G")->applyFromArray($styleTengah);
        $sheet->getStyle("G6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("G")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('G')->setWidth(15);

        $sheet->setCellValue("H6", "SIFAT");
        $sheet->getStyle("H6")->applyFromArray($styleTengah);
        $sheet->getStyle("H")->applyFromArray($styleTengah);
        $sheet->getStyle("H6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("H")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('H')->setWidth(15);

        $sheet->setCellValue("I6", "BAHAN BAKAR");
        $sheet->getStyle("I6")->applyFromArray($styleTengah);
        $sheet->getStyle("I")->applyFromArray($styleTengah);
        $sheet->getStyle("I6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("I")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('I')->setWidth(15);

        $sheet->setCellValue("J6", "JENIS UJI");
        $sheet->getStyle("J6")->applyFromArray($styleTengah);
        $sheet->getStyle("J")->applyFromArray($styleTengah);
        $sheet->getStyle("J6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("J")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('J')->setWidth(15);

        $sheet->getStyle('A6:J6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================

        $criteria = new CDbCriteria();
        $criteria->addCondition("tgl_uji = TO_DATE('" . $selectDate . "', 'DD-Mon-YY')");
        $criteria->order = "no_uji asc";
        $result = VDaftar::model()->findAll($criteria);
        //======================================================================
        //BODY
        $no = 1;
        $baris = 7;
        foreach ($result as $data):
            $sheet->setCellValue("A" . $baris, $no);
            $sheet->setCellValue("B" . $baris, $data->no_uji);
            $sheet->setCellValue("C" . $baris, $data->no_kendaraan);
            $sheet->setCellValue("D" . $baris, $data->nama_pemilik);
            $sheet->setCellValue("E" . $baris, $data->jns_kend);
            $sheet->setCellValue("F" . $baris, $data->nm_komersil);
            $sheet->setCellValue("G" . $baris, $data->karoseri_jenis);
            $sheet->setCellValue("H" . $baris, $data->sifat);
            $sheet->setCellValue("I" . $baris, $data->bahan_bakar);
            $sheet->setCellValue("J" . $baris, $data->nm_uji);
//            $sheet->getRowDimension($baris)->setRowHeight(20);
            $baris++;
            $no++;
        endforeach;
        //END BODY
        //======================================================================
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );
        $baris_border = $baris - 1;
        $sheet->getStyle("A6:J" . $baris_border)->applyFromArray($styleArray);
        //======================================================================
        $sheet->getHeaderFooter()->setOddFooter('&R Page &P / &N');
        $sheet->getHeaderFooter()->setEvenFooter('&R Page &P / &N');
        //END FOOTER
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="TERDAFTAR_' . $tglIndonesia . '.xls"');
        header('Set-Cookie: fileDownload=true; path=/');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $xlsWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, 'Xls');
        $xlsWriter->save('php://output');
        Yii::app()->end();
    }

}
