<?php

class BukuujiController extends Controller
{

    public function filters()
    {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }

    public function actionIndex()
    {
        $this->pageTitle = 'Report - Kartu Uji';
        $this->render('index');
    }

    public function actionRekap($tgl)
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

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $xls->getActiveSheet();
        $xls->setActiveSheetIndex(0);
        //======================================================================
        //HEADER
        $sheet->mergeCells("A1:H1");
        $sheet->setCellValue("A1", "PENGUJIAN KENDARAAN BERMOTOR");
        $sheet->getStyle("A1")->getFont()->setSize(16);
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A1")->getFont()->setBold(true);

        $sheet->mergeCells("A2:H2");
        $sheet->setCellValue("A2", "DINAS PERHUBUNGAN KABUPATEN SAMPANG");
        $sheet->getStyle("A2")->getFont()->setSize(16);
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A2")->getFont()->setBold(true);

        $sheet->mergeCells("A3:H3");
        $sheet->setCellValue("A3", "DAFTAR CETAK KARTU UJI");
        $sheet->getStyle("A3")->getFont()->setSize(12);
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A3")->getFont()->setBold(true);

        $sheet->mergeCells("A4:H4");
        $sheet->setCellValue("A4", $tglAwalIndonesia . " - " . $tglAkhirIndonesia);
        $sheet->getStyle("A4")->getFont()->setSize(12);
        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A4")->getFont()->setBold(true);

        $sheet->setCellValue("A6", "NO");
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("A")->setWidth(5);

        $sheet->setCellValue("B6", "NO SERI KARTU");
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B6")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension("B")->setWidth(20);

        $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
        $sheet->setCellValue("C6", "NO UJI");
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("C")->setWidth(20);

        $sheet->getStyle("D6")->getAlignment()->setWrapText(true);
        $sheet->setCellValue("D6", "NO KENDARAAN");
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("D")->setWidth(20);

        $sheet->setCellValue("E6", "NAMA");
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension("E")->setWidth(33);

        $sheet->setCellValue("F6", "ALAMAT");
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension("F")->setWidth(50);

        $sheet->setCellValue("G6", "TGL CETAK");
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension("G")->setWidth(10);

        $sheet->setCellValue("H6", "KETERANGAN CETAK");
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension("H")->setWidth(25);

        $sheet->getStyle("A6:H6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================

        $criteria = new CDbCriteria();
        $criteria->addCondition("to_date(tgluji, 'DDMMYYYY') >= TO_DATE('" . $tgl_awal_search . "', 'DDMMYYYY')");
        $criteria->addCondition("to_date(tgluji, 'DDMMYYYY') <= TO_DATE('" . $tgl_akhir_search . "', 'DDMMYYYY')");
        $result = Datapengujian::model()->findAll($criteria);
        //======================================================================
        //BODY        
        $no = 1;
        $baris = 7;
        $gb = 0;
        foreach ($result as $data) :
            switch ($data->statuspenerbitan) {
                case "1":
                    $keterangan = "Daftar Baru";
                    break;
                case "2":
                    $keterangan = "Perpanjangan";
                    break;
                case "3":
                    $keterangan = "Penggantian karena rusak";
                    break;
                case "4":
                    $keterangan = "Penggantian karena hilang";
                    break;
                case "5":
                    $keterangan = "Numpang uji (masuk)";
                    break;
                case "6":
                    $keterangan = "Mutasi (masuk)";
                    break;
                default:
                    $keterangan = "Perpanjangan";
            }
            $TGLPENGUJIAN = DateTime::createFromFormat('dmY', $data->tgluji);
            $TGL_PENGUJIAN = $TGLPENGUJIAN->format('d-m-Y');
            $sheet->setCellValue("A" . $baris, $no);
            $sheet->setCellValue("B" . $baris, $data->nokendalikartu);
            $sheet->setCellValue("C" . $baris, $data->nouji);
            $sheet->setCellValue("D" . $baris, $data->noregistrasikendaraan);
            $sheet->setCellValue("E" . $baris, $data->nama);
            $sheet->setCellValue("F" . $baris, $data->alamat);
            $sheet->setCellValue("G" . $baris, $TGL_PENGUJIAN);
            $sheet->setCellValue("H" . $baris, $keterangan);
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
        $sheet->getStyle("A6:H" . $baris_border)->applyFromArray($styleArray);
        //======================================================================

        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="REPORT-PEMAKAIAN KARTU UJI_' . $tglAwalIndonesia . '-' . $tglAkhirIndonesia . '.xls"');
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
