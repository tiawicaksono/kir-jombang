<?php

class RekomendasiController extends Controller
{

    public function actionIndex()
    {
        $this->pageTitle = 'REKAP REKOMENDASI';
        $this->render('index');
    }

    public function actionRekap($tgl, $pilihan)
    {
        if ($pilihan == "bulan") {
            $blnThn = date("n-Y", strtotime($tgl));
            $explodeBlnThn = explode('-', $blnThn);
            $bln = $explodeBlnThn[0];
            $thn = $explodeBlnThn[1];
            $criteria = new CDbCriteria();
            $criteria->addInCondition('bulan', array($bln));
            $criteria->addInCondition('tahun', array($thn));
            $result = VRekom::model()->findAll($criteria);
        } else {
            $criteria = new CDbCriteria();
            $criteria->addInCondition('tahun', array($tgl));
            $result = VRekom::model()->findAll($criteria);
        }

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
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        // $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setScale(82);
        //=============================================
        $sheet->mergeCells("A1:M1");
        $sheet->mergeCells("A2:M2");
        $sheet->mergeCells("A3:M3");
        $sheet->mergeCells("A4:M4");

        $sheet->setCellValue("A1", "JUMLAH REKOMENDASI MUTASI KELUAR, NUMPANG UJI KELUAR DAN UBAH BENTUK");
        $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR");
        $sheet->setCellValue("A3", "DINAS PERHUBUNGAN KABUPATEN SAMPANG, JAWA TIMUR");
        if ($pilihan == "bulan") {
            $sheet->setCellValue("A4", "TAHUN : " . $thn . " / BULAN : " . strtoupper(Yii::app()->params['bulanArrayInd'][$bln - 1]));
        } else {
            $sheet->setCellValue("A4", "TAHUN : " . $tgl);
        }

        $sheet->getStyle("A1")->applyFromArray($styleCenter);
        $sheet->getStyle("A2")->applyFromArray($styleCenter);
        $sheet->getStyle("A3")->applyFromArray($styleCenter);
        $sheet->getStyle("A4")->applyFromArray($styleCenter);
        //======================================================================
        //======================================================================
        //======================================================================
        /*
         * BODY HEADER
         */
        $sheet->mergeCells("A6:A7");
        $sheet->setCellValue("A6", "NO");
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("A")->setWidth(5);
        //=====================================================================================================
        $sheet->mergeCells("B6:B7");
        $sheet->setCellValue("B6", "TGL REKOM");
        $sheet->getStyle("B6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("B")->setWidth(15);
        //=====================================================================================================
        $sheet->mergeCells("C6:C7");
        $sheet->setCellValue("C6", "NO UJI");
        $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("C")->setWidth(15);
        //=====================================================================================================
        $sheet->mergeCells("D6:D7");
        $sheet->setCellValue("D6", "NO KENDARAAN");
        $sheet->getStyle("D6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("D")->setWidth(15);
        //=====================================================================================================
        $sheet->mergeCells("E6:E7");
        $sheet->setCellValue("E6", "JENIS REKOM");
        $sheet->getStyle("E6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("E")->setAutoSize(true);
        //=====================================================================================================
        $sheet->mergeCells("F6:J6");
        $sheet->setCellValue("F6", "MUTASI KELUAR");
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("F7", "NIK TUJUAN");
        $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("G7", "PEMILIK TUJUAN");
        $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("H7", "ALAMAT TUJUAN");
        $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("I7", "KOTA TUJUAN");
        $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("I")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("J7", "PROVINSI TUJUAN");
        $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("J")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("F")->setAutoSize(true);
        $sheet->getColumnDimension("G")->setAutoSize(true);
        $sheet->getColumnDimension("H")->setAutoSize(true);
        $sheet->getColumnDimension("I")->setAutoSize(true);
        $sheet->getColumnDimension("J")->setAutoSize(true);
        //=====================================================================================================
        $sheet->mergeCells("K6:L6");
        $sheet->setCellValue("K6", "NUMPANG UJI KELUAR");
        $sheet->getStyle("K6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("K6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("K7", "KOTA TUJUAN");
        $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("K")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("L7", "PROVINSI TUJUAN");
        $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("L")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("L")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("K")->setAutoSize(true);
        $sheet->getColumnDimension("L")->setAutoSize(true);
        //=====================================================================================================
        $sheet->setCellValue("M6", "UBAH BENTUK");
        $sheet->getStyle("M6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("M7", "KETERANGAN");
        $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("M")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("M")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("M")->setAutoSize(true);
        //=====================================================================================================
        $sheet->getStyle("A6:M7")->applyFromArray($styleCenter);
        $sheet->getStyle("A6:M7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //======================================================================

        /*
         * BODY 
         */
        $baris = 8;
        $no = 1;
        $dataKotaMutke = '';
        $dataProvinsiMutke = '';
        $dataKotaNumke = '';
        $dataProvinsiNumke = '';
        foreach ($result as $data) :
            /**
             * MUTASI KELUAR
             */
            $criteriaProvinsiMutke = new CDbCriteria();
            $criteriaProvinsiMutke->addInCondition('id_provinsi', array($data->id_provinsi_mutke));
            $resultProvinsiMutke = MProvinsi::model()->find($criteriaProvinsiMutke);
            if (!empty($resultProvinsiMutke)) {
                $dataProvinsiMutke = $resultProvinsiMutke->nama;
            }

            $criteriaKotaMutke = new CDbCriteria();
            $criteriaKotaMutke->addInCondition('id_kota', array($data->id_kota_mutke));
            $resultKotaMutke = MKota::model()->find($criteriaKotaMutke);
            if (!empty($resultKotaMutke)) {
                $dataKotaMutke = $resultKotaMutke->nama;
            }
            /**
             * NUMPANG KELUAR
             */
            $criteriaProvinsiNumke = new CDbCriteria();
            $criteriaProvinsiNumke->addInCondition('id_provinsi', array($data->id_provinsi_numke));
            $resultProvinsiNumke = MProvinsi::model()->find($criteriaProvinsiNumke);
            if (!empty($resultProvinsiNumke)) {
                $dataProvinsiNumke = $resultProvinsiNumke->nama;
            }

            $criteriaKotaNumke = new CDbCriteria();
            $criteriaKotaNumke->addInCondition('id_kota', array($data->id_kota_numke));
            $resultKotaNumke = MKota::model()->find($criteriaKotaNumke);
            if (!empty($resultKotaNumke)) {
                $dataKotaNumke = $resultKotaNumke->nama;
            }

            $sheet->setCellValue("A" . $baris, $no);
            $sheet->setCellValue("B" . $baris, $data->tgl_rekom);
            $sheet->setCellValue("C" . $baris, $data->no_uji);
            $sheet->setCellValue("D" . $baris, $data->no_kendaraan);
            $sheet->setCellValue("E" . $baris, $data->nm_uji);
            $sheet->setCellValue("F" . $baris, $data->nik_baru);
            $sheet->setCellValue("G" . $baris, $data->pemilik_baru);
            $sheet->setCellValue("H" . $baris, $data->alamat_baru);
            $sheet->setCellValue("I" . $baris, $dataKotaMutke);
            $sheet->setCellValue("J" . $baris, $dataProvinsiMutke);
            $sheet->setCellValue("K" . $baris, $dataKotaNumke);
            $sheet->setCellValue("L" . $baris, $dataProvinsiNumke);
            $sheet->setCellValue("M" . $baris, $data->ket_ubah_bentuk);
            $baris++;
            $no++;
        endforeach;
        $baris_border = $baris - 1;
        $sheet->getStyle("A6:M" . $baris_border)->applyFromArray($styleBorder);
        //=====================================================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        if ($pilihan == "bulan") {
            header('Content-Disposition: attachment;filename="Rekomendasi [' . $bln . '-' . $thn . '].xls"');
        } else {
            header('Content-Disposition: attachment;filename="Rekomendasi Tahun [' . $tgl . '].xls"');
        }

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
