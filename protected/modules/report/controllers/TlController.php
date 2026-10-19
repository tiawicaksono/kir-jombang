<?php

class TlController extends Controller {
    
//    public function filters() {
//        return array(
//            'Rights', // perform access control for CRUD operations
//        );
//    }

    public function actionIndex() {
        $this->pageTitle = 'Report - Tidak Lulus';
        $this->render('index');
    }

    public function actionRekap($tgl) {

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $xls->getActiveSheet();
        $xls->setActiveSheetIndex(0);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(true);
        $sheet->getPageSetup()->setScale(90);
        //======================================================================
        //HEADER
        $sheet->mergeCells("A1:G1");
        $sheet->setCellValue("A1", "LAPORAN KENDARAAN TIDAK LULUS");
        $sheet->getStyle("A1")->getFont()->setSize(20);
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->mergeCells("A2:G2");
        $sheet->setCellValue("A2", "UPTD PKB KOTAWARINGIN TIMUR");
        $sheet->getStyle("A2")->getFont()->setSize(20);
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->mergeCells("A3:G3");
        $sheet->setCellValue("A3", date("d F Y", strtotime($tgl)));
        $sheet->getStyle("A3")->getFont()->setSize(14);
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("A5", "NO");
        $sheet->getStyle("A5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('A')->setAutoSize(true);

        $sheet->setCellValue("B5", "NO UJI");
        $sheet->getStyle("B5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('B')->setWidth(14);

        $sheet->setCellValue("C5", "NO KENDARAAN");
        $sheet->getStyle("C5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("C5")->getAlignment()->setWrapText(true);
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth(12);

        $sheet->setCellValue("D5", "NAMA PEMILIK");
        $sheet->getStyle("D5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('D')->setWidth(20);

        $sheet->setCellValue("E5", "JENIS KENDARAAN");
        $sheet->getStyle("E5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E5")->getAlignment()->setWrapText(true);
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('E')->setWidth(15);

        $sheet->setCellValue("F5", "TANDA TANGAN");
        $sheet->getStyle("F5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("F5")->getAlignment()->setWrapText(true);
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('F')->setWidth(13);

        $sheet->setCellValue("G5", "TANGGAL");
        $sheet->getStyle("G5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension('G')->setWidth(13);

        $sheet->getStyle('A5:G5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================

        $criteria = new CDbCriteria();
        $criteria->addCondition("tgl_uji = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
        $result = VTl::model()->findAll($criteria);
        //======================================================================
        //BODY
        $no = 1;
        $baris = 6;
        foreach ($result as $data):
            $sheet->setCellValue("A" . $baris, $no);
            $sheet->setCellValue("B" . $baris, $data->no_uji);
            $sheet->setCellValue("C" . $baris, $data->no_kendaraan);
            $sheet->setCellValue("D" . $baris, $data->nama_pemilik);
            $sheet->setCellValue("E" . $baris, $data->jns_kend);
            $sheet->setCellValue("F" . $baris, '');
            $sheet->setCellValue("G" . $baris, '');
            $sheet->getRowDimension($baris)->setRowHeight(40);
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
        $sheet->getStyle("A5:G".$baris_border)->applyFromArray($styleArray);
        //======================================================================
        //FOOTER
//        $kepala = $baris + 1;
//        $sheet->mergeCells("E" . $kepala . ":G" . $kepala);
//        $sheet->setCellValue("E" . $kepala, "KEPALA UPTD PKB SAMPANG");
//        $sheet->getStyle("E" . $kepala)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
//
//        $nama = $kepala + 5;
//        $sheet->mergeCells("E" . $nama . ":G" . $nama);
//        $sheet->setCellValue("E" . $nama, "Abdul Manab, SH.");
//        $sheet->getStyle("E" . $nama)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
//
//        $penata = $nama + 1;
//        $sheet->mergeCells("E" . $penata . ":G" . $penata);
//        $sheet->setCellValue("E" . $penata, "Penata");
//        $sheet->getStyle("E" . $penata)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
//
//        $nip = $penata + 1;
//        $sheet->mergeCells("E" . $nip . ":G" . $nip);
//        $sheet->setCellValue("E" . $nip, "NIP. 19630402 198910 1 003");
//        $sheet->getStyle("E" . $nip)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        
//        $sheet->getHeaderFooter()->setOddFooter('&R&F Page &P / &N');
//        $sheet->getHeaderFooter()->setEvenFooter('&R&F Page &P / &N');
        $sheet->getHeaderFooter()->setOddFooter('&R Page &P / &N');
        $sheet->getHeaderFooter()->setEvenFooter('&R Page &P / &N');
        //END FOOTER
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="TL.xlsx"');
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
