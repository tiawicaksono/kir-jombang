<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PengujianController
 *
 * @author TIA.WICAKSONO
 */
class PengujianController extends Controller
{

    //put your code here    
    /**=====================================================================
     * REPORT UJI PERTAMA
    ======================================================================*/
    public function actionPageReportUjiPertama()
    {
        $this->pageTitle = 'Report - Uji Pertama';
        $this->render('index_uji_pertama');
    }

    public function actionExportExcelUjiPertama($tgl)
    {
        $tglExplode = explode('-', str_replace(' ', '', $tgl));
        $tglAwal = $tglExplode[0];
        $tgl_awal_search = DateTime::createFromFormat('d/m/Y', $tglAwal)->format('dmY');
        $tgl_awal = DateTime::createFromFormat('d/m/Y', $tglAwal)->format('m/d/Y');
        $tglAkhir = $tglExplode[1];
        $tgl_akhir_search = DateTime::createFromFormat('d/m/Y', $tglAkhir)->format('dmY');
        $tgl_akhir = DateTime::createFromFormat('d/m/Y', $tglAkhir)->format('m/d/Y');
        $tglAwalIndonesia = date("d", strtotime($tgl_awal)) . " " . strtoupper(Yii::app()->params['bulanArrayInd'][date("n", strtotime($tgl_awal)) - 1]) . " " . date("Y", strtotime($tgl_awal));
        $tglAkhirIndonesia = date("d", strtotime($tgl_akhir)) . " " . strtoupper(Yii::app()->params['bulanArrayInd'][date("n", strtotime($tgl_akhir)) - 1]) . " " . date("Y", strtotime($tgl_akhir));

        //======================================================================

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        //======================================================================
        $styleCenter = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            )
        );
        $styleVerticalCenter = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            )
        );
        $styleBorder = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );
        //======================================================================
        /*
         * HEADER TERDAFTAR
         */
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setScale(82);
        //=============================================
        $sheet->mergeCells("A1:K1");
        $sheet->mergeCells("A2:K2");
        $sheet->mergeCells("A3:K3");
        $sheet->mergeCells("A4:K4");

        $sheet->setCellValue("A1", "DAFTAR KENDARAAN UJI PERTAMA");
        $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR");
        $sheet->setCellValue("A3", "DINAS PERHUBUNGAN KABUPATEN SAMPANG, JAWA TIMUR");
        $sheet->setCellValue("A4", $tglAwalIndonesia . '-' . $tglAkhirIndonesia);

        $sheet->getStyle("A1")->applyFromArray($styleCenter);
        $sheet->getStyle("A2")->applyFromArray($styleCenter);
        $sheet->getStyle("A3")->applyFromArray($styleCenter);
        $sheet->getStyle("A4")->applyFromArray($styleCenter);
        //======================================================================
        //======================================================================
        //======================================================================
        //======================================================================
        $sheet->getRowDimension(6)->setRowHeight(40);
        $sheet->setCellValue("A6", "NO");
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("A")->setWidth(5);
        //=====================================================================================================
        $sheet->setCellValue("B6", "JENIS KEND");
        $sheet->getStyle("B6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("B")->setAutoSize(true);
        //=====================================================================================================
        $sheet->setCellValue("C6", "MERK / TIPE");
        $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("C")->setAutoSize(true);
        //=====================================================================================================
        $sheet->setCellValue("D6", "TAHUN");
        $sheet->getStyle("D6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("A")->setWidth(10);
        //=====================================================================================================
        $sheet->setCellValue("E6", "NO UJI");
        $sheet->getStyle("E6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("E")->setAutoSize(true);
        //=====================================================================================================
        $sheet->setCellValue("F6", "NO KEND");
        $sheet->getStyle("F6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("F")->setAutoSize(true);
        //=====================================================================================================
        $sheet->setCellValue("G6", "NO CHASIS");
        $sheet->getStyle("G6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("G")->setAutoSize(true);
        //=====================================================================================================
        $sheet->setCellValue("H6", "NO MESIN");
        $sheet->getStyle("H6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("H")->setAutoSize(true);
        //=====================================================================================================
        $sheet->setCellValue("I6", "NAMA PEMILIK");
        $sheet->getStyle("I6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("I6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("I6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("I")->setAutoSize(true);
        //=====================================================================================================
        $sheet->setCellValue("J6", "ALAMAT");
        $sheet->getStyle("J6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("J")->setAutoSize(true);
        //=====================================================================================================
        $sheet->setCellValue("K6", "STATUS");
        $sheet->getStyle("K6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("K6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("K6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("K")->setAutoSize(true);
        //=====================================================================================================

        $sheet->getStyle("A6:K6")->applyFromArray($styleCenter);
        $sheet->getStyle("A6:K6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================
        //======================================================================
        //BODY
        $criteria = new CDbCriteria();
        $criteria->addCondition("tgl_retribusi >= TO_DATE('" . $tglAwal . "', 'DD/MM/YY')");
        $criteria->addCondition("tgl_retribusi <= TO_DATE('" . $tglAkhir . "', 'DD/MM/YY')");
        $criteria->addCondition('id_uji = 8');
        $criteria->addCondition('datang = true');
        $criteria->addCondition('lulus = true');
        $result = VValidasi::model()->findAll($criteria);
        $no = 1;
        $baris = 7;
        foreach ($result as $data) :
            $sheet->setCellValue("A" . $baris, $no);
            $sheet->setCellValue("B" . $baris, $data->jenis);
            $sheet->setCellValue("C" . $baris, $data->merk . " - " . $data->nm_type);
            $sheet->setCellValue("D" . $baris, $data->tahun);
            $sheet->setCellValue("E" . $baris, $data->no_uji);
            $sheet->setCellValue("F" . $baris, $data->no_kendaraan);
            $sheet->setCellValue("G" . $baris, $data->no_chasis);
            $sheet->setCellValue("H" . $baris, $data->no_mesin);
            $sheet->setCellValue("I" . $baris, $data->nama_pemilik);
            $sheet->setCellValue("J" . $baris, $data->alamat);
            $sheet->setCellValue("K" . $baris, $data->umum == true ? 'UMUM' : 'TIDAK UMUM');
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
        $sheet->getStyle("A6:K" . $baris_border)->applyFromArray($styleArray);
        //=====================================================================
        ob_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="REPORT-UJI PERTAMA_' . $tglAwalIndonesia . '-' . $tglAkhirIndonesia . '.xls"');
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

    public function actionUjiPertamaListGrid()
    {
        $tgl = $_POST['tanggal'];
        $tglExplode = explode('-', str_replace(' ', '', $tgl));
        $tglAwal = $tglExplode[0];
        $tglAkhir = $tglExplode[1];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'no_uji';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;
        //        $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
        $criteria->addCondition("tgl_retribusi >= TO_DATE('" . $tglAwal . "', 'DD/MM/YY')");
        $criteria->addCondition("tgl_retribusi <= TO_DATE('" . $tglAkhir . "', 'DD/MM/YY')");
        $criteria->addCondition('id_uji = 8');
        $result = VValidasi::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $data) {
            $dataJson[] = array(
                "no_kendaraan" => $data->no_kendaraan,
                "no_uji" => $data->no_uji,
                "merk_tahun" => $data->merk . " / " . $data->tahun,
                "jenis" => $data->nm_komersil,
                "umum" => $data->umum == true ? 'v' : '-',
                "b_umum" => $data->umum == false ? 'v' : '-',
            );
        }
        header('Content-Type: application/json');
        echo CJSON::encode(
            array(
                'total' => VValidasi::model()->count($criteria),
                'rows' => $dataJson,
            )
        );
        Yii::app()->end();
    }

    /*     * =====================================================================
     * REPORT PEMAKAIAN BUKU UJI
      ====================================================================== */

    public function actionPageReportBukuUji()
    {
        $this->render('index_buku_uji');
    }

    public function actionExportExcelBukuUji()
    {
        $tgl = $_GET['tgl_report'];

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $xls->getActiveSheet();
        //======================================================================
        //HEADER
        $sheet->setCellValue("A1", "No.");
        $sheet->setCellValue("B1", "Nomor Kendaraan");
        $sheet->setCellValue("C1", "Nomor Uji");
        $sheet->setCellValue("D1", "Merk/Tahun");
        $sheet->setCellValue("E1", "Jenis");
        $sheet->setCellValue("F1", "Umum");
        $sheet->setCellValue("G1", "Bukan Umum");
        //END HEADER
        //======================================================================
        //======================================================================
        //BODY
        $dataKendaraan = TblBuku::model()->reportUjiPertama($tgl);
        $no = 1;
        $baris = 2;
        foreach ($dataKendaraan as $data) :
            $sheet->setCellValue("A" . $baris, $no);
            $sheet->setCellValue("B" . $baris, $data->no_kendaraan);
            $sheet->setCellValue("C" . $baris, $data->no_uji);
            $sheet->setCellValue("D" . $baris, $data->merk . " / " . $data->tahun);
            $sheet->setCellValue("E" . $baris, $data->nm_komersil);
            $sheet->setCellValue("F" . $baris, $data->umum == true ? 'v' : '-');
            $sheet->setCellValue("G" . $baris, $data->umum == false ? 'v' : '-');
            $baris++;
            $no++;
        endforeach;
        $dataCountKendaraan = TblBuku::model()->countReportUjiPertama($tgl);
        $lanjutBaris = $baris + 1;
        foreach ($dataCountKendaraan as $data) :
            $sheet->mergeCells("A" . $lanjutBaris . ":C" . $lanjutBaris);
            $sheet->setCellValue("A" . $lanjutBaris, $data->nm_komersil);
            $sheet->setCellValue("D" . $lanjutBaris, $data->jumlah);
            $lanjutBaris++;
        endforeach;
        //END BODY
        //======================================================================
        ob_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="export_tracking.xls"');
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

    public function actionTableBukuUji()
    {
        $tgl = $_POST['tanggal'];
        $this->renderPartial('table_buku_uji', array('tgl' => $tgl));
    }

    public function actionGrafikBukuUji()
    {
        $tgl = $_POST['tanggal'];
        $this->renderPartial('grafik_buku_uji', array('tgl' => $tgl));
    }
}
