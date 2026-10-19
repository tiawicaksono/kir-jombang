<?php

class UjikendaraanController extends Controller {

    public function filters() {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }
    
    public function actionIndex() {
        $this->pageTitle = 'Uji Kendaraan';
        $this->render('index');
    }

    public function actionRekap($tgl) {
        $blnThn = date("n-Y", strtotime($tgl));
        $explodeBlnThn = explode('-', $blnThn);
        $bln = $explodeBlnThn[0];
        $thn = $explodeBlnThn[1];

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
        $criteriaIndikator->addInCondition('bulan', array($bln));
        $criteriaIndikator->addInCondition('tahun', array($thn));
        $indikator = TblIndikator::model()->find($criteriaIndikator);
        //======================================================================
        //HEADER
        $sheet->mergeCells("A1:J1");
        $sheet->setCellValue("A1", "LAPORAN UJI KENDARAAN");
        $sheet->getStyle("A1")->getFont()->setSize(16);
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->mergeCells("A2:J2");
        $sheet->setCellValue("A2", "UNIT PELAYANAN UJI BERKALA KENDARAAN BERMOTOR");
        $sheet->getStyle("A2")->getFont()->setSize(16);
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->mergeCells("A3:J3");
        $sheet->setCellValue("A3", "UPTD PKB KOTAWARINGIN TIMUR");
        $sheet->getStyle("A3")->getFont()->setSize(16);
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->mergeCells("A4:E4");
        $sheet->setCellValue("A4", "BULAN : ".date("F Y", strtotime($tgl)));
        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                
        $sheet->mergeCells("A5:A6");
        $sheet->setCellValue("A5", "TGL");
        $sheet->getStyle("A5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('A')->setWidth(10);

        $sheet->mergeCells("B5:C5");
        $sheet->setCellValue("B5", "DATANG");
        $sheet->getStyle("B5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("B6", "BRKL");
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("C6", "EXTL");
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(12);

        $sheet->mergeCells("D5:E5");
        $sheet->setCellValue("D5", "TIDAK DATANG");
        $sheet->getStyle("D5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("D6", "BRKL");
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("E6", "EXTL");
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(12);

        $sheet->mergeCells("F5:G5");
        $sheet->setCellValue("F5", "LULUS");
        $sheet->getStyle("F5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("F6", "BRKL");
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("G6", "EXTL");
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(12);

        $sheet->mergeCells("H5:I5");
        $sheet->setCellValue("H5", "TIDAK LULUS");
        $sheet->getStyle("H5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("H6", "BRKL");
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("I6", "EXTL");
        $sheet->getStyle("I6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("I6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("I")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('H')->setWidth(12);
        $sheet->getColumnDimension('I')->setWidth(12);
        
        $sheet->mergeCells("J5:K5");
        $sheet->setCellValue("J5", "TOTAL");
        $sheet->getStyle("J5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("J5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("J6", "BRKL");
        $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("J")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("K6", "EXTL");
        $sheet->getStyle("K6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("K6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("K")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('J')->setWidth(12);
        $sheet->getColumnDimension('K')->setWidth(12);

        $sheet->getStyle('A5:K6')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================

        $criteria = new CDbCriteria();
        $criteria->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
        $criteria->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
        $criteria->order = "tgl_uji ASC";
        $result = VLapKendUji::model()->findAll($criteria);
        //======================================================================
        //BODY
        $no = 1;
        $baris = 7;
//        $totalKend = 0;$nominalKend=0;$totalBuku=0;$nominalBuku=0;$blnDenda=0;$nominalDenda=0;$nominalPendapatan=0;$totProses=0;$totKendDenda=0;
        foreach ($result as $data):
            $dberkala = $data->dberkala;
            $dextl = $data->dextl;
            $tdberkala = $data->tdberkala;
            $tdextl = $data->tdextl;
            $lberkala = $data->lberkala;
            $lextl = $data->lextl;
            $tlberkala = $data->tlberkala;
            $tlextl = $data->tlextl;
//            $totalBrkl = $dberkala+$tdberkala+$lberkala+$tlberkala;
//            $totalExtl = $dextl+$tdextl+$lextl+$tlextl;
            $sheet->setCellValue("A" . $baris, date("d", strtotime($data->tgl_uji)));
            $sheet->setCellValue("B" . $baris, $dberkala);
            $sheet->setCellValue("C" . $baris, $dextl);
            $sheet->setCellValue("D" . $baris, $tdberkala);
            $sheet->setCellValue("E" . $baris, $tdextl);
            $sheet->setCellValue("F" . $baris, $lberkala);
            $sheet->setCellValue("G" . $baris, $lextl);
            $sheet->setCellValue("H" . $baris, $tlberkala);
            $sheet->setCellValue("I" . $baris, $tlextl);
            $sheet->setCellValue("J" . $baris, '=SUM(F'.$baris.',H'.$baris.')');
            $sheet->setCellValue("K" . $baris, '=SUM(G'.$baris.',I'.$baris.')');
//            $sheet->getRowDimension($baris)->setRowHeight(40);
            $akhirBaris = $baris;
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
        $sheet->getStyle("A5:K".$baris)->applyFromArray($styleArray);
//        $sheet->getStyle("C7:C".$baris)->getNumberFormat()->setFormatCode('#,##0');
//        $sheet->getStyle("E7:E".$baris)->getNumberFormat()->setFormatCode('#,##0');
//        $sheet->getStyle("H7:H".$baris)->getNumberFormat()->setFormatCode('#,##0');
//        $sheet->getStyle("I7:I".$baris)->getNumberFormat()->setFormatCode('#,##0');
//        $sheet->getStyle("J7:J".$baris)->getNumberFormat()->setFormatCode('#,##0.00');
        //======================================================================
        //FOOTER
        //TOTAL
        $sheet->setCellValue("A" . $baris, "TOTAL");
        $sheet->getStyle("A" . $baris)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("B" . $baris, '=SUM(B7:B'.$akhirBaris.')');
        $sheet->getStyle("B" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("C" . $baris, '=SUM(C7:C'.$akhirBaris.')');
        $sheet->getStyle("C" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("D" . $baris, '=SUM(D7:D'.$akhirBaris.')');
        $sheet->getStyle("D" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("E" . $baris, '=SUM(E7:E'.$akhirBaris.')');
        $sheet->getStyle("E" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("F" . $baris, '=SUM(F7:F'.$akhirBaris.')');
        $sheet->getStyle("F" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("G" . $baris, '=SUM(G7:G'.$akhirBaris.')');
        $sheet->getStyle("G" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("H" . $baris, '=SUM(H7:H'.$akhirBaris.')');
        $sheet->getStyle("H" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("I" . $baris, '=SUM(I7:I'.$akhirBaris.')');
        $sheet->getStyle("I" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("J" . $baris, '=SUM(J7:J'.$akhirBaris.')');
        $sheet->getStyle("J" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->setCellValue("K" . $baris, '=SUM(K7:K'.$akhirBaris.')');
        $sheet->getStyle("K" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        
        $sheet->getStyle("A".$baris.":K".$baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //======================================================================
        //TANDA TANGAN
//        $kepala = $baris + 2;
//        $sheet->mergeCells("F" . $kepala . ":J" . $kepala);
//        $sheet->setCellValue("F" . $kepala, "KEPALA UPTD PKB SAMPANG");
//        $sheet->getStyle("F" . $kepala)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
//
//        $nama = $kepala + 5;
//        $sheet->mergeCells("F" . $nama . ":J" . $nama);
//        $sheet->setCellValue("F" . $nama, "Abdul Manab, SH.");
//        $sheet->getStyle("F" . $nama)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
//
//        $penata = $nama + 1;
//        $sheet->mergeCells("F" . $penata . ":J" . $penata);
//        $sheet->setCellValue("F" . $penata, "Penata");
//        $sheet->getStyle("F" . $penata)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
//
//        $nip = $penata + 1;
//        $sheet->mergeCells("F" . $nip . ":J" . $nip);
//        $sheet->setCellValue("F" . $nip, "NIP. 19630402 198910 1 003");
//        $sheet->getStyle("F" . $nip)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        //END FOOTER
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Uji Kendaraan ['.$bln.'-'.$thn.'].xlsx"');
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
