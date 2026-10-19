<?php

class PendapatanController extends Controller
{

    public function actionIndex()
    {
        $this->pageTitle = 'REKAP PENDAPATAN';
        $this->render('index');
    }

    public function actionRekap($tgl)
    {
        $blnThn = date("n-Y", strtotime($tgl));
        $explodeBlnThn = explode('-', $blnThn);
        $bln = $explodeBlnThn[0];
        $thn = $explodeBlnThn[1];

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        //======================================================================
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );
        //======================================================================
        $sheet = $xls->getActiveSheet();
        $xls->setActiveSheetIndex(0);
        $tglIndonesia = strtoupper(Yii::app()->params['bulanArrayInd'][date("n", strtotime($tgl)) - 1]) . " " . date("Y", strtotime($tgl));
        //======================================================================
        //HEADER
        $sheet->mergeCells("A1:H1");
        $sheet->setCellValue("A1", "DINAS PERHUBUNGAN");
        $sheet->getStyle("A1")->getFont()->setSize(16);
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A1")->getFont()->setBold(true);

        $sheet->mergeCells("A2:H2");
        $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR - KABUPATEN SAMPANG");
        $sheet->getStyle("A2")->getFont()->setSize(16);
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A2")->getFont()->setBold(true);

        $sheet->mergeCells("A3:H3");
        $sheet->setCellValue("A3", "DAFTAR PENERIMAAN UANG PENGUJIAN KENDARAAN BERMOTOR");
        $sheet->getStyle("A3")->getFont()->setSize(12);
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A3")->getFont()->setBold(true);

        $sheet->mergeCells("A4:H4");
        $sheet->setCellValue("A4", $tglIndonesia);
        $sheet->getStyle("A4")->getFont()->setSize(12);
        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A4")->getFont()->setBold(true);

        $sheet->mergeCells("A6:A7");
        $sheet->setCellValue("A6", "NO");
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("A")->setWidth(5);

        $sheet->mergeCells("B6:B7");
        $sheet->setCellValue("B6", "NO REKENING");
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("B")->setWidth(20);

        $sheet->mergeCells("C6:C7");
        $sheet->setCellValue("C6", "JENIS KEND / KEGIATAN");
        $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("C")->setWidth(20);

        $sheet->mergeCells("D6:D7");
        $sheet->setCellValue("D6", "URAIAN");
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("D")->setWidth(20);

        $sheet->mergeCells("E6:E7");
        $sheet->setCellValue("E6", "JUMLAH KEND");
        $sheet->getStyle("E6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->mergeCells("F6:F7");
        $sheet->setCellValue("F6", "BIAYA SAT KEGIATAN");
        $sheet->getStyle("F6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("F")->setWidth(10);

        $sheet->mergeCells("G6:G7");
        $sheet->setCellValue("G6", "JUMLAH BIAYA (Rp)");
        $sheet->getStyle("G6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("G")->setWidth(10);

        $sheet->mergeCells("H6:H7");
        $sheet->setCellValue("H6", "KET.");
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("H")->setWidth(10);

        $sheet->getRowDimension(6)->setRowHeight(30);
        $sheet->getRowDimension(7)->setRowHeight(30);

        $sheet->getStyle("A6:H7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================
        $kategori = ["MPU", "BUS", "PICK UP", "TRUCK", "GANDENGAN"];
        $uraian = array(
            "Uji & Ganti Kartu",
            "Uji & Tidak Ganti Kartu",
            "Numpang Uji",
            "Retribusi >= 1th",
            "Kartu Rusak",
            "Kartu Hilang",
            "Ganti Kartu",
            "Rekom"
        );
        //======================================================================
        //BODY
        $no = 1;
        $baris = 8;
        $total_result = 0;
        $total_biaya = 0;
        foreach ($kategori as $data) :
            $mergeKolom = $baris + 7;
            $sheet->mergeCells("A" . $baris . ":" . "A" . $mergeKolom);
            $sheet->mergeCells("B" . $baris . ":" . "B" . $mergeKolom);
            $sheet->mergeCells("C" . $baris . ":" . "C" . $mergeKolom);
            $sheet->setCellValue("A" . $baris, $no);
            $sheet->setCellValue("B" . $baris, "4.1.02.01.06");
            $sheet->setCellValue("C" . $baris, $data);
            $row_jumlah = $baris;
            foreach ($uraian as $key => $value) {
                /**
                 * BIAYA
                 */
                $biaya[4] = 25000;
                $biaya[5] = 200000;
                $biaya[6] = 25000;
                $biaya[7] = 50000;
                if ($data == "MPU") {
                    //uji & ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addNotInCondition("id_bk_masuk", array(1));
                    $result[0] = VValidasi::model()->count($criteria);
                    //uji & tidak ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addInCondition("id_bk_masuk", array(1));
                    $result[1] = VValidasi::model()->count($criteria);
                    //numpang uji
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(2));
                    $result[2] = VValidasi::model()->count($criteria);
                    //Retribusi >= 1th
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->select = 'SUM(tlt_retribusi) AS tlt_retribusi';
                    $result[3] = VValidasi::model()->find($criteria)->tlt_retribusi;
                    //Kartu Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_bk_masuk", array(4));
                    $result[4] = VValidasi::model()->count($criteria);
                    //Kartu Hilang
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_bk_masuk", array(3));
                    $result[5] = VValidasi::model()->count($criteria);
                    //Beli Kartu Hilang dan Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(10, 11));
                    $criteria->addInCondition("id_bk_masuk", array(3, 4));
                    $result[6] = VValidasi::model()->count($criteria);
                    //Rekom
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(3, 5, 7));
                    $result[7] = VValidasi::model()->count($criteria);

                    $biaya[0] = 110000;
                    $biaya[1] = 85000;
                    $biaya[2] = 110000;
                    $biaya[3] = 60000;
                } elseif ($data == "BUS") {
                    //uji & ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addNotInCondition("id_bk_masuk", array(1));
                    $result[0] = VValidasi::model()->count($criteria);
                    //uji & tidak ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addInCondition("id_bk_masuk", array(1));
                    $result[1] = VValidasi::model()->count($criteria);
                    //numpang uji
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(2));
                    $result[2] = VValidasi::model()->count($criteria);
                    //Retribusi >= 1th
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->select = 'SUM(tlt_retribusi) AS tlt_retribusi';
                    $result[3] = VValidasi::model()->find($criteria)->tlt_retribusi;
                    //Kartu Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_bk_masuk", array(4));
                    $result[4] = VValidasi::model()->count($criteria);
                    //Kartu Hilang
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_bk_masuk", array(3));
                    $result[5] = VValidasi::model()->count($criteria);
                    //Beli Kartu Hilang dan Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(10, 11));
                    $criteria->addInCondition("id_bk_masuk", array(3, 4));
                    $result[6] = VValidasi::model()->count($criteria);
                    //Rekom
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(3, 5, 7));
                    $result[7] = VValidasi::model()->count($criteria);

                    $biaya[0] = 130000;
                    $biaya[1] = 105000;
                    $biaya[2] = 130000;
                    $biaya[3] = 80000;
                } elseif ($data == "PICK UP") {
                    //uji & ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addNotInCondition("id_bk_masuk", array(1));
                    $result[0] = VValidasi::model()->count($criteria);
                    //uji & tidak ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addInCondition("id_bk_masuk", array(1));
                    $result[1] = VValidasi::model()->count($criteria);
                    //numpang uji
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(2));
                    $result[2] = VValidasi::model()->count($criteria);
                    //Retribusi >= 1th
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->select = 'SUM(tlt_retribusi) AS tlt_retribusi';
                    $result[3] = VValidasi::model()->find($criteria)->tlt_retribusi;
                    //Kartu Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_bk_masuk", array(4));
                    $result[4] = VValidasi::model()->count($criteria);
                    //Kartu Hilang
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_bk_masuk", array(3));
                    $result[5] = VValidasi::model()->count($criteria);
                    //Beli Kartu Hilang dan Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(10, 11));
                    $criteria->addInCondition("id_bk_masuk", array(3, 4));
                    $result[6] = VValidasi::model()->count($criteria);
                    //Rekom
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(3, 5, 7));
                    $result[7] = VValidasi::model()->count($criteria);

                    $biaya[0] = 110000;
                    $biaya[1] = 85000;
                    $biaya[2] = 110000;
                    $biaya[3] = 60000;
                } elseif ($data == "TRUCK") {
                    //uji & ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addNotInCondition("id_bk_masuk", array(1));
                    $result[0] = VValidasi::model()->count($criteria);
                    //uji & tidak ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addInCondition("id_bk_masuk", array(1));
                    $result[1] = VValidasi::model()->count($criteria);
                    //numpang uji
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(2));
                    $result[2] = VValidasi::model()->count($criteria);
                    //Retribusi >= 1th
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->select = 'SUM(tlt_retribusi) AS tlt_retribusi';
                    $result[3] = VValidasi::model()->find($criteria)->tlt_retribusi;
                    //Kartu Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_bk_masuk", array(4));
                    $result[4] = VValidasi::model()->count($criteria);
                    //Kartu Hilang
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_bk_masuk", array(3));
                    $result[5] = VValidasi::model()->count($criteria);
                    //Beli Kartu Hilang dan Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(10, 11));
                    $criteria->addInCondition("id_bk_masuk", array(3, 4));
                    $result[6] = VValidasi::model()->count($criteria);
                    //Rekom
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(3, 5, 7));
                    $result[7] = VValidasi::model()->count($criteria);

                    $biaya[0] = 130000;
                    $biaya[1] = 105000;
                    $biaya[2] = 130000;
                    $biaya[3] = 80000;
                } elseif ($data == "GANDENGAN") {
                    //uji & ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addNotInCondition("id_bk_masuk", array(1));
                    $result[0] = VValidasi::model()->count($criteria);
                    //uji & tidak ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addInCondition("id_bk_masuk", array(1));
                    $result[1] = VValidasi::model()->count($criteria);
                    //numpang uji
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_uji", array(2));
                    $result[2] = VValidasi::model()->count($criteria);
                    //Retribusi >= 1th
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->select = 'SUM(tlt_retribusi) AS tlt_retribusi';
                    $result[3] = VValidasi::model()->find($criteria)->tlt_retribusi;
                    //Kartu Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_bk_masuk", array(4));
                    $result[4] = VValidasi::model()->count($criteria);
                    //Kartu Hilang
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_bk_masuk", array(3));
                    $result[5] = VValidasi::model()->count($criteria);
                    //Beli Kartu Hilang dan Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_uji", array(10, 11));
                    $criteria->addInCondition("id_bk_masuk", array(3, 4));
                    $result[6] = VValidasi::model()->count($criteria);
                    //Rekom
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
                    $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_uji", array(3, 5, 7));
                    $result[7] = VValidasi::model()->count($criteria);

                    $biaya[0] = 170000;
                    $biaya[1] = 145000;
                    $biaya[2] = 170000;
                    $biaya[3] = 120000;
                }
                if ($key == 0 || $key == 1 || $key == 2) {
                    $total_result += $result[$key];
                }
                $kali_biaya = $result[$key] * $biaya[$key];
                $total_biaya += $kali_biaya;

                $sheet->setCellValue("D" . $baris, $value);
                $sheet->setCellValue("E" . $baris, $result[$key] == 0 ? '' : $result[$key]);
                $sheet->setCellValue("F" . $baris, $biaya[$key]);
                $sheet->setCellValue("G" . $baris, $kali_biaya);
                $sheet->setCellValue("H" . $baris, "");
                $sheet->getStyle("F$baris:G$baris")->getNumberFormat()->setFormatCode('#,##0');
                $baris++;
            }
            $sheet->mergeCells("A" . $baris . ":" . "D" . $baris);
            $sheet->setCellValue("A" . $baris, "JUMLAH");
            $mergeKolom5 = $mergeKolom - 5;
            $sheet->setCellValue("E" . $baris, "=SUM(E" . $row_jumlah . ":E" . $mergeKolom5 . ")");
            $sheet->setCellValue("G" . $baris, "=SUM(G" . $row_jumlah . ":G" . $mergeKolom . ")");
            $sheet->getStyle("G$baris")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("A" . $baris . ":H" . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');

            // $sheet->getStyle("G8:H" . $baris)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getRowDimension($baris)->setRowHeight(20);
            $baris++;
            $no++;
        endforeach;
        $baris_total = $baris + 1;
        $baris_denda = $baris_total + 2;
        $baris_total_keseluruhan = $baris_denda + 2;
        $sheet->mergeCells("A" . $baris_total . ":" . "D" . $baris_total);
        $sheet->setCellValue("A" . $baris_total, "JUMLAH SELURUHNYA");
        $sheet->setCellValue("E" . $baris_total, $total_result);
        $sheet->setCellValue("G" . $baris_total, $total_biaya);
        $sheet->getStyle("G$baris_total")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getRowDimension($baris_total)->setRowHeight(20);
        $sheet->getStyle("A" . $baris_total . ":H" . $baris_total)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //HITUNG DENDA
        $criteria = new CDbCriteria();
        $criteria->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
        $criteria->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
        $criteria->select = "sum(b_tlt_uji) as b_tlt_uji";
        $nilai_denda = VValidasi::model()->find($criteria);
        $sheet->setCellValue("A" . $baris_denda, "6");
        $sheet->setCellValue("B" . $baris_denda, "4.1.02.01.06");
        $sheet->mergeCells("C" . $baris_denda . ":" . "D" . $baris_denda);
        $sheet->getStyle("C" . $baris_denda)->getAlignment()->setWrapText(true);
        $sheet->setCellValue("C" . $baris_denda, "Pendapatan Denda Retribusi Pengujian Kendaraan Bermotor");
        $sheet->setCellValue("G" . $baris_denda, $nilai_denda->b_tlt_uji);
        $sheet->getRowDimension($baris_denda)->setRowHeight(40);
        $sheet->getStyle("G$baris_denda")->getNumberFormat()->setFormatCode('#,##0');
        //HITUNG TOTAL KESELURUHAN
        $sheet->setCellValue("A" . $baris_total_keseluruhan, "7");
        $sheet->mergeCells("B" . $baris_total_keseluruhan . ":" . "F" . $baris_total_keseluruhan);
        $sheet->setCellValue("B" . $baris_total_keseluruhan, "TOTAL KESELURUHAN");
        $sheet->setCellValue("G" . $baris_total_keseluruhan, $total_biaya + $nilai_denda->b_tlt_uji);
        $sheet->getRowDimension($baris_total_keseluruhan)->setRowHeight(40);
        $sheet->getStyle("G$baris_total_keseluruhan")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("A" . $baris_total_keseluruhan . ":H" . $baris_total_keseluruhan)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('B5D0B3');
        $sheet->getStyle("A6:H" . $baris_total_keseluruhan)->applyFromArray($styleArray);
        //END BODY
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="RETRIBUSI_' . $tglIndonesia . '.xls"');
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
