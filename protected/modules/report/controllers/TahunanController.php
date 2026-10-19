<?php

class TahunanController extends Controller {

    public function filters() {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }

    /*
     * PENDAPATAN
     */

    public function actionIndexReportPendapatan() {
        $this->pageTitle = 'Report Pendapatan';
        $this->render('index_report_pendapatan');
    }
    
    public function actionReportPendapatan($thn) {

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $xls->getActiveSheet();
        $xls->setActiveSheetIndex(0);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(true);
        $sheet->getPageSetup()->setScale(90);
        //======================================================================
        $criteriaIndikator = new CDbCriteria();
//        $criteriaIndikator->addInCondition('bulan', array($bln));
        $criteriaIndikator->addInCondition('tahun', array($thn));
        $indikator = TblIndikator::model()->find($criteriaIndikator);
        //======================================================================
        //HEADER
        $sheet->mergeCells("A1:J1");
        $sheet->setCellValue("A1", "REKAPITULASI PENDAPATAN RETRIBUSI");
        $sheet->getStyle("A1")->getFont()->setSize(16);
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->mergeCells("A2:J2");
        $sheet->setCellValue("A2", "PENGUJIAN JENDARAAN BERMOTOR JBB > 3.500 KG");
        $sheet->getStyle("A2")->getFont()->setSize(16);
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->mergeCells("A3:J3");
        $sheet->setCellValue("A3", "UPTD PENGUJIAN KENDARAAN BERMOTOR SURABAYA DISHUB SURABAYA");
        $sheet->getStyle("A3")->getFont()->setSize(16);
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

//        $sheet->mergeCells("A4:E4");
//        $sheet->setCellValue("A4", "BULAN : ".date("F Y", strtotime($tgl)));
//        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
//        
        $sheet->mergeCells("F4:J4");
        $sheet->setCellValue("F4", "TARGET PAD ".$thn." : Rp.".number_format($indikator->target));
        $sheet->getStyle("F4")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("F4")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->mergeCells("A5:A6");
        $sheet->setCellValue("A5", "BULAN");
        $sheet->getStyle("A5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
//        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
//        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('A')->setWidth(10);

        $sheet->mergeCells("B5:C5");
        $sheet->setCellValue("B5", "RETRIBUSI");
        $sheet->getStyle("B5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("B6", "KEND");
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("C6", "NOMINAL (Rp)");
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(15);

        $sheet->mergeCells("D5:E5");
        $sheet->setCellValue("D5", "BUKU UJI");
        $sheet->getStyle("D5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("D6", "JUMLAH");
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("E6", "NOMINAL (Rp)");
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(15);

        $sheet->mergeCells("F5:H5");
        $sheet->setCellValue("F5", "DENDA");
        $sheet->getStyle("F5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("F6", "JUMLAH");
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("G6", "BLN");
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("H6", "NOMINAL (Rp)");
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(15);

        $sheet->mergeCells("I5:J5");
        $sheet->setCellValue("I5", "CAPAIAN");
        $sheet->getStyle("I5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("I5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("I6", "PENDAPATAN");
        $sheet->getStyle("I6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("I6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("I")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("J6", "(%)");
        $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("J")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(12);

        $sheet->getStyle('A5:J6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================

        //BODY
        $no = 1;
        $baris = 7;
        $totalKend = 0;$nominalKend=0;$totalBuku=0;$nominalBuku=0;$blnDenda=0;$nominalDenda=0;$nominalPendapatan=0;$totProses=0;$totKendDenda=0;
        for($bln = 1; $bln<=12; $bln++):
//        foreach ($result as $data):
            $criteria = new CDbCriteria();
            $criteria->select='SUM(b_daftar) as total_bdaftar,SUM(b_buku) as total_bbuku,SUM(b_denda) as total_bdenda';
            $criteria->addCondition("EXTRACT(YEAR FROM tgl_pad) =" . $thn);
            $criteria->addCondition("EXTRACT(MONTH FROM tgl_pad) =" . $bln);
            $data = TblLapPad::model()->find($criteria);
            //======================================================================
        
            $bDaftar = $data->total_bdaftar;
            $bBuku = $data->total_bbuku;
            $bDenda = $data->total_bdenda;
            $jmlRet = $data->total_bdaftar / 85000;
            $jmlBuku = $data->total_bbuku / 15000;
            $jmlDenda = $data->total_bdenda / 1700;
            $pendapatan = $bDaftar+$bBuku+$bDenda;
            $prosen = ($pendapatan / $indikator->target)*100;
            //TOTAL
            $totalKend += $jmlRet;
            $nominalKend += $bDaftar;
            $totalBuku += $jmlBuku;
            $nominalBuku += $bBuku;
            $blnDenda += $jmlDenda;
            $nominalDenda += $bDenda;
            $nominalPendapatan += $pendapatan;
            $totProses += $prosen;
            //DENDA KENDARAAN
//            $date = date("d", strtotime($data->tgl_pad));
//            $tanggal = $bln."/".$date."/".$thn;
            $criteriaDenda = new CDbCriteria();
            $criteriaDenda->addCondition("EXTRACT(YEAR FROM tgl_retribusi) =" . $thn);
            $criteriaDenda->addCondition("EXTRACT(MONTH FROM tgl_retribusi) =" . $bln);
//            $criteriaDenda->addCondition("tgl_retribusi = '$tanggal'");
            $criteriaDenda->addCondition('validasi = true');
            $criteriaDenda->addCondition('b_tlt_uji != 0');
            $result = VRetribusiAll::model()->count($criteriaDenda);
            $totKendDenda += $result;
            
            $monthName = date('F', mktime(0, 0, 0, $bln, 10)); 
            $sheet->setCellValue("A" . $baris, $monthName);
            $sheet->setCellValue("B" . $baris, $jmlRet);
            $sheet->setCellValue("C" . $baris, $bDaftar);
            $sheet->setCellValue("D" . $baris, $jmlBuku);
            $sheet->setCellValue("E" . $baris, $bBuku);
            $sheet->setCellValue("F" . $baris, $result);
            $sheet->setCellValue("G" . $baris, $jmlDenda);
            $sheet->setCellValue("H" . $baris, $bDenda);
            $sheet->setCellValue("I" . $baris, $pendapatan);
            $sheet->setCellValue("J" . $baris, $prosen);
//            $sheet->getRowDimension($baris)->setRowHeight(40);
            $baris++;
            $no++;
        endfor;
//        endforeach;
        //END BODY
        //======================================================================
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );
        $sheet->getStyle("A5:J".$baris)->applyFromArray($styleArray);
        $sheet->getStyle("C7:C".$baris)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("E7:E".$baris)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("H7:H".$baris)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("I7:I".$baris)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("J7:J".$baris)->getNumberFormat()->setFormatCode('#,##0.00');
        //======================================================================
        //FOOTER
        //TOTAL
        $sheet->setCellValue("A" . $baris, "TOTAL");
        $sheet->getStyle("A" . $baris)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("B" . $baris, $totalKend);
        $sheet->getStyle("B" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("C" . $baris, $nominalKend);
        $sheet->getStyle("C" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("D" . $baris, $totalBuku);
        $sheet->getStyle("D" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("E" . $baris, $nominalBuku);
        $sheet->getStyle("E" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("F" . $baris, $totKendDenda);
        $sheet->getStyle("F" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("G" . $baris, $blnDenda);
        $sheet->getStyle("G" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("H" . $baris, $nominalDenda);
        $sheet->getStyle("H" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("I" . $baris, $nominalPendapatan);
        $sheet->getStyle("I" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("J" . $baris, $totProses);
        $sheet->getStyle("J" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->getStyle("A".$baris.":J".$baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //======================================================================
        //TANDA TANGAN
        $kepala = $baris + 2;
        $sheet->mergeCells("F" . $kepala . ":J" . $kepala);
        $sheet->setCellValue("F" . $kepala, "KEPALA UPTD PKB SURABAYA");
        $sheet->getStyle("F" . $kepala)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));

        $nama = $kepala + 5;
        $sheet->mergeCells("F" . $nama . ":J" . $nama);
        $sheet->setCellValue("F" . $nama, "Abdul Manab, SH.");
        $sheet->getStyle("F" . $nama)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));

        $penata = $nama + 1;
        $sheet->mergeCells("F" . $penata . ":J" . $penata);
        $sheet->setCellValue("F" . $penata, "Penata");
        $sheet->getStyle("F" . $penata)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));

        $nip = $penata + 1;
        $sheet->mergeCells("F" . $nip . ":J" . $nip);
        $sheet->setCellValue("F" . $nip, "NIP. 19630402 198910 1 003");
        $sheet->getStyle("F" . $nip)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        //END FOOTER
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Pendapatan ['.$thn.'].xlsx"');
        header('Set-Cookie: fileDownload=true; path=/');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $xlsWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, 'Xlsx');
        $xlsWriter->save('php://output');
        Yii::app()->end();
    }
    
    /*
     * KELULUSAN KENDARAAN UJI
     */

    public function actionIndexReportKelulusan() {
        $this->pageTitle = 'Report Kelulusan Uji';
        $this->render('index_report_kelulusan');
    }

    public function actionReportKelulusan($thn) {

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //======================================================================
        $styleCenter = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleVerticalCenter = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleBorder = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
        ));
        //======================================================================
        /*
         * HEADER LULUS DAN TIDAK LULUS
         */
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('LULUS & TIDAK LULUS');
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setScale(82);
        //=============================================
        $sheet->getColumnDimension('A')->setWidth(11.56);
        $sheet->getColumnDimension('O')->setWidth(11.56);
        $sheet->mergeCells("A1:AA1");
        $sheet->mergeCells("A2:AA2");
        $sheet->mergeCells("A3:AA3");
        $sheet->mergeCells("A4:AA4");
        
        $sheet->setCellValue("A1", "JUMLAH KENDARAAN LULUS & TIDAK LULUS");
        $sheet->setCellValue("A2", "TAHUN ".$thn);
        $sheet->setCellValue("A3", "UPTD PENGUJIAN KENDARAAN BERMOTOR SURABAYA");
        $sheet->setCellValue("A4", "DINAS PERHUBUNGAN KOTA SURABAYA");
        
        $sheet->getStyle("A1")->applyFromArray($styleCenter);
        $sheet->getStyle("A2")->applyFromArray($styleCenter);
        $sheet->getStyle("A3")->applyFromArray($styleCenter);
        $sheet->getStyle("A4")->applyFromArray($styleCenter);
        /*
         * BODY HEADER LULUS
         */
        $sheet->mergeCells("B6:J6");
        $sheet->mergeCells("A6:A8");
        $sheet->mergeCells("B7:D7");
        $sheet->mergeCells("E7:G7");
        $sheet->mergeCells("H7:J7");
        $sheet->mergeCells("K6:M7");
        $sheet->setCellValue("B6", "LULUS");
        $sheet->setCellValue("A6", "Bulan");
        $sheet->setCellValue("B7", "Mobil Barang");
        $sheet->setCellValue("B8", "Umum");
        $sheet->setCellValue("C8", "BU");
        $sheet->setCellValue("D8", "Total");
        $sheet->setCellValue("E7", "Mobil Penumpang");
        $sheet->setCellValue("E8", "Umum");
        $sheet->setCellValue("F8", "BU");
        $sheet->setCellValue("G8", "Total");
        $sheet->setCellValue("H7", "Mobil Bus");
        $sheet->setCellValue("H8", "Umum");
        $sheet->setCellValue("I8", "BU");
        $sheet->setCellValue("J8", "Total");
        $sheet->setCellValue("K6", "TOTAL KESELURUHAN");
        $sheet->setCellValue("K8", "Umum");
        $sheet->setCellValue("L8", "BU");
        $sheet->setCellValue("M8", "Total");
        $sheet->getStyle("A6:M8")->applyFromArray($styleCenter);
        $sheet->getStyle('A6:M8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        /*
         * BODY LULUS
         */
        $baris = 9;
        $baris_awal = $baris;
        $baris_akhir = $baris_awal+11;
        
        for ($bln = 1; $bln <= 12; $bln++):
            $dataKendaraan = VLapKendaraanBaru::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $thn));
            
            $sheet->setCellValue("A" . $baris, Yii::app()->params['bulanArrayInd'][$bln - 1]);
            $sheet->setCellValue("B" . $baris, $dataKendaraan['lls_mbrg_umum']);
            $sheet->setCellValue("C" . $baris, '=D'.$baris.'-B'.$baris);
            $sheet->setCellValue("D" . $baris, $dataKendaraan['lls_mbrg']);
            $sheet->setCellValue("E" . $baris, $dataKendaraan['lls_mpnp_umum']);
            $sheet->setCellValue("F" . $baris, '=G'.$baris.'-E'.$baris);
            $sheet->setCellValue("G" . $baris, $dataKendaraan['lls_mpnp']);
            $sheet->setCellValue("H" . $baris, $dataKendaraan['lls_mbus_umum']);
            $sheet->setCellValue("I" . $baris, '=J'.$baris.'-H'.$baris);
            $sheet->setCellValue("J" . $baris, $dataKendaraan['lls_mbus']);
            $sheet->setCellValue("K" . $baris, '=SUM(B'.$baris.',E'.$baris.',H'.$baris.')');
            $sheet->setCellValue("L" . $baris, '=SUM(C'.$baris.',F'.$baris.',I'.$baris.')');
            $sheet->setCellValue("M" . $baris, '=SUM(K'.$baris.':L'.$baris.')');
            $baris++;
        endfor;
        
        $sheet->setCellValue("A" . $baris, "TOTAL");
        $sheet->setCellValue("B" . $baris, "=SUM(B".$baris_awal.":B".$baris_akhir.")");
        $sheet->setCellValue("C" . $baris, "=SUM(C".$baris_awal.":C".$baris_akhir.")");
        $sheet->setCellValue("D" . $baris, "=SUM(D".$baris_awal.":D".$baris_akhir.")");
        $sheet->setCellValue("E" . $baris, "=SUM(E".$baris_awal.":E".$baris_akhir.")");
        $sheet->setCellValue("F" . $baris, "=SUM(F".$baris_awal.":F".$baris_akhir.")");
        $sheet->setCellValue("G" . $baris, "=SUM(G".$baris_awal.":G".$baris_akhir.")");
        $sheet->setCellValue("H" . $baris, "=SUM(H".$baris_awal.":H".$baris_akhir.")");
        $sheet->setCellValue("I" . $baris, "=SUM(I".$baris_awal.":I".$baris_akhir.")");
        $sheet->setCellValue("J" . $baris, "=SUM(J".$baris_awal.":J".$baris_akhir.")");
        $sheet->setCellValue("K" . $baris, "=SUM(K".$baris_awal.":K".$baris_akhir.")");
        $sheet->setCellValue("L" . $baris, "=SUM(L".$baris_awal.":L".$baris_akhir.")");
        $sheet->setCellValue("M" . $baris, "=SUM(M".$baris_awal.":M".$baris_akhir.")");
        $sheet->getStyle('A' . $baris . ':M' . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        $sheet->getStyle("A6:M" . $baris)->applyFromArray($styleBorder);
        //======================================================================
        /*
         * BODY HEADER TIDAK LULUS
         */
        $sheet->mergeCells("P6:X6");
        $sheet->mergeCells("O6:O8");
        $sheet->mergeCells("P7:R7");
        $sheet->mergeCells("S7:U7");
        $sheet->mergeCells("V7:X7");
        $sheet->mergeCells("Y6:AA7");
        $sheet->setCellValue("P6", "TIDAK LULUS");
        $sheet->setCellValue("O6", "Bulan");
        $sheet->setCellValue("P7", "Mobil Barang");
        $sheet->setCellValue("P8", "Umum");
        $sheet->setCellValue("Q8", "BU");
        $sheet->setCellValue("R8", "Total");
        $sheet->setCellValue("S7", "Mobil Penumpang");
        $sheet->setCellValue("S8", "Umum");
        $sheet->setCellValue("T8", "BU");
        $sheet->setCellValue("U8", "Total");
        $sheet->setCellValue("V7", "Mobil Bus");
        $sheet->setCellValue("V8", "Umum");
        $sheet->setCellValue("W8", "BU");
        $sheet->setCellValue("X8", "Total");
        $sheet->setCellValue("Y6", "TOTAL KESELURUHAN");
        $sheet->setCellValue("Y8", "Umum");
        $sheet->setCellValue("Z8", "BU");
        $sheet->setCellValue("AA8", "Total");
        $sheet->getStyle("O6:AA8")->applyFromArray($styleCenter);
        $sheet->getStyle('O6:AA8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        /*
         * BODY TIDAK LULUS
         */
        $baris = 9;
        $baris_awal = $baris;
        $baris_akhir = $baris_awal+11;
        
        for ($bln = 1; $bln <= 12; $bln++):
            $dataKendaraan = VLapKendaraanBaru::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $thn));
            
            $sheet->setCellValue("O" . $baris, Yii::app()->params['bulanArrayInd'][$bln - 1]);
            $sheet->setCellValue("P" . $baris, $dataKendaraan['tl_mbrg_umum']);
            $sheet->setCellValue("Q" . $baris, '=R'.$baris.'-P'.$baris);
            $sheet->setCellValue("R" . $baris, $dataKendaraan['tl_mbrg']);
            $sheet->setCellValue("S" . $baris, $dataKendaraan['tl_mpnp_umum']);
            $sheet->setCellValue("T" . $baris, '=U'.$baris.'-S'.$baris);
            $sheet->setCellValue("U" . $baris, $dataKendaraan['tl_mpnp']);
            $sheet->setCellValue("V" . $baris, $dataKendaraan['tl_mbus_umum']);
            $sheet->setCellValue("W" . $baris, '=X'.$baris.'-V'.$baris);
            $sheet->setCellValue("X" . $baris, $dataKendaraan['tl_mbus']);
            $sheet->setCellValue("Y" . $baris, '=SUM(P'.$baris.',S'.$baris.',V'.$baris.')');
            $sheet->setCellValue("Z" . $baris, '=SUM(Q'.$baris.',T'.$baris.',W'.$baris.')');
            $sheet->setCellValue("AA" . $baris, '=SUM(Y'.$baris.':Z'.$baris.')');
            $baris++;
        endfor;
        
        $sheet->setCellValue("O" . $baris, "TOTAL");
        $sheet->setCellValue("P" . $baris, "=SUM(P".$baris_awal.":P".$baris_akhir.")");
        $sheet->setCellValue("Q" . $baris, "=SUM(Q".$baris_awal.":Q".$baris_akhir.")");
        $sheet->setCellValue("R" . $baris, "=SUM(R".$baris_awal.":R".$baris_akhir.")");
        $sheet->setCellValue("S" . $baris, "=SUM(S".$baris_awal.":S".$baris_akhir.")");
        $sheet->setCellValue("T" . $baris, "=SUM(T".$baris_awal.":T".$baris_akhir.")");
        $sheet->setCellValue("U" . $baris, "=SUM(U".$baris_awal.":U".$baris_akhir.")");
        $sheet->setCellValue("V" . $baris, "=SUM(V".$baris_awal.":V".$baris_akhir.")");
        $sheet->setCellValue("W" . $baris, "=SUM(W".$baris_awal.":W".$baris_akhir.")");
        $sheet->setCellValue("X" . $baris, "=SUM(X".$baris_awal.":X".$baris_akhir.")");
        $sheet->setCellValue("Y" . $baris, "=SUM(Y".$baris_awal.":Y".$baris_akhir.")");
        $sheet->setCellValue("Z" . $baris, "=SUM(Z".$baris_awal.":Z".$baris_akhir.")");
        $sheet->setCellValue("AA" . $baris, "=SUM(AA".$baris_awal.":AA".$baris_akhir.")");
        $sheet->getStyle('O' . $baris . ':AA' . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        $sheet->getStyle("O6:AA" . $baris)->applyFromArray($styleBorder);
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="report_kelulusan_'.$thn.'.xlsx"');
        header('Set-Cookie: fileDownload=true; path=/');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $xlsWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, 'Xlsx');
        $xlsWriter->save('php://output');
        Yii::app()->end();
    }
    
    /*
     * PENGUJIAN KENDARAAN 
     */
    
    public function actionIndexReportUjiKendaraan() {
        $this->pageTitle = 'Report Uji Kendaraan';
        $this->render('index_report_uji_kendaraan');
    }
    
    public function actionReportUjiKendaraan($thn) {

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //======================================================================
        $styleCenter = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleVerticalCenter = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleBorder = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
            )
        ));
        //======================================================================
        /*
         * HEADER UJI PERTAMA DAN BERKALA
         */
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('UJI PERTAMA & BERKALA');
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
//        $sheet->getPageSetup()->setVerticalCentered(true);
        $sheet->getPageSetup()->setScale(82);
        //=============================================
        $sheet->getColumnDimension('A')->setWidth(11.56);
        $sheet->getColumnDimension('O')->setWidth(11.56);
        $sheet->mergeCells("A1:AA1");
        $sheet->mergeCells("A2:AA2");
        $sheet->mergeCells("A3:AA3");
        $sheet->mergeCells("A4:AA4");
        
        $sheet->setCellValue("A1", "JUMLAH KENDARAAN YANG MELAKUKAN PENDAFTARAN UJI");
        $sheet->setCellValue("A2", "TAHUN ".$thn);
        $sheet->setCellValue("A3", "UPTD PENGUJIAN KENDARAAN BERMOTOR");
        $sheet->setCellValue("A4", "DINAS PERHUBUNGAN KAB. KOTAWARINGIN TIMUR");
        
        $sheet->getStyle("A1")->applyFromArray($styleCenter);
        $sheet->getStyle("A2")->applyFromArray($styleCenter);
        $sheet->getStyle("A3")->applyFromArray($styleCenter);
        $sheet->getStyle("A4")->applyFromArray($styleCenter);
        /*
         * BODY HEADER UJI PERTAMA
         */
        $sheet->mergeCells("B6:J6");
        $sheet->mergeCells("A6:A8");
        $sheet->mergeCells("B7:D7");
        $sheet->mergeCells("E7:G7");
        $sheet->mergeCells("H7:J7");
        $sheet->mergeCells("K6:M7");
        $sheet->setCellValue("B6", "UJI PERTAMA");
        $sheet->setCellValue("A6", "Bulan");
        $sheet->setCellValue("B7", "Mobil Barang");
        $sheet->setCellValue("B8", "Umum");
        $sheet->setCellValue("C8", "BU");
        $sheet->setCellValue("D8", "Total");
        $sheet->setCellValue("E7", "Mobil Penumpang");
        $sheet->setCellValue("E8", "Umum");
        $sheet->setCellValue("F8", "BU");
        $sheet->setCellValue("G8", "Total");
        $sheet->setCellValue("H7", "Mobil Bus");
        $sheet->setCellValue("H8", "Umum");
        $sheet->setCellValue("I8", "BU");
        $sheet->setCellValue("J8", "Total");
        $sheet->setCellValue("K6", "TOTAL KESELURUHAN");
        $sheet->setCellValue("K8", "Umum");
        $sheet->setCellValue("L8", "BU");
        $sheet->setCellValue("M8", "Total");
        $sheet->getStyle("A6:M8")->applyFromArray($styleCenter);
        $sheet->getStyle('A6:M8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        /*
         * BODY UJI PERTAMA
         */
        $baris = 9;
        $baris_awal = $baris;
        $baris_akhir = $baris_awal+11;
        
        for ($bln = 1; $bln <= 12; $bln++):
            $dataKendaraan = VLapKendaraanBaru::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $thn));
            
            $sheet->setCellValue("A" . $baris, Yii::app()->params['bulanArrayInd'][$bln - 1]);
            $sheet->setCellValue("B" . $baris, $dataKendaraan['ujibaru_mbrg_umum']);
            $sheet->setCellValue("C" . $baris, '=D'.$baris.'-B'.$baris);
            $sheet->setCellValue("D" . $baris, $dataKendaraan['ujibaru_mbrg']);
            $sheet->setCellValue("E" . $baris, $dataKendaraan['ujibaru_mpnp_umum']);
            $sheet->setCellValue("F" . $baris, '=G'.$baris.'-E'.$baris);
            $sheet->setCellValue("G" . $baris, $dataKendaraan['ujibaru_mpnp']);
            $sheet->setCellValue("H" . $baris, $dataKendaraan['ujibaru_mbbus_umum']);
            $sheet->setCellValue("I" . $baris, '=J'.$baris.'-H'.$baris);
            $sheet->setCellValue("J" . $baris, $dataKendaraan['ujibaru_mbus']);
            $sheet->setCellValue("K" . $baris, '=SUM(B'.$baris.',E'.$baris.',H'.$baris.')');
            $sheet->setCellValue("L" . $baris, '=SUM(C'.$baris.',F'.$baris.',I'.$baris.')');
            $sheet->setCellValue("M" . $baris, '=SUM(K'.$baris.':L'.$baris.')');
            $baris++;
        endfor;
        
        $sheet->setCellValue("A" . $baris, "TOTAL");
        $sheet->setCellValue("B" . $baris, "=SUM(B".$baris_awal.":B".$baris_akhir.")");
        $sheet->setCellValue("C" . $baris, "=SUM(C".$baris_awal.":C".$baris_akhir.")");
        $sheet->setCellValue("D" . $baris, "=SUM(D".$baris_awal.":D".$baris_akhir.")");
        $sheet->setCellValue("E" . $baris, "=SUM(E".$baris_awal.":E".$baris_akhir.")");
        $sheet->setCellValue("F" . $baris, "=SUM(F".$baris_awal.":F".$baris_akhir.")");
        $sheet->setCellValue("G" . $baris, "=SUM(G".$baris_awal.":G".$baris_akhir.")");
        $sheet->setCellValue("H" . $baris, "=SUM(H".$baris_awal.":H".$baris_akhir.")");
        $sheet->setCellValue("I" . $baris, "=SUM(I".$baris_awal.":I".$baris_akhir.")");
        $sheet->setCellValue("J" . $baris, "=SUM(J".$baris_awal.":J".$baris_akhir.")");
        $sheet->setCellValue("K" . $baris, "=SUM(K".$baris_awal.":K".$baris_akhir.")");
        $sheet->setCellValue("L" . $baris, "=SUM(L".$baris_awal.":L".$baris_akhir.")");
        $sheet->setCellValue("M" . $baris, "=SUM(M".$baris_awal.":M".$baris_akhir.")");
        $sheet->getStyle('A' . $baris . ':M' . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        $sheet->getStyle("A6:M" . $baris)->applyFromArray($styleBorder);
        //======================================================================
        /*
         * BODY HEADER UJI BERKALA
         */
        $sheet->mergeCells("P6:X6");
        $sheet->mergeCells("O6:O8");
        $sheet->mergeCells("P7:R7");
        $sheet->mergeCells("S7:U7");
        $sheet->mergeCells("V7:X7");
        $sheet->mergeCells("Y6:AA7");
        $sheet->setCellValue("P6", "UJI BERKALA");
        $sheet->setCellValue("O6", "Bulan");
        $sheet->setCellValue("P7", "Mobil Barang");
        $sheet->setCellValue("P8", "Umum");
        $sheet->setCellValue("Q8", "BU");
        $sheet->setCellValue("R8", "Total");
        $sheet->setCellValue("S7", "Mobil Penumpang");
        $sheet->setCellValue("S8", "Umum");
        $sheet->setCellValue("T8", "BU");
        $sheet->setCellValue("U8", "Total");
        $sheet->setCellValue("V7", "Mobil Bus");
        $sheet->setCellValue("V8", "Umum");
        $sheet->setCellValue("W8", "BU");
        $sheet->setCellValue("X8", "Total");
        $sheet->setCellValue("Y6", "TOTAL KESELURUHAN");
        $sheet->setCellValue("Y8", "Umum");
        $sheet->setCellValue("Z8", "BU");
        $sheet->setCellValue("AA8", "Total");
        $sheet->getStyle("O6:AA8")->applyFromArray($styleCenter);
        $sheet->getStyle('O6:AA8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        /*
         * BODY UJI BERKALA
         */
        $baris = 9;
        $baris_awal = $baris;
        $baris_akhir = $baris_awal+11;
        
        for ($bln = 1; $bln <= 12; $bln++):
            $dataKendaraan = VLapKendaraanBaru::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $thn));
            
            $sheet->setCellValue("O" . $baris, Yii::app()->params['bulanArrayInd'][$bln - 1]);
            $sheet->setCellValue("P" . $baris, $dataKendaraan['brkl_mbrg_umum']);
            $sheet->setCellValue("Q" . $baris, '=R'.$baris.'-P'.$baris);
            $sheet->setCellValue("R" . $baris, $dataKendaraan['brkl_mbrg']);
            $sheet->setCellValue("S" . $baris, $dataKendaraan['brkl_mpnp_umum']);
            $sheet->setCellValue("T" . $baris, '=U'.$baris.'-S'.$baris);
            $sheet->setCellValue("U" . $baris, $dataKendaraan['brkl_mpnp']);
            $sheet->setCellValue("V" . $baris, $dataKendaraan['brkl_mbus_umum']);
            $sheet->setCellValue("W" . $baris, '=X'.$baris.'-V'.$baris);
            $sheet->setCellValue("X" . $baris, $dataKendaraan['brkl_mbus']);
            $sheet->setCellValue("Y" . $baris, '=SUM(P'.$baris.',S'.$baris.',V'.$baris.')');
            $sheet->setCellValue("Z" . $baris, '=SUM(Q'.$baris.',T'.$baris.',W'.$baris.')');
            $sheet->setCellValue("AA" . $baris, '=SUM(Y'.$baris.':Z'.$baris.')');
            $baris++;
        endfor;
        
        $sheet->setCellValue("O" . $baris, "TOTAL");
        $sheet->setCellValue("P" . $baris, "=SUM(P".$baris_awal.":P".$baris_akhir.")");
        $sheet->setCellValue("Q" . $baris, "=SUM(Q".$baris_awal.":Q".$baris_akhir.")");
        $sheet->setCellValue("R" . $baris, "=SUM(R".$baris_awal.":R".$baris_akhir.")");
        $sheet->setCellValue("S" . $baris, "=SUM(S".$baris_awal.":S".$baris_akhir.")");
        $sheet->setCellValue("T" . $baris, "=SUM(T".$baris_awal.":T".$baris_akhir.")");
        $sheet->setCellValue("U" . $baris, "=SUM(U".$baris_awal.":U".$baris_akhir.")");
        $sheet->setCellValue("V" . $baris, "=SUM(V".$baris_awal.":V".$baris_akhir.")");
        $sheet->setCellValue("W" . $baris, "=SUM(W".$baris_awal.":W".$baris_akhir.")");
        $sheet->setCellValue("X" . $baris, "=SUM(X".$baris_awal.":X".$baris_akhir.")");
        $sheet->setCellValue("Y" . $baris, "=SUM(Y".$baris_awal.":Y".$baris_akhir.")");
        $sheet->setCellValue("Z" . $baris, "=SUM(Z".$baris_awal.":Z".$baris_akhir.")");
        $sheet->setCellValue("AA" . $baris, "=SUM(AA".$baris_awal.":AA".$baris_akhir.")");
        $sheet->getStyle('O' . $baris . ':AA' . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        $sheet->getStyle("O6:AA" . $baris)->applyFromArray($styleBorder);
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="report_pengujian_'.$thn.'.xlsx"');
        header('Set-Cookie: fileDownload=true; path=/');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $xlsWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, 'Xlsx');
        $xlsWriter->save('php://output');
        Yii::app()->end();
    }

    
    /*
     * MUTASI
     */
    
    public function actionIndexReportMutasi() {
        $this->pageTitle = 'Report Mutasi';
        $this->render('index_report_mutasi');
}
    
    public function actionReportMutasi($thn) {

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //======================================================================
        $styleCenter = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleVerticalCenter = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleBorder = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
        ));
        //======================================================================
        /*
         * HEADER MUTASI MASUK DAN MUTASI KELUAR
         */
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('MUTASI MASUK & KELUAR');
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setScale(82);
        //=============================================
        $sheet->getColumnDimension('A')->setWidth(11.56);
        $sheet->getColumnDimension('O')->setWidth(11.56);
        $sheet->mergeCells("A1:AA1");
        $sheet->mergeCells("A2:AA2");
        $sheet->mergeCells("A3:AA3");
        $sheet->mergeCells("A4:AA4");
        
        $sheet->setCellValue("A1", "JUMLAH KENDARAAN MUTASI MASUK DAN KELUAR");
        $sheet->setCellValue("A2", "TAHUN ".$thn);
        $sheet->setCellValue("A3", "UPTD PENGUJIAN KENDARAAN BERMOTOR SURABAYA");
        $sheet->setCellValue("A4", "DINAS PERHUBUNGAN KOTA SURABAYA");
        
        $sheet->getStyle("A1")->applyFromArray($styleCenter);
        $sheet->getStyle("A2")->applyFromArray($styleCenter);
        $sheet->getStyle("A3")->applyFromArray($styleCenter);
        $sheet->getStyle("A4")->applyFromArray($styleCenter);
        /*
         * BODY HEADER MUTASI MASUK
         */
        $sheet->mergeCells("B6:J6");
        $sheet->mergeCells("A6:A8");
        $sheet->mergeCells("B7:D7");
        $sheet->mergeCells("E7:G7");
        $sheet->mergeCells("H7:J7");
        $sheet->mergeCells("K6:M7");
        $sheet->setCellValue("B6", "MUTASI MASUK");
        $sheet->setCellValue("A6", "Bulan");
        $sheet->setCellValue("B7", "Mobil Barang");
        $sheet->setCellValue("B8", "Umum");
        $sheet->setCellValue("C8", "BU");
        $sheet->setCellValue("D8", "Total");
        $sheet->setCellValue("E7", "Mobil Penumpang");
        $sheet->setCellValue("E8", "Umum");
        $sheet->setCellValue("F8", "BU");
        $sheet->setCellValue("G8", "Total");
        $sheet->setCellValue("H7", "Mobil Bus");
        $sheet->setCellValue("H8", "Umum");
        $sheet->setCellValue("I8", "BU");
        $sheet->setCellValue("J8", "Total");
        $sheet->setCellValue("K6", "TOTAL KESELURUHAN");
        $sheet->setCellValue("K8", "Umum");
        $sheet->setCellValue("L8", "BU");
        $sheet->setCellValue("M8", "Total");
        $sheet->getStyle("A6:M8")->applyFromArray($styleCenter);
        $sheet->getStyle('A6:M8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        /*
         * BODY MUTASI MASUK
         */
        $baris = 9;
        $baris_awal = $baris;
        $baris_akhir = $baris_awal+11;
        
        for ($bln = 1; $bln <= 12; $bln++):
            $dataKendaraan = VLapKendaraanBaru::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $thn));
            
            $sheet->setCellValue("A" . $baris, Yii::app()->params['bulanArrayInd'][$bln - 1]);
            $sheet->setCellValue("B" . $baris, $dataKendaraan['mts_msk_mbrg_umum']);
            $sheet->setCellValue("C" . $baris, '=D'.$baris.'-B'.$baris);
            $sheet->setCellValue("D" . $baris, $dataKendaraan['mts_msk_mbrg']);
            $sheet->setCellValue("E" . $baris, $dataKendaraan['mts_msk_mpnp_umum']);
            $sheet->setCellValue("F" . $baris, '=G'.$baris.'-E'.$baris);
            $sheet->setCellValue("G" . $baris, $dataKendaraan['mts_msk_mpnp']);
            $sheet->setCellValue("H" . $baris, $dataKendaraan['mts_msk_mbus_umum']);
            $sheet->setCellValue("I" . $baris, '=J'.$baris.'-H'.$baris);
            $sheet->setCellValue("J" . $baris, $dataKendaraan['mts_msk_mbus']);
            $sheet->setCellValue("K" . $baris, '=SUM(B'.$baris.',E'.$baris.',H'.$baris.')');
            $sheet->setCellValue("L" . $baris, '=SUM(C'.$baris.',F'.$baris.',I'.$baris.')');
            $sheet->setCellValue("M" . $baris, '=SUM(K'.$baris.':L'.$baris.')');
            $baris++;
        endfor;
        
        $sheet->setCellValue("A" . $baris, "TOTAL");
        $sheet->setCellValue("B" . $baris, "=SUM(B".$baris_awal.":B".$baris_akhir.")");
        $sheet->setCellValue("C" . $baris, "=SUM(C".$baris_awal.":C".$baris_akhir.")");
        $sheet->setCellValue("D" . $baris, "=SUM(D".$baris_awal.":D".$baris_akhir.")");
        $sheet->setCellValue("E" . $baris, "=SUM(E".$baris_awal.":E".$baris_akhir.")");
        $sheet->setCellValue("F" . $baris, "=SUM(F".$baris_awal.":F".$baris_akhir.")");
        $sheet->setCellValue("G" . $baris, "=SUM(G".$baris_awal.":G".$baris_akhir.")");
        $sheet->setCellValue("H" . $baris, "=SUM(H".$baris_awal.":H".$baris_akhir.")");
        $sheet->setCellValue("I" . $baris, "=SUM(I".$baris_awal.":I".$baris_akhir.")");
        $sheet->setCellValue("J" . $baris, "=SUM(J".$baris_awal.":J".$baris_akhir.")");
        $sheet->setCellValue("K" . $baris, "=SUM(K".$baris_awal.":K".$baris_akhir.")");
        $sheet->setCellValue("L" . $baris, "=SUM(L".$baris_awal.":L".$baris_akhir.")");
        $sheet->setCellValue("M" . $baris, "=SUM(M".$baris_awal.":M".$baris_akhir.")");
        $sheet->getStyle('A' . $baris . ':M' . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        $sheet->getStyle("A6:M" . $baris)->applyFromArray($styleBorder);
        //======================================================================
        /*
         * BODY HEADER MUTASI KELUAR
         */
        $sheet->mergeCells("P6:X6");
        $sheet->mergeCells("O6:O8");
        $sheet->mergeCells("P7:R7");
        $sheet->mergeCells("S7:U7");
        $sheet->mergeCells("V7:X7");
        $sheet->mergeCells("Y6:AA7");
        $sheet->setCellValue("P6", "MUTASI KELUAR");
        $sheet->setCellValue("O6", "Bulan");
        $sheet->setCellValue("P7", "Mobil Barang");
        $sheet->setCellValue("P8", "Umum");
        $sheet->setCellValue("Q8", "BU");
        $sheet->setCellValue("R8", "Total");
        $sheet->setCellValue("S7", "Mobil Penumpang");
        $sheet->setCellValue("S8", "Umum");
        $sheet->setCellValue("T8", "BU");
        $sheet->setCellValue("U8", "Total");
        $sheet->setCellValue("V7", "Mobil Bus");
        $sheet->setCellValue("V8", "Umum");
        $sheet->setCellValue("W8", "BU");
        $sheet->setCellValue("X8", "Total");
        $sheet->setCellValue("Y6", "TOTAL KESELURUHAN");
        $sheet->setCellValue("Y8", "Umum");
        $sheet->setCellValue("Z8", "BU");
        $sheet->setCellValue("AA8", "Total");
        $sheet->getStyle("O6:AA8")->applyFromArray($styleCenter);
        $sheet->getStyle('O6:AA8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        /*
         * BODY MUTASI KELUAR
         */
        $baris = 9;
        $baris_awal = $baris;
        $baris_akhir = $baris_awal+11;
        
        for ($bln = 1; $bln <= 12; $bln++):
            $dataKendaraan = VLapKendaraanBaru::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $thn));
            
            $sheet->setCellValue("O" . $baris, Yii::app()->params['bulanArrayInd'][$bln - 1]);
            $sheet->setCellValue("P" . $baris, $dataKendaraan['mts_klr_mbrg_umum']);
            $sheet->setCellValue("Q" . $baris, '=R'.$baris.'-P'.$baris);
            $sheet->setCellValue("R" . $baris, $dataKendaraan['mts_klr_mbrg']);
            $sheet->setCellValue("S" . $baris, $dataKendaraan['mts_klr_mpnp_umum']);
            $sheet->setCellValue("T" . $baris, '=U'.$baris.'-S'.$baris);
            $sheet->setCellValue("U" . $baris, $dataKendaraan['mts_klr_mpnp']);
            $sheet->setCellValue("V" . $baris, $dataKendaraan['mts_klr_mbus_umum']);
            $sheet->setCellValue("W" . $baris, '=X'.$baris.'-V'.$baris);
            $sheet->setCellValue("X" . $baris, $dataKendaraan['mts_klr_mbus']);
            $sheet->setCellValue("Y" . $baris, '=SUM(P'.$baris.',S'.$baris.',V'.$baris.')');
            $sheet->setCellValue("Z" . $baris, '=SUM(Q'.$baris.',T'.$baris.',W'.$baris.')');
            $sheet->setCellValue("AA" . $baris, '=SUM(Y'.$baris.':Z'.$baris.')');
            $baris++;
        endfor;
        
        $sheet->setCellValue("O" . $baris, "TOTAL");
        $sheet->setCellValue("P" . $baris, "=SUM(P".$baris_awal.":P".$baris_akhir.")");
        $sheet->setCellValue("Q" . $baris, "=SUM(Q".$baris_awal.":Q".$baris_akhir.")");
        $sheet->setCellValue("R" . $baris, "=SUM(R".$baris_awal.":R".$baris_akhir.")");
        $sheet->setCellValue("S" . $baris, "=SUM(S".$baris_awal.":S".$baris_akhir.")");
        $sheet->setCellValue("T" . $baris, "=SUM(T".$baris_awal.":T".$baris_akhir.")");
        $sheet->setCellValue("U" . $baris, "=SUM(U".$baris_awal.":U".$baris_akhir.")");
        $sheet->setCellValue("V" . $baris, "=SUM(V".$baris_awal.":V".$baris_akhir.")");
        $sheet->setCellValue("W" . $baris, "=SUM(W".$baris_awal.":W".$baris_akhir.")");
        $sheet->setCellValue("X" . $baris, "=SUM(X".$baris_awal.":X".$baris_akhir.")");
        $sheet->setCellValue("Y" . $baris, "=SUM(Y".$baris_awal.":Y".$baris_akhir.")");
        $sheet->setCellValue("Z" . $baris, "=SUM(Z".$baris_awal.":Z".$baris_akhir.")");
        $sheet->setCellValue("AA" . $baris, "=SUM(AA".$baris_awal.":AA".$baris_akhir.")");
        $sheet->getStyle('O' . $baris . ':AA' . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        $sheet->getStyle("O6:AA" . $baris)->applyFromArray($styleBorder);
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="report_mutasi_'.$thn.'.xlsx"');
        header('Set-Cookie: fileDownload=true; path=/');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $xlsWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, 'Xlsx');
        $xlsWriter->save('php://output');
        Yii::app()->end();
    }

    
    
    /*
     * NUMPANG UJI
     */
    
    public function actionIndexReportNumpangUji() {
        $this->pageTitle = 'Report Numpang Uji';
        $this->render('index_report_numpang_uji');
    }
    
    public function actionReportNumpangUji($thn) {

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //======================================================================
        $styleCenter = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleVerticalCenter = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleBorder = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
        ));
        //======================================================================
                /*
         * HEADER NUMPANG UJI MASUK DAN NUMPANG KELUAR
         */
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('NUMPANG UJI');
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setScale(82);
        //=============================================
        $sheet->getColumnDimension('A')->setWidth(11.56);
        $sheet->getColumnDimension('O')->setWidth(11.56);
        $sheet->mergeCells("A1:AA1");
        $sheet->mergeCells("A2:AA2");
        $sheet->mergeCells("A3:AA3");
        $sheet->mergeCells("A4:AA4");
        
        $sheet->setCellValue("A1", "JUMLAH KENDARAAN NUMPANG UJI MASUK DAN NUMPANG UJI KELUAR");
        $sheet->setCellValue("A2", "TAHUN ".$thn);
        $sheet->setCellValue("A3", "UPTD PENGUJIAN KENDARAAN BERMOTOR SURABAYA");
        $sheet->setCellValue("A4", "DINAS PERHUBUNGAN KOTA SURABAYA");
        
        $sheet->getStyle("A1")->applyFromArray($styleCenter);
        $sheet->getStyle("A2")->applyFromArray($styleCenter);
        $sheet->getStyle("A3")->applyFromArray($styleCenter);
        $sheet->getStyle("A4")->applyFromArray($styleCenter);
        /*
         * BODY HEADER NUMPANG UJI MASUK
         */
        $sheet->mergeCells("B6:J6");
        $sheet->mergeCells("A6:A8");
        $sheet->mergeCells("B7:D7");
        $sheet->mergeCells("E7:G7");
        $sheet->mergeCells("H7:J7");
        $sheet->mergeCells("K6:M7");
        $sheet->setCellValue("B6", "NUMPANG UJI MASUK");
        $sheet->setCellValue("A6", "Bulan");
        $sheet->setCellValue("B7", "Mobil Barang");
        $sheet->setCellValue("B8", "Umum");
        $sheet->setCellValue("C8", "BU");
        $sheet->setCellValue("D8", "Total");
        $sheet->setCellValue("E7", "Mobil Penumpang");
        $sheet->setCellValue("E8", "Umum");
        $sheet->setCellValue("F8", "BU");
        $sheet->setCellValue("G8", "Total");
        $sheet->setCellValue("H7", "Mobil Bus");
        $sheet->setCellValue("H8", "Umum");
        $sheet->setCellValue("I8", "BU");
        $sheet->setCellValue("J8", "Total");
        $sheet->setCellValue("K6", "TOTAL KESELURUHAN");
        $sheet->setCellValue("K8", "Umum");
        $sheet->setCellValue("L8", "BU");
        $sheet->setCellValue("M8", "Total");
        $sheet->getStyle("A6:M8")->applyFromArray($styleCenter);
        $sheet->getStyle('A6:M8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        /*
         * BODY NUMPANG UJI MASUK
         */
        $baris = 9;
        $baris_awal = $baris;
        $baris_akhir = $baris_awal+11;
        
        for ($bln = 1; $bln <= 12; $bln++):
            $dataKendaraan = VLapKendaraanBaru::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $thn));
            
            $sheet->setCellValue("A" . $baris, Yii::app()->params['bulanArrayInd'][$bln - 1]);
            $sheet->setCellValue("B" . $baris, $dataKendaraan['npu_msk_mbrg_umum']);
            $sheet->setCellValue("C" . $baris, '=D'.$baris.'-B'.$baris);
            $sheet->setCellValue("D" . $baris, $dataKendaraan['npu_msk_mbrg']);
            $sheet->setCellValue("E" . $baris, $dataKendaraan['npu_msk_mpnp_umum']);
            $sheet->setCellValue("F" . $baris, '=G'.$baris.'-E'.$baris);
            $sheet->setCellValue("G" . $baris, $dataKendaraan['npu_msk_mpnp']);
            $sheet->setCellValue("H" . $baris, $dataKendaraan['npu_msk_mbus_umum']);
            $sheet->setCellValue("I" . $baris, '=J'.$baris.'-H'.$baris);
            $sheet->setCellValue("J" . $baris, $dataKendaraan['npu_msk_mbus']);
            $sheet->setCellValue("K" . $baris, '=SUM(B'.$baris.',E'.$baris.',H'.$baris.')');
            $sheet->setCellValue("L" . $baris, '=SUM(C'.$baris.',F'.$baris.',I'.$baris.')');
            $sheet->setCellValue("M" . $baris, '=SUM(K'.$baris.':L'.$baris.')');
            $baris++;
        endfor;
        
        $sheet->setCellValue("A" . $baris, "TOTAL");
        $sheet->setCellValue("B" . $baris, "=SUM(B".$baris_awal.":B".$baris_akhir.")");
        $sheet->setCellValue("C" . $baris, "=SUM(C".$baris_awal.":C".$baris_akhir.")");
        $sheet->setCellValue("D" . $baris, "=SUM(D".$baris_awal.":D".$baris_akhir.")");
        $sheet->setCellValue("E" . $baris, "=SUM(E".$baris_awal.":E".$baris_akhir.")");
        $sheet->setCellValue("F" . $baris, "=SUM(F".$baris_awal.":F".$baris_akhir.")");
        $sheet->setCellValue("G" . $baris, "=SUM(G".$baris_awal.":G".$baris_akhir.")");
        $sheet->setCellValue("H" . $baris, "=SUM(H".$baris_awal.":H".$baris_akhir.")");
        $sheet->setCellValue("I" . $baris, "=SUM(I".$baris_awal.":I".$baris_akhir.")");
        $sheet->setCellValue("J" . $baris, "=SUM(J".$baris_awal.":J".$baris_akhir.")");
        $sheet->setCellValue("K" . $baris, "=SUM(K".$baris_awal.":K".$baris_akhir.")");
        $sheet->setCellValue("L" . $baris, "=SUM(L".$baris_awal.":L".$baris_akhir.")");
        $sheet->setCellValue("M" . $baris, "=SUM(M".$baris_awal.":M".$baris_akhir.")");
        $sheet->getStyle('A' . $baris . ':M' . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        $sheet->getStyle("A6:M" . $baris)->applyFromArray($styleBorder);
        //======================================================================
        /*
         * BODY HEADER NUMPANG UJI KELUAR
         */
        $sheet->mergeCells("P6:X6");
        $sheet->mergeCells("O6:O8");
        $sheet->mergeCells("P7:R7");
        $sheet->mergeCells("S7:U7");
        $sheet->mergeCells("V7:X7");
        $sheet->mergeCells("Y6:AA7");
        $sheet->setCellValue("P6", "NUMPANG UJI KELUAR");
        $sheet->setCellValue("O6", "Bulan");
        $sheet->setCellValue("P7", "Mobil Barang");
        $sheet->setCellValue("P8", "Umum");
        $sheet->setCellValue("Q8", "BU");
        $sheet->setCellValue("R8", "Total");
        $sheet->setCellValue("S7", "Mobil Penumpang");
        $sheet->setCellValue("S8", "Umum");
        $sheet->setCellValue("T8", "BU");
        $sheet->setCellValue("U8", "Total");
        $sheet->setCellValue("V7", "Mobil Bus");
        $sheet->setCellValue("V8", "Umum");
        $sheet->setCellValue("W8", "BU");
        $sheet->setCellValue("X8", "Total");
        $sheet->setCellValue("Y6", "TOTAL KESELURUHAN");
        $sheet->setCellValue("Y8", "Umum");
        $sheet->setCellValue("Z8", "BU");
        $sheet->setCellValue("AA8", "Total");
        $sheet->getStyle("O6:AA8")->applyFromArray($styleCenter);
        $sheet->getStyle('O6:AA8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        /*
         * BODY NUMPANG UJI KELUAR
         */
        $baris = 9;
        $baris_awal = $baris;
        $baris_akhir = $baris_awal+11;
        
        for ($bln = 1; $bln <= 12; $bln++):
            $dataKendaraan = VLapKendaraanBaru::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $thn));
            
            $sheet->setCellValue("O" . $baris, Yii::app()->params['bulanArrayInd'][$bln - 1]);
            $sheet->setCellValue("P" . $baris, $dataKendaraan['npu_klr_mbrg_umum']);
            $sheet->setCellValue("Q" . $baris, '=R'.$baris.'-P'.$baris);
            $sheet->setCellValue("R" . $baris, $dataKendaraan['npu_klr_mbrg']);
            $sheet->setCellValue("S" . $baris, $dataKendaraan['npu_klr_mpnp_umum']);
            $sheet->setCellValue("T" . $baris, '=U'.$baris.'-S'.$baris);
            $sheet->setCellValue("U" . $baris, $dataKendaraan['npu_klr_mpnp']);
            $sheet->setCellValue("V" . $baris, $dataKendaraan['npu_klr_mbus_umum']);
            $sheet->setCellValue("W" . $baris, '=X'.$baris.'-V'.$baris);
            $sheet->setCellValue("X" . $baris, $dataKendaraan['npu_klr_mbus']);
            $sheet->setCellValue("Y" . $baris, '=SUM(P'.$baris.',S'.$baris.',V'.$baris.')');
            $sheet->setCellValue("Z" . $baris, '=SUM(Q'.$baris.',T'.$baris.',W'.$baris.')');
            $sheet->setCellValue("AA" . $baris, '=SUM(Y'.$baris.':Z'.$baris.')');
            $baris++;
        endfor;
        
        $sheet->setCellValue("O" . $baris, "TOTAL");
        $sheet->setCellValue("P" . $baris, "=SUM(P".$baris_awal.":P".$baris_akhir.")");
        $sheet->setCellValue("Q" . $baris, "=SUM(Q".$baris_awal.":Q".$baris_akhir.")");
        $sheet->setCellValue("R" . $baris, "=SUM(R".$baris_awal.":R".$baris_akhir.")");
        $sheet->setCellValue("S" . $baris, "=SUM(S".$baris_awal.":S".$baris_akhir.")");
        $sheet->setCellValue("T" . $baris, "=SUM(T".$baris_awal.":T".$baris_akhir.")");
        $sheet->setCellValue("U" . $baris, "=SUM(U".$baris_awal.":U".$baris_akhir.")");
        $sheet->setCellValue("V" . $baris, "=SUM(V".$baris_awal.":V".$baris_akhir.")");
        $sheet->setCellValue("W" . $baris, "=SUM(W".$baris_awal.":W".$baris_akhir.")");
        $sheet->setCellValue("X" . $baris, "=SUM(X".$baris_awal.":X".$baris_akhir.")");
        $sheet->setCellValue("Y" . $baris, "=SUM(Y".$baris_awal.":Y".$baris_akhir.")");
        $sheet->setCellValue("Z" . $baris, "=SUM(Z".$baris_awal.":Z".$baris_akhir.")");
        $sheet->setCellValue("AA" . $baris, "=SUM(AA".$baris_awal.":AA".$baris_akhir.")");
        $sheet->getStyle('O' . $baris . ':AA' . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        $sheet->getStyle("O6:AA" . $baris)->applyFromArray($styleBorder);
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="report_numpanguji_'.$thn.'.xlsx"');
        header('Set-Cookie: fileDownload=true; path=/');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $xlsWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, 'Xlsx');
        $xlsWriter->save('php://output');
        Yii::app()->end();
    }
    
    
    /*
     * TERDAFTAR
     */
    
    public function actionIndexReportTerdaftar() {
        $this->pageTitle = 'Report Terdaftar';
        $this->render('index_report_terdaftar');
    }
    
    public function actionReportTerdaftar($thn) {

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        
        //======================================================================
        $styleCenter = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleVerticalCenter = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleBorder = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
        ));
        //======================================================================
        /*
         * HEADER TERDAFTAR
         */
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('TERDAFTAR');
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setScale(82);
        //=============================================
        $sheet->getColumnDimension('A')->setWidth(11.56);
        $sheet->getColumnDimension('O')->setWidth(11.56);
        $sheet->mergeCells("A1:M1");
        $sheet->mergeCells("A2:M2");
        $sheet->mergeCells("A3:M3");
        $sheet->mergeCells("A4:M4");
        
        $sheet->setCellValue("A1", "JUMLAH KENDARAAN TERDAFTAR");
        $sheet->setCellValue("A2", "TAHUN ".$thn);
        $sheet->setCellValue("A3", "UPTD PENGUJIAN KENDARAAN BERMOTOR SURABAYA");
        $sheet->setCellValue("A4", "DINAS PERHUBUNGAN KOTA SURABAYA");
        
        $sheet->getStyle("A1")->applyFromArray($styleCenter);
        $sheet->getStyle("A2")->applyFromArray($styleCenter);
        $sheet->getStyle("A3")->applyFromArray($styleCenter);
        $sheet->getStyle("A4")->applyFromArray($styleCenter);
        /*
         * BODY HEADER MUTASI MASUK
         */
        $sheet->mergeCells("B6:J6");
        $sheet->mergeCells("A6:A8");
        $sheet->mergeCells("B7:D7");
        $sheet->mergeCells("E7:G7");
        $sheet->mergeCells("H7:J7");
        $sheet->mergeCells("K6:M7");
        $sheet->setCellValue("B6", "TERDAFTAR");
        $sheet->setCellValue("A6", "Bulan");
        $sheet->setCellValue("B7", "Mobil Barang");
        $sheet->setCellValue("B8", "Umum");
        $sheet->setCellValue("C8", "BU");
        $sheet->setCellValue("D8", "Total");
        $sheet->setCellValue("E7", "Mobil Penumpang");
        $sheet->setCellValue("E8", "Umum");
        $sheet->setCellValue("F8", "BU");
        $sheet->setCellValue("G8", "Total");
        $sheet->setCellValue("H7", "Mobil Bus");
        $sheet->setCellValue("H8", "Umum");
        $sheet->setCellValue("I8", "BU");
        $sheet->setCellValue("J8", "Total");
        $sheet->setCellValue("K6", "TOTAL KESELURUHAN");
        $sheet->setCellValue("K8", "Umum");
        $sheet->setCellValue("L8", "BU");
        $sheet->setCellValue("M8", "Total");
        $sheet->getStyle("A6:M8")->applyFromArray($styleCenter);
        $sheet->getStyle('A6:M8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        /*
         * BODY MUTASI MASUK
         */
        $baris = 9;
        $baris_awal = $baris;
        $baris_akhir = $baris_awal+11;
        
        for ($bln = 1; $bln <= 12; $bln++):
            $dataKendaraan = VLapKendaraanBaru::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $thn));
            
            $sheet->setCellValue("A" . $baris, Yii::app()->params['bulanArrayInd'][$bln - 1]);
            $sheet->setCellValue("B" . $baris, $dataKendaraan['terdaftar_mbrg_umum']);
            $sheet->setCellValue("C" . $baris, '=D'.$baris.'-B'.$baris);
            $sheet->setCellValue("D" . $baris, $dataKendaraan['terdaftar_mbrg']);
            $sheet->setCellValue("E" . $baris, $dataKendaraan['terdaftar_mpnp_umum']);
            $sheet->setCellValue("F" . $baris, '=G'.$baris.'-E'.$baris);
            $sheet->setCellValue("G" . $baris, $dataKendaraan['terdaftar_mpnp']);
            $sheet->setCellValue("H" . $baris, $dataKendaraan['terdaftar_mbus_umum']);
            $sheet->setCellValue("I" . $baris, '=J'.$baris.'-H'.$baris);
            $sheet->setCellValue("J" . $baris, $dataKendaraan['terdaftar_mbus']);
            $sheet->setCellValue("K" . $baris, '=SUM(B'.$baris.',E'.$baris.',H'.$baris.')');
            $sheet->setCellValue("L" . $baris, '=SUM(C'.$baris.',F'.$baris.',I'.$baris.')');
            $sheet->setCellValue("M" . $baris, '=SUM(K'.$baris.':L'.$baris.')');
            $baris++;
        endfor;
        
        $sheet->setCellValue("A" . $baris, "TOTAL");
        $sheet->setCellValue("B" . $baris, "=SUM(B".$baris_awal.":B".$baris_akhir.")");
        $sheet->setCellValue("C" . $baris, "=SUM(C".$baris_awal.":C".$baris_akhir.")");
        $sheet->setCellValue("D" . $baris, "=SUM(D".$baris_awal.":D".$baris_akhir.")");
        $sheet->setCellValue("E" . $baris, "=SUM(E".$baris_awal.":E".$baris_akhir.")");
        $sheet->setCellValue("F" . $baris, "=SUM(F".$baris_awal.":F".$baris_akhir.")");
        $sheet->setCellValue("G" . $baris, "=SUM(G".$baris_awal.":G".$baris_akhir.")");
        $sheet->setCellValue("H" . $baris, "=SUM(H".$baris_awal.":H".$baris_akhir.")");
        $sheet->setCellValue("I" . $baris, "=SUM(I".$baris_awal.":I".$baris_akhir.")");
        $sheet->setCellValue("J" . $baris, "=SUM(J".$baris_awal.":J".$baris_akhir.")");
        $sheet->setCellValue("K" . $baris, "=SUM(K".$baris_awal.":K".$baris_akhir.")");
        $sheet->setCellValue("L" . $baris, "=SUM(L".$baris_awal.":L".$baris_akhir.")");
        $sheet->setCellValue("M" . $baris, "=SUM(M".$baris_awal.":M".$baris_akhir.")");
        $sheet->getStyle('A' . $baris . ':M' . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        $sheet->getStyle("A6:M" . $baris)->applyFromArray($styleBorder);
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="report_terdaftar_'.$thn.'.xlsx"');
        header('Set-Cookie: fileDownload=true; path=/');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $xlsWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($xls, 'Xlsx');
        $xlsWriter->save('php://output');
        Yii::app()->end();
    }
}
