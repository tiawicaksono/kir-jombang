<?php

class KementrianController extends Controller
{
        public function filters()
        {
                return array(
                        'Rights', // perform access control for CRUD operations
                );
        }

        /*
     * BERKALA
     */
        public function actionBerkala()
        {
                $this->pageTitle = 'KEMENTRIAN BERKALA';
                $this->render('berkala');
        }

        public function actionBerkalaLtl()
        {
                $this->pageTitle = 'Kementrian Berkala';
                $this->render('berkala_ltl');
        }

        public function actionBerkala3500()
        {
                $this->pageTitle = 'Kementrian Berkala';
                $this->render('berkala_3500');
        }

        public function actionBerkala35016000()
        {
                $this->pageTitle = 'Kementrian Berkala';
                $this->render('berkala_3501_6000');
        }

        public function actionBerkala60019000()
        {
                $this->pageTitle = 'Kementrian Berkala';
                $this->render('berkala_6001_9000');
        }

        public function actionBerkala900112000()
        {
                $this->pageTitle = 'Kementrian Berkala';
                $this->render('berkala_9001_12000');
        }

        public function actionBerkala12000()
        {
                $this->pageTitle = 'Kementrian Berkala';
                $this->render('berkala_12000');
        }

        public function actionReportBerkala3500($tgl)
        {
                $blnThn = date("n-Y", strtotime($tgl));
                $explodeBlnThn = explode('-', $blnThn);
                $bln = $explodeBlnThn[0];
                $thn = $explodeBlnThn[1];

                $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

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
                //        $sheet->setTitle('TERDAFTAR');
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToPage(false);
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setScale(82);
                //=============================================
                $sheet->mergeCells("A1:R1");
                $sheet->mergeCells("A2:R2");
                $sheet->mergeCells("A3:R3");
                $sheet->mergeCells("A4:R4");

                $sheet->setCellValue("A1", "JUMLAH PELAYANAN PENGUJIAN BERKALA KENDARAAN BERMOTOR BERDASARKAN UMUR DAN JBB (KG)");
                $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR");
                $sheet->setCellValue("A3", "DINAS PERHUBUNGAN KABUPATEN SAMPANG, JAWA TIMUR");
                $sheet->setCellValue("A4", "TAHUN : " . $thn . " / BULAN : " . strtoupper(Yii::app()->params['bulanArrayInd'][$bln - 1]));

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
                $sheet->setCellValue("B6", "JENIS KEND");
                $sheet->getStyle("B6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("B")->setWidth(15);
                //=====================================================================================================
                $sheet->mergeCells("C6:C7");
                $sheet->setCellValue("C6", "UMUR (TAHUN)");
                $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("C")->setWidth(10);
                //=====================================================================================================
                $sheet->mergeCells("D6:E6");
                $sheet->setCellValue("D6", "S/D 3500");
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D7", "UMUM");
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("E7", "B.UMUM");
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("D")->setWidth(12);
                $sheet->getColumnDimension("E")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("F6:G6");
                $sheet->setCellValue("F6", "3501 S/D 6000");
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F7", "UMUM");
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("G7", "B.UMUM");
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("F")->setWidth(12);
                $sheet->getColumnDimension("G")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("H6:I6");
                $sheet->setCellValue("H6", "6001 S/D 9000");
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H7", "UMUM");
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("I7", "B.UMUM");
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("H")->setWidth(12);
                $sheet->getColumnDimension("I")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("J6:K6");
                $sheet->setCellValue("J6", "9001 S/D 12000");
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J7", "UMUM");
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("K7", "B.UMUM");
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("J")->setWidth(12);
                $sheet->getColumnDimension("K")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("L6:M6");
                $sheet->setCellValue("L6", "DIATAS 12001");
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L7", "UMUM");
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("M7", "B.UMUM");
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("L")->setWidth(12);
                $sheet->getColumnDimension("M")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("N6:P6");
                $sheet->setCellValue("N6", "JUMLAH");
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("N7", "UMUM");
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("O7", "B.UMUM");
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("P7", "U+BU");
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("N")->setWidth(12);
                $sheet->getColumnDimension("O")->setWidth(12);
                $sheet->getColumnDimension("P")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("Q6:R6");
                $sheet->setCellValue("Q6", "JUMLAH STATUS HASIL UJI");
                $sheet->getStyle("Q6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("Q7", "LULUS");
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("R7", "T.LULUS");
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("Q")->setWidth(12);
                $sheet->getColumnDimension("R")->setWidth(12);
                //=====================================================================================================

                $sheet->getStyle("A6:R7")->applyFromArray($styleCenter);
                $sheet->getStyle("A6:R7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                /*
         * BODY 
         */
                $criteria = new CDbCriteria();
                $criteria->select = "SUM(mp_u_1) AS mp_u_1, SUM(mp_tu_1) AS mp_tu_1, SUM(mp_u_6) AS mp_u_6, SUM(mp_tu_6) AS mp_tu_6, SUM(mp_u_10) AS mp_u_10, SUM(mp_tu_10) AS mp_tu_10,"
                        . "SUM(mb_u_1) AS mb_u_1, SUM(mb_tu_1) AS mb_tu_1, SUM(mb_u_6) AS mb_u_6, SUM(mb_tu_6) AS mb_tu_6, SUM(mb_u_10) AS mb_u_10, SUM(mb_tu_10) AS mb_tu_10,"
                        . "SUM(bus_u_1) AS bus_u_1, SUM(bus_tu_1) AS bus_tu_1, SUM(bus_u_6) AS bus_u_6, SUM(bus_tu_6) AS bus_tu_6, SUM(bus_u_10) AS bus_u_10, SUM(bus_tu_10) AS bus_tu_10,"
                        . "SUM(mk_u_1) AS mk_u_1, SUM(mk_tu_1) AS mk_tu_1, SUM(mk_u_6) AS mk_u_6, SUM(mk_tu_6) AS mk_tu_6, SUM(mk_u_10) AS mk_u_10, SUM(mk_tu_10) AS mk_tu_10,"
                        . "SUM(kg_u_1) AS kg_u_1,SUM(kg_tu_1) AS kg_tu_1,SUM(kg_u_6) AS kg_u_6,SUM(kg_tu_6) AS kg_tu_6,SUM(kg_u_10) AS kg_u_10,SUM(kg_tu_10) AS kg_tu_10,"
                        . "SUM(kt_u_1) AS kt_u_1,SUM(kt_tu_1) AS kt_tu_1,SUM(kt_u_6) AS kt_u_6,SUM(kt_tu_6) AS kt_tu_6,SUM(kt_u_10) AS kt_u_10,SUM(kt_tu_10) AS kt_tu_10";
                $criteria->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan = VKementrianBerkala3500::model()->find($criteria);
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A8:A11");
                $sheet->setCellValue("A8", "1");
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B8:B11");
                $sheet->setCellValue("B8", "MOBIL PENUMPANG");
                $sheet->getStyle("B8")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C8", "1-5");
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D8", $dataKendaraan->mp_u_1);
                $sheet->setCellValue("E8", $dataKendaraan->mp_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C9", "6-10");
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D9", $dataKendaraan->mp_u_6);
                $sheet->setCellValue("E9", $dataKendaraan->mp_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C10", ">10");
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D10", $dataKendaraan->mp_u_10);
                $sheet->setCellValue("E10", $dataKendaraan->mp_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C11", "JUMLAH");
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C11:R11")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D11", '=SUM(D8:D10)');
                $sheet->setCellValue("E11", '=SUM(E8:E10)');
                $sheet->setCellValue("F11", '=SUM(F8:F10)');
                $sheet->setCellValue("G11", '=SUM(G8:G10)');
                $sheet->setCellValue("H11", '=SUM(H8:H10)');
                $sheet->setCellValue("I11", '=SUM(I8:I10)');
                $sheet->setCellValue("J11", '=SUM(J8:J10)');
                $sheet->setCellValue("K11", '=SUM(K8:K10)');
                $sheet->setCellValue("L11", '=SUM(L8:L10)');
                $sheet->setCellValue("M11", '=SUM(M8:M10)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A12:A15");
                $sheet->setCellValue("A12", "2");
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B12:B15");
                $sheet->setCellValue("B12", "MOBIL BUS");
                $sheet->getStyle("B12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C12", "1-5");
                $sheet->getStyle("C12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D12", $dataKendaraan->bus_u_1);
                $sheet->setCellValue("E12", $dataKendaraan->bus_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C13", "6-10");
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D13", $dataKendaraan->bus_u_6);
                $sheet->setCellValue("E13", $dataKendaraan->bus_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C14", ">10");
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D14", $dataKendaraan->bus_u_10);
                $sheet->setCellValue("E14", $dataKendaraan->bus_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C15", "JUMLAH");
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C15:R15")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D15", '=SUM(D12:D14)');
                $sheet->setCellValue("E15", '=SUM(E12:E14)');
                $sheet->setCellValue("F15", '=SUM(F12:F14)');
                $sheet->setCellValue("G15", '=SUM(G12:G14)');
                $sheet->setCellValue("H15", '=SUM(H12:H14)');
                $sheet->setCellValue("I15", '=SUM(I12:I14)');
                $sheet->setCellValue("J15", '=SUM(J12:J14)');
                $sheet->setCellValue("K15", '=SUM(K12:K14)');
                $sheet->setCellValue("L15", '=SUM(L12:L14)');
                $sheet->setCellValue("M15", '=SUM(M12:M14)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A16:A19");
                $sheet->setCellValue("A16", "3");
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B16:B19");
                $sheet->setCellValue("B16", "MOBIL KHUSUS");
                $sheet->getStyle("B16")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C16", "1-5");
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D16", $dataKendaraan->mk_u_1);
                $sheet->setCellValue("E16", $dataKendaraan->mk_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C17", "6-10");
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D17", $dataKendaraan->mk_u_6);
                $sheet->setCellValue("E17", $dataKendaraan->mk_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C18", ">10");
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D18", $dataKendaraan->mk_u_10);
                $sheet->setCellValue("E18", $dataKendaraan->mk_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C19", "JUMLAH");
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C19:R19")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D19", '=SUM(D16:D18)');
                $sheet->setCellValue("E19", '=SUM(E16:E18)');
                $sheet->setCellValue("F19", '=SUM(F16:F18)');
                $sheet->setCellValue("G19", '=SUM(G16:G18)');
                $sheet->setCellValue("H19", '=SUM(H16:H18)');
                $sheet->setCellValue("I19", '=SUM(I16:I18)');
                $sheet->setCellValue("J19", '=SUM(J16:J18)');
                $sheet->setCellValue("K19", '=SUM(K16:K18)');
                $sheet->setCellValue("L19", '=SUM(L16:L18)');
                $sheet->setCellValue("M19", '=SUM(M16:M18)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A20:A23");
                $sheet->setCellValue("A20", "4");
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B20:B23");
                $sheet->setCellValue("B20", "MOBIL BARANG");
                $sheet->getStyle("B20")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C20", "1-5");
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D20", $dataKendaraan->mb_u_1);
                $sheet->setCellValue("E20", $dataKendaraan->mb_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C21", "6-10");
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D21", $dataKendaraan->mb_u_6);
                $sheet->setCellValue("E21", $dataKendaraan->mb_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C22", ">10");
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D22", $dataKendaraan->mb_u_10);
                $sheet->setCellValue("E22", $dataKendaraan->mb_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C23", "JUMLAH");
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C23:R23")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D23", '=SUM(D20:D22)');
                $sheet->setCellValue("E23", '=SUM(E20:E22)');
                $sheet->setCellValue("F23", '=SUM(F20:F22)');
                $sheet->setCellValue("G23", '=SUM(G20:G22)');
                $sheet->setCellValue("H23", '=SUM(H20:H22)');
                $sheet->setCellValue("I23", '=SUM(I20:I22)');
                $sheet->setCellValue("J23", '=SUM(J20:J22)');
                $sheet->setCellValue("K23", '=SUM(K20:K22)');
                $sheet->setCellValue("L23", '=SUM(L20:L22)');
                $sheet->setCellValue("M23", '=SUM(M20:M22)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A24:A27");
                $sheet->setCellValue("A24", "5");
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B24:B27");
                $sheet->setCellValue("B24", "KERETA GANDENG");
                $sheet->getStyle("B24")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C24", "1-5");
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D24", $dataKendaraan->kg_u_1);
                $sheet->setCellValue("E24", $dataKendaraan->kg_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C25", "6-10");
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D25", $dataKendaraan->kg_u_6);
                $sheet->setCellValue("E25", $dataKendaraan->kg_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C26", ">10");
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D26", $dataKendaraan->kg_u_10);
                $sheet->setCellValue("E26", $dataKendaraan->kg_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C27", "JUMLAH");
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C27:R27")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D27", '=SUM(D24:D26)');
                $sheet->setCellValue("E27", '=SUM(E24:E26)');
                $sheet->setCellValue("F27", '=SUM(F24:F26)');
                $sheet->setCellValue("G27", '=SUM(G24:G26)');
                $sheet->setCellValue("H27", '=SUM(H24:H26)');
                $sheet->setCellValue("I27", '=SUM(I24:I26)');
                $sheet->setCellValue("J27", '=SUM(J24:J26)');
                $sheet->setCellValue("K27", '=SUM(K24:K26)');
                $sheet->setCellValue("L27", '=SUM(L24:L26)');
                $sheet->setCellValue("M27", '=SUM(M24:M26)');
                //======================================================================
                ////======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A28:A31");
                $sheet->setCellValue("A28", "6");
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B28:B31");
                $sheet->setCellValue("B28", "KERETA TEMPEL");
                $sheet->getStyle("B28")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C28", "1-5");
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D28", $dataKendaraan->kt_u_1);
                $sheet->setCellValue("E28", $dataKendaraan->kt_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C29", "6-10");
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D29", $dataKendaraan->kt_u_6);
                $sheet->setCellValue("E29", $dataKendaraan->kt_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C30", ">10");
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D30", $dataKendaraan->kt_u_10);
                $sheet->setCellValue("E30", $dataKendaraan->kt_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C31", "JUMLAH");
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C31:R31")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D31", '=SUM(D28:D30)');
                $sheet->setCellValue("E31", '=SUM(E28:E30)');
                $sheet->setCellValue("F31", '=SUM(F28:F30)');
                $sheet->setCellValue("G31", '=SUM(G28:G30)');
                $sheet->setCellValue("H31", '=SUM(H28:H30)');
                $sheet->setCellValue("I31", '=SUM(I28:I30)');
                $sheet->setCellValue("J31", '=SUM(J28:J30)');
                $sheet->setCellValue("K31", '=SUM(K28:K30)');
                $sheet->setCellValue("L31", '=SUM(L28:L30)');
                $sheet->setCellValue("M31", '=SUM(M28:M30)');
                //======================================================================
                //JUMLAH UMUM & BUKAN UMUM
                //======================================================================
                //UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("N8", '=SUM(D8,F8,H8,J8,L8)');
                $sheet->setCellValue("N9", '=SUM(D9,F9,H9,J9,L9)');
                $sheet->setCellValue("N10", '=SUM(D10,F10,H10,J10,L10)');
                $sheet->setCellValue("N11", '=SUM(N8:N10)');
                //MOBIL BUS
                $sheet->setCellValue("N12", '=SUM(D12,F12,H12,J12,L12)');
                $sheet->setCellValue("N13", '=SUM(D13,F13,H13,J13,L13)');
                $sheet->setCellValue("N14", '=SUM(D14,F14,H14,J14,L14)');
                $sheet->setCellValue("N15", '=SUM(N12:N14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("N16", '=SUM(D16,F16,H16,J16,L16)');
                $sheet->setCellValue("N17", '=SUM(D17,F17,H17,J17,L17)');
                $sheet->setCellValue("N18", '=SUM(D18,F18,H18,J18,L18)');
                $sheet->setCellValue("N19", '=SUM(N16:N18)');
                //MOBIL BARANG
                $sheet->setCellValue("N20", '=SUM(D20,F20,H20,J20,L20)');
                $sheet->setCellValue("N21", '=SUM(D21,F21,H21,J21,L21)');
                $sheet->setCellValue("N22", '=SUM(D22,F22,H22,J22,L22)');
                $sheet->setCellValue("N23", '=SUM(N20:N22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("N24", '=SUM(D24,F24,H24,J24,L24)');
                $sheet->setCellValue("N25", '=SUM(D25,F25,H25,J25,L25)');
                $sheet->setCellValue("N26", '=SUM(D26,F26,H26,J26,L26)');
                $sheet->setCellValue("N27", '=SUM(N24:N26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("N28", '=SUM(D28,F28,H28,J28,L28)');
                $sheet->setCellValue("N29", '=SUM(D29,F29,H29,J29,L29)');
                $sheet->setCellValue("N30", '=SUM(D30,F30,H30,J30,L30)');
                $sheet->setCellValue("N31", '=SUM(N28:N30)');
                //======================================================================
                //BUKAN UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("O8", '=SUM(E8,G8,I8,K8,M8)');
                $sheet->setCellValue("O9", '=SUM(E9,G9,I9,K9,M9)');
                $sheet->setCellValue("O10", '=SUM(E10,G10,I10,K10,M10)');
                $sheet->setCellValue("O11", '=SUM(O8:O10)');
                //MOBIL BUS
                $sheet->setCellValue("O12", '=SUM(E12,G12,I12,K12,M12)');
                $sheet->setCellValue("O13", '=SUM(E13,G13,I13,K13,M13)');
                $sheet->setCellValue("O14", '=SUM(E14,G14,I14,K14,M14)');
                $sheet->setCellValue("O15", '=SUM(O12:O14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("O16", '=SUM(E16,G16,I16,K16,M16)');
                $sheet->setCellValue("O17", '=SUM(E17,G17,I17,K17,M17)');
                $sheet->setCellValue("O18", '=SUM(E18,G18,I18,K18,M18)');
                $sheet->setCellValue("O19", '=SUM(O16:O18)');
                //MOBIL BARANG
                $sheet->setCellValue("O20", '=SUM(E20,G20,I20,K20,M20)');
                $sheet->setCellValue("O21", '=SUM(E21,G21,I21,K21,M21)');
                $sheet->setCellValue("O22", '=SUM(E22,G22,I22,K22,M22)');
                $sheet->setCellValue("O23", '=SUM(O20:O22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("O24", '=SUM(E24,G24,I24,K24,M24)');
                $sheet->setCellValue("O25", '=SUM(E25,G25,I25,K25,M25)');
                $sheet->setCellValue("O26", '=SUM(E26,G26,I26,K26,M26)');
                $sheet->setCellValue("O27", '=SUM(O24:O26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("O28", '=SUM(E28,G28,I28,K28,M28)');
                $sheet->setCellValue("O29", '=SUM(E29,G29,I29,K29,M29)');
                $sheet->setCellValue("O30", '=SUM(E30,G30,I30,K30,M30)');
                $sheet->setCellValue("O31", '=SUM(O28:O30)');
                //UMUM + BUKAN UMUM
                $sheet->setCellValue("P8", '=SUM(N8,O8)');
                $sheet->setCellValue("P9", '=SUM(N9,O9)');
                $sheet->setCellValue("P10", '=SUM(N10,O10)');
                $sheet->setCellValue("P11", '=SUM(P8:P10)');
                $sheet->setCellValue("P12", '=SUM(N12,O12)');
                $sheet->setCellValue("P13", '=SUM(N13,O13)');
                $sheet->setCellValue("P14", '=SUM(N14,O14)');
                $sheet->setCellValue("P15", '=SUM(P12:P14)');
                $sheet->setCellValue("P16", '=SUM(N16,O16)');
                $sheet->setCellValue("P17", '=SUM(N17,O17)');
                $sheet->setCellValue("P18", '=SUM(N18,O18)');
                $sheet->setCellValue("P19", '=SUM(P16:P18)');
                $sheet->setCellValue("P20", '=SUM(N20:O20)');
                $sheet->setCellValue("P21", '=SUM(N21:O21)');
                $sheet->setCellValue("P22", '=SUM(N22:O22)');
                $sheet->setCellValue("P23", '=SUM(P20:P22)');
                $sheet->setCellValue("P24", '=SUM(N24:O24)');
                $sheet->setCellValue("P25", '=SUM(N25:O25)');
                $sheet->setCellValue("P26", '=SUM(N26:O26)');
                $sheet->setCellValue("P27", '=SUM(P24:P26)');
                $sheet->setCellValue("P28", '=SUM(N28:O28)');
                $sheet->setCellValue("P29", '=SUM(N29:O29)');
                $sheet->setCellValue("P30", '=SUM(N30:O30)');
                $sheet->setCellValue("P31", '=SUM(P28:P30)');
                //======================================================================
                //JUMLAH STATUS HASIL UJI
                //MOBIL PENUMPANG
                //        $sheet->setCellValue("Q8", $dataKendaraan->mp_l_1);
                //        $sheet->setCellValue("R8", $dataKendaraan->mp_tl_1);
                //        $sheet->setCellValue("Q9", $dataKendaraan->mp_l_6);
                //        $sheet->setCellValue("R9", $dataKendaraan->mp_tl_6);
                //        $sheet->setCellValue("Q10", $dataKendaraan->mp_l_10);
                //        $sheet->setCellValue("R10", $dataKendaraan->mp_tl_10);
                $sheet->setCellValue("Q11", '=SUM(Q8:Q10)');
                $sheet->setCellValue("R11", '=SUM(R8:R10)');
                //MOBIL BUS
                //        $sheet->setCellValue("Q12", $dataKendaraan->bus_l_1);
                //        $sheet->setCellValue("R12", $dataKendaraan->bus_tl_1);
                //        $sheet->setCellValue("Q13", $dataKendaraan->bus_l_6);
                //        $sheet->setCellValue("R13", $dataKendaraan->bus_tl_6);
                //        $sheet->setCellValue("Q14", $dataKendaraan->bus_l_10);
                //        $sheet->setCellValue("R14", $dataKendaraan->bus_tl_10);
                $sheet->setCellValue("Q15", '=SUM(Q12:Q14)');
                $sheet->setCellValue("R15", '=SUM(R12:R14)');
                //MOBIL KHUSUS
                //        $sheet->setCellValue("Q16", $dataKendaraan->mk_l_1);
                //        $sheet->setCellValue("R16", $dataKendaraan->mk_tl_1);
                //        $sheet->setCellValue("Q17", $dataKendaraan->mk_l_6);
                //        $sheet->setCellValue("R17", $dataKendaraan->mk_tl_6);
                //        $sheet->setCellValue("Q18", $dataKendaraan->mk_l_10);
                //        $sheet->setCellValue("R18", $dataKendaraan->mk_tl_10);
                $sheet->setCellValue("Q19", '=SUM(Q16:Q18)');
                $sheet->setCellValue("R19", '=SUM(R16:R18)');
                //MOBIL BARANG
                //        $sheet->setCellValue("Q20", $dataKendaraan->mb_l_1);
                //        $sheet->setCellValue("R20", $dataKendaraan->mb_tl_1);
                //        $sheet->setCellValue("Q21", $dataKendaraan->mb_l_6);
                //        $sheet->setCellValue("R21", $dataKendaraan->mb_tl_6);
                //        $sheet->setCellValue("Q22", $dataKendaraan->mb_l_10);
                //        $sheet->setCellValue("R22", $dataKendaraan->mb_tl_10);
                $sheet->setCellValue("Q23", '=SUM(Q20:Q22)');
                $sheet->setCellValue("R23", '=SUM(R20:R22)');
                //KERETA GANDENGAN
                //        $sheet->setCellValue("Q24", $dataKendaraan->kg_l_1);
                //        $sheet->setCellValue("R24", $dataKendaraan->kg_tl_1);
                //        $sheet->setCellValue("Q25", $dataKendaraan->kg_l_6);
                //        $sheet->setCellValue("R25", $dataKendaraan->kg_tl_6);
                //        $sheet->setCellValue("Q26", $dataKendaraan->kg_l_10);
                //        $sheet->setCellValue("R26", $dataKendaraan->kg_tl_10);
                $sheet->setCellValue("Q27", '=SUM(Q24:Q26)');
                $sheet->setCellValue("R27", '=SUM(R24:R26)');
                //KERETA TEMPELAN
                //        $sheet->setCellValue("Q28", $dataKendaraan->kt_l_1);
                //        $sheet->setCellValue("R28", $dataKendaraan->kt_tl_1);
                //        $sheet->setCellValue("Q29", $dataKendaraan->kt_l_6);
                //        $sheet->setCellValue("R29", $dataKendaraan->kt_tl_6);
                //        $sheet->setCellValue("Q30", $dataKendaraan->kt_l_10);
                //        $sheet->setCellValue("R30", $dataKendaraan->kt_tl_10);
                $sheet->setCellValue("Q31", '=SUM(Q28:Q30)');
                $sheet->setCellValue("R31", '=SUM(R28:R30)');
                //======================================================================
                $sheet->mergeCells("A32:C32");
                $sheet->setCellValue("A32", "JUMLAH");
                $sheet->setCellValue("D32", "=SUM(D11,D15,D19,D23,D27,D31)");
                $sheet->setCellValue("E32", "=SUM(E11,E15,E19,E23,E27,E31)");
                $sheet->setCellValue("F32", "=SUM(F11,F15,F19,F23,F27,F31)");
                $sheet->setCellValue("G32", "=SUM(G11,G15,G19,G23,G27,G31)");
                $sheet->setCellValue("H32", "=SUM(H11,H15,H19,H23,H27,H31)");
                $sheet->setCellValue("I32", "=SUM(I11,I15,I19,I23,I27,I31)");
                $sheet->setCellValue("J32", "=SUM(J11,J15,J19,J23,J27,J31)");
                $sheet->setCellValue("K32", "=SUM(K11,K15,K19,K23,K27,K31)");
                $sheet->setCellValue("L32", "=SUM(L11,L15,L19,L23,L27,L31)");
                $sheet->setCellValue("M32", "=SUM(M11,M15,M19,M23,M27,M31)");
                $sheet->setCellValue("N32", "=SUM(N11,N15,N19,N23,N27,N31)");
                $sheet->setCellValue("O32", "=SUM(O11,O15,O19,O23,O27,O31)");
                $sheet->setCellValue("P32", "=SUM(P11,P15,P19,P23,P27,P31)");
                $sheet->setCellValue("Q32", "=SUM(Q11,Q15,Q19,Q23,Q27,Q31)");
                $sheet->setCellValue("R32", "=SUM(R11,R15,R19,R23,R27,R31)");
                $sheet->getStyle("A32:R32")->applyFromArray($styleCenter);
                $sheet->getStyle("A32:R32")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                //=====================================================================================================
                $sheet->getStyle("A6:R32")->applyFromArray($styleBorder);
                ob_clean();

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="kementrian_berkala_3500_' . $thn . '_' . $bln . '.xls"');
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

        public function actionReportBerkalaLTl($tgl)
        {
                $blnThn = date("n-Y", strtotime($tgl));
                $explodeBlnThn = explode('-', $blnThn);
                $bln = $explodeBlnThn[0];
                $thn = $explodeBlnThn[1];

                $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

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
                //        $sheet->setTitle('TERDAFTAR');
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToPage(false);
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setScale(82);
                //=============================================
                $sheet->mergeCells("A1:R1");
                $sheet->mergeCells("A2:R2");
                $sheet->mergeCells("A3:R3");
                $sheet->mergeCells("A4:R4");

                $sheet->setCellValue("A1", "JUMLAH PELAYANAN PENGUJIAN BERKALA KENDARAAN BERMOTOR BERDASARKAN UMUR DAN JBB (KG)");
                $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR");
                $sheet->setCellValue("A3", "DINAS PERHUBUNGAN KABUPATEN SAMPANG, JAWA TIMUR");
                $sheet->setCellValue("A4", "TAHUN : " . $thn . " / BULAN : " . strtoupper(Yii::app()->params['bulanArrayInd'][$bln - 1]));

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
                $sheet->setCellValue("B6", "JENIS KEND");
                $sheet->getStyle("B6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("B")->setWidth(15);
                //=====================================================================================================
                $sheet->mergeCells("C6:C7");
                $sheet->setCellValue("C6", "UMUR (TAHUN)");
                $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("C")->setWidth(10);
                //=====================================================================================================
                $sheet->mergeCells("D6:E6");
                $sheet->setCellValue("D6", "S/D 3500");
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D7", "UMUM");
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("E7", "B.UMUM");
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("D")->setWidth(12);
                $sheet->getColumnDimension("E")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("F6:G6");
                $sheet->setCellValue("F6", "3501 S/D 6000");
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F7", "UMUM");
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("G7", "B.UMUM");
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("F")->setWidth(12);
                $sheet->getColumnDimension("G")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("H6:I6");
                $sheet->setCellValue("H6", "6001 S/D 9000");
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H7", "UMUM");
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("I7", "B.UMUM");
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("H")->setWidth(12);
                $sheet->getColumnDimension("I")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("J6:K6");
                $sheet->setCellValue("J6", "9001 S/D 12000");
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J7", "UMUM");
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("K7", "B.UMUM");
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("J")->setWidth(12);
                $sheet->getColumnDimension("K")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("L6:M6");
                $sheet->setCellValue("L6", "DIATAS 12001");
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L7", "UMUM");
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("M7", "B.UMUM");
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("L")->setWidth(12);
                $sheet->getColumnDimension("M")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("N6:P6");
                $sheet->setCellValue("N6", "JUMLAH");
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("N7", "UMUM");
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("O7", "B.UMUM");
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("P7", "U+BU");
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("N")->setWidth(12);
                $sheet->getColumnDimension("O")->setWidth(12);
                $sheet->getColumnDimension("P")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("Q6:R6");
                $sheet->setCellValue("Q6", "JUMLAH STATUS HASIL UJI");
                $sheet->getStyle("Q6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("Q7", "LULUS");
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("R7", "T.LULUS");
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("Q")->setWidth(12);
                $sheet->getColumnDimension("R")->setWidth(12);
                //=====================================================================================================

                $sheet->getStyle("A6:R7")->applyFromArray($styleCenter);
                $sheet->getStyle("A6:R7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                /*
         * BODY 
         */
                $criteria = new CDbCriteria();
                $criteria->select = "SUM(mb_l_1) AS mb_l_1, SUM(mb_l_6) AS mb_l_6, SUM(mb_l_10) AS mb_l_10, SUM(mb_tl_1) AS mb_tl_1, SUM(mb_tl_6) AS mb_tl_6, SUM(mb_tl_10) AS mb_tl_10,"
                        . "SUM(bus_l_1) AS bus_l_1, SUM(bus_l_6) AS bus_l_6, SUM(bus_l_10) AS bus_l_10, SUM(bus_tl_1) AS bus_tl_1, SUM(bus_tl_6) AS bus_tl_6, SUM(bus_tl_10) AS bus_tl_10,"
                        . "SUM(mp_l_1) AS mp_l_1, SUM(mp_l_6) AS mp_l_6, SUM(mp_l_10) AS mp_l_10, SUM(mp_tl_1) AS mp_tl_1, SUM(mp_tl_6) AS mp_tl_6, SUM(mp_tl_10) AS mp_tl_10,"
                        . "SUM(mk_l_1) AS mk_l_1, SUM(mk_l_6) AS mk_l_6, SUM(mk_l_10) AS mk_l_10, SUM(mk_tl_1) AS mk_tl_1, SUM(mk_tl_6) AS mk_tl_6, SUM(mk_tl_10) AS mk_tl_10,"
                        . "SUM(kg_l_1) AS kg_l_1,SUM(kg_l_6) AS kg_l_6,SUM(kg_l_10) AS kg_l_10,SUM(kg_tl_1) AS kg_tl_1,SUM(kg_tl_6) AS kg_tl_6,SUM(kg_tl_10) AS kg_tl_10,"
                        . "SUM(kt_l_1) AS kt_l_1,SUM(kt_l_6) AS kt_l_6,SUM(kt_l_10) AS kt_l_10,SUM(kt_tl_1) AS kt_tl_1,SUM(kt_tl_6) AS kt_tl_6,SUM(kt_tl_10) AS kt_tl_10";
                $criteria->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan = VKementrianBerkalaLtl::model()->find($criteria);
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A8:A11");
                $sheet->setCellValue("A8", "1");
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B8:B11");
                $sheet->setCellValue("B8", "MOBIL PENUMPANG");
                $sheet->getStyle("B8")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C8", "1-5");
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D8", $dataKendaraan->mp_u_1);
                //        $sheet->setCellValue("E8", $dataKendaraan->mp_bu_1);
                //-------------------------------------------
                $sheet->setCellValue("C9", "6-10");
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D9", $dataKendaraan->mp_u_6);
                //        $sheet->setCellValue("E9", $dataKendaraan->mp_bu_6);
                //-------------------------------------------
                $sheet->setCellValue("C10", ">10");
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D10", $dataKendaraan->mp_u_10);
                //        $sheet->setCellValue("E10", $dataKendaraan->mp_bu_10);
                //-------------------------------------------
                $sheet->setCellValue("C11", "JUMLAH");
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C11:R11")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D11", '=SUM(D8:D10)');
                $sheet->setCellValue("E11", '=SUM(E8:E10)');
                $sheet->setCellValue("F11", '=SUM(F8:F10)');
                $sheet->setCellValue("G11", '=SUM(G8:G10)');
                $sheet->setCellValue("H11", '=SUM(H8:H10)');
                $sheet->setCellValue("I11", '=SUM(I8:I10)');
                $sheet->setCellValue("J11", '=SUM(J8:J10)');
                $sheet->setCellValue("K11", '=SUM(K8:K10)');
                $sheet->setCellValue("L11", '=SUM(L8:L10)');
                $sheet->setCellValue("M11", '=SUM(M8:M10)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A12:A15");
                $sheet->setCellValue("A12", "2");
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B12:B15");
                $sheet->setCellValue("B12", "MOBIL BUS");
                $sheet->getStyle("B12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C12", "1-5");
                $sheet->getStyle("C12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D12", $dataKendaraan->bus_u_1);
                //        $sheet->setCellValue("E12", $dataKendaraan->bus_bu_1);
                //-------------------------------------------
                $sheet->setCellValue("C13", "6-10");
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D13", $dataKendaraan->bus_u_6);
                //        $sheet->setCellValue("E13", $dataKendaraan->bus_bu_6);
                //-------------------------------------------
                $sheet->setCellValue("C14", ">10");
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D14", $dataKendaraan->bus_u_10);
                //        $sheet->setCellValue("E14", $dataKendaraan->bus_bu_10);
                //-------------------------------------------
                $sheet->setCellValue("C15", "JUMLAH");
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C15:R15")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D15", '=SUM(D12:D14)');
                $sheet->setCellValue("E15", '=SUM(E12:E14)');
                $sheet->setCellValue("F15", '=SUM(F12:F14)');
                $sheet->setCellValue("G15", '=SUM(G12:G14)');
                $sheet->setCellValue("H15", '=SUM(H12:H14)');
                $sheet->setCellValue("I15", '=SUM(I12:I14)');
                $sheet->setCellValue("J15", '=SUM(J12:J14)');
                $sheet->setCellValue("K15", '=SUM(K12:K14)');
                $sheet->setCellValue("L15", '=SUM(L12:L14)');
                $sheet->setCellValue("M15", '=SUM(M12:M14)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A16:A19");
                $sheet->setCellValue("A16", "3");
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B16:B19");
                $sheet->setCellValue("B16", "MOBIL KHUSUS");
                $sheet->getStyle("B16")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C16", "1-5");
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("C17", "6-10");
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("C18", ">10");
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //-------------------------------------------
                $sheet->setCellValue("C19", "JUMLAH");
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C19:R19")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D19", '=SUM(D16:D18)');
                $sheet->setCellValue("E19", '=SUM(E16:E18)');
                $sheet->setCellValue("F19", '=SUM(F16:F18)');
                $sheet->setCellValue("G19", '=SUM(G16:G18)');
                $sheet->setCellValue("H19", '=SUM(H16:H18)');
                $sheet->setCellValue("I19", '=SUM(I16:I18)');
                $sheet->setCellValue("J19", '=SUM(J16:J18)');
                $sheet->setCellValue("K19", '=SUM(K16:K18)');
                $sheet->setCellValue("L19", '=SUM(L16:L18)');
                $sheet->setCellValue("M19", '=SUM(M16:M18)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A20:A23");
                $sheet->setCellValue("A20", "4");
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B20:B23");
                $sheet->setCellValue("B20", "MOBIL BARANG");
                $sheet->getStyle("B20")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C20", "1-5");
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D20", $dataKendaraan->mb_u_1);
                //        $sheet->setCellValue("E20", $dataKendaraan->mb_bu_1);
                //-------------------------------------------
                $sheet->setCellValue("C21", "6-10");
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D21", $dataKendaraan->mb_u_6);
                //        $sheet->setCellValue("E21", $dataKendaraan->mb_bu_6);
                //-------------------------------------------
                $sheet->setCellValue("C22", ">10");
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D22", $dataKendaraan->mb_u_10);
                //        $sheet->setCellValue("E22", $dataKendaraan->mb_bu_10);
                //-------------------------------------------
                $sheet->setCellValue("C23", "JUMLAH");
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C23:R23")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D23", '=SUM(D20:D22)');
                $sheet->setCellValue("E23", '=SUM(E20:E22)');
                $sheet->setCellValue("F23", '=SUM(F20:F22)');
                $sheet->setCellValue("G23", '=SUM(G20:G22)');
                $sheet->setCellValue("H23", '=SUM(H20:H22)');
                $sheet->setCellValue("I23", '=SUM(I20:I22)');
                $sheet->setCellValue("J23", '=SUM(J20:J22)');
                $sheet->setCellValue("K23", '=SUM(K20:K22)');
                $sheet->setCellValue("L23", '=SUM(L20:L22)');
                $sheet->setCellValue("M23", '=SUM(M20:M22)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A24:A27");
                $sheet->setCellValue("A24", "5");
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B24:B27");
                $sheet->setCellValue("B24", "KERETA GANDENG");
                $sheet->getStyle("B24")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C24", "1-5");
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D23", $dataKendaraan->mb_u_1);
                //        $sheet->setCellValue("E23", $dataKendaraan->mb_bu_1);
                //-------------------------------------------
                $sheet->setCellValue("C25", "6-10");
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D24", $dataKendaraan->mb_u_6);
                //        $sheet->setCellValue("E24", $dataKendaraan->mb_bu_6);
                //-------------------------------------------
                $sheet->setCellValue("C26", ">10");
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D25", $dataKendaraan->mb_u_10);
                //        $sheet->setCellValue("E25", $dataKendaraan->mb_bu_10);
                //-------------------------------------------
                $sheet->setCellValue("C27", "JUMLAH");
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C27:R27")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D27", '=SUM(D24:D26)');
                $sheet->setCellValue("E27", '=SUM(E24:E26)');
                $sheet->setCellValue("F27", '=SUM(F24:F26)');
                $sheet->setCellValue("G27", '=SUM(G24:G26)');
                $sheet->setCellValue("H27", '=SUM(H24:H26)');
                $sheet->setCellValue("I27", '=SUM(I24:I26)');
                $sheet->setCellValue("J27", '=SUM(J24:J26)');
                $sheet->setCellValue("K27", '=SUM(K24:K26)');
                $sheet->setCellValue("L27", '=SUM(L24:L26)');
                $sheet->setCellValue("M27", '=SUM(M24:M26)');
                //======================================================================
                ////======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A28:A31");
                $sheet->setCellValue("A28", "6");
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B28:B31");
                $sheet->setCellValue("B28", "KERETA TEMPEL");
                $sheet->getStyle("B28")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C28", "1-5");
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D23", $dataKendaraan->mb_u_1);
                //        $sheet->setCellValue("E23", $dataKendaraan->mb_bu_1);
                //-------------------------------------------
                $sheet->setCellValue("C29", "6-10");
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D24", $dataKendaraan->mb_u_6);
                //        $sheet->setCellValue("E24", $dataKendaraan->mb_bu_6);
                //-------------------------------------------
                $sheet->setCellValue("C30", ">10");
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                //        $sheet->setCellValue("D25", $dataKendaraan->mb_u_10);
                //        $sheet->setCellValue("E25", $dataKendaraan->mb_bu_10);
                //-------------------------------------------
                $sheet->setCellValue("C31", "JUMLAH");
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C31:R31")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D31", '=SUM(D28:D30)');
                $sheet->setCellValue("E31", '=SUM(E28:E30)');
                $sheet->setCellValue("F31", '=SUM(F28:F30)');
                $sheet->setCellValue("G31", '=SUM(G28:G30)');
                $sheet->setCellValue("H31", '=SUM(H28:H30)');
                $sheet->setCellValue("I31", '=SUM(I28:I30)');
                $sheet->setCellValue("J31", '=SUM(J28:J30)');
                $sheet->setCellValue("K31", '=SUM(K28:K30)');
                $sheet->setCellValue("L31", '=SUM(L28:L30)');
                $sheet->setCellValue("M31", '=SUM(M28:M30)');
                //======================================================================
                //JUMLAH UMUM & BUKAN UMUM
                //======================================================================
                //UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("N8", '=SUM(D8,F8,H8,J8,L8)');
                $sheet->setCellValue("N9", '=SUM(D9,F9,H9,J9,L9)');
                $sheet->setCellValue("N10", '=SUM(D10,F10,H10,J10,L10)');
                $sheet->setCellValue("N11", '=SUM(N8:N10)');
                //MOBIL BUS
                $sheet->setCellValue("N12", '=SUM(D12,F12,H12,J12,L12)');
                $sheet->setCellValue("N13", '=SUM(D13,F13,H13,J13,L13)');
                $sheet->setCellValue("N14", '=SUM(D14,F14,H14,J14,L14)');
                $sheet->setCellValue("N15", '=SUM(N12:N14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("N16", '=SUM(D16,F16,H16,J16,L16)');
                $sheet->setCellValue("N17", '=SUM(D17,F17,H17,J17,L17)');
                $sheet->setCellValue("N18", '=SUM(D18,F18,H18,J18,L18)');
                $sheet->setCellValue("N19", '=SUM(N16:N18)');
                //MOBIL BARANG
                $sheet->setCellValue("N20", '=SUM(D20,F20,H20,J20,L20)');
                $sheet->setCellValue("N21", '=SUM(D21,F21,H21,J21,L21)');
                $sheet->setCellValue("N22", '=SUM(D22,F22,H22,J22,L22)');
                $sheet->setCellValue("N23", '=SUM(N20:N22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("N24", '=SUM(D24,F24,H24,J24,L24)');
                $sheet->setCellValue("N25", '=SUM(D25,F25,H25,J25,L25)');
                $sheet->setCellValue("N26", '=SUM(D26,F26,H26,J26,L26)');
                $sheet->setCellValue("N27", '=SUM(N24:N26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("N28", '=SUM(D28,F28,H28,J28,L28)');
                $sheet->setCellValue("N29", '=SUM(D29,F29,H29,J29,L29)');
                $sheet->setCellValue("N30", '=SUM(D30,F30,H30,J30,L30)');
                $sheet->setCellValue("N31", '=SUM(N28:N30)');
                //======================================================================
                //BUKAN UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("O8", '=SUM(E8,G8,I8,K8,M8)');
                $sheet->setCellValue("O9", '=SUM(E9,G9,I9,K9,M9)');
                $sheet->setCellValue("O10", '=SUM(E10,G10,I10,K10,M10)');
                $sheet->setCellValue("O11", '=SUM(O8:O10)');
                //MOBIL BUS
                $sheet->setCellValue("O12", '=SUM(E12,G12,I12,K12,M12)');
                $sheet->setCellValue("O13", '=SUM(E13,G13,I13,K13,M13)');
                $sheet->setCellValue("O14", '=SUM(E14,G14,I14,K14,M14)');
                $sheet->setCellValue("O15", '=SUM(O12:O14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("O16", '=SUM(E16,G16,I16,K16,M16)');
                $sheet->setCellValue("O17", '=SUM(E17,G17,I17,K17,M17)');
                $sheet->setCellValue("O18", '=SUM(E18,G18,I18,K18,M18)');
                $sheet->setCellValue("O19", '=SUM(O16:O18)');
                //MOBIL BARANG
                $sheet->setCellValue("O20", '=SUM(E20,G20,I20,K20,M20)');
                $sheet->setCellValue("O21", '=SUM(E21,G21,I21,K21,M21)');
                $sheet->setCellValue("O22", '=SUM(E22,G22,I22,K22,M22)');
                $sheet->setCellValue("O23", '=SUM(O20:O22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("O24", '=SUM(E24,G24,I24,K24,M24)');
                $sheet->setCellValue("O25", '=SUM(E25,G25,I25,K25,M25)');
                $sheet->setCellValue("O26", '=SUM(E26,G26,I26,K26,M26)');
                $sheet->setCellValue("O27", '=SUM(O24:O26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("O28", '=SUM(E28,G28,I28,K28,M28)');
                $sheet->setCellValue("O29", '=SUM(E29,G29,I29,K29,M29)');
                $sheet->setCellValue("O30", '=SUM(E30,G30,I30,K30,M30)');
                $sheet->setCellValue("O31", '=SUM(O28:O30)');
                //UMUM + BUKAN UMUM
                $sheet->setCellValue("P8", '=SUM(N8,O8)');
                $sheet->setCellValue("P9", '=SUM(N9,O9)');
                $sheet->setCellValue("P10", '=SUM(N10,O10)');
                $sheet->setCellValue("P11", '=SUM(P8:P10)');
                $sheet->setCellValue("P12", '=SUM(N12,O12)');
                $sheet->setCellValue("P13", '=SUM(N13,O13)');
                $sheet->setCellValue("P14", '=SUM(N14,O14)');
                $sheet->setCellValue("P15", '=SUM(P12:P14)');
                $sheet->setCellValue("P16", '=SUM(N16,O16)');
                $sheet->setCellValue("P17", '=SUM(N17,O17)');
                $sheet->setCellValue("P18", '=SUM(N18,O18)');
                $sheet->setCellValue("P19", '=SUM(P16:P18)');
                $sheet->setCellValue("P20", '=SUM(N20:O20)');
                $sheet->setCellValue("P21", '=SUM(N21:O21)');
                $sheet->setCellValue("P22", '=SUM(N22:O22)');
                $sheet->setCellValue("P23", '=SUM(P20:P22)');
                $sheet->setCellValue("P24", '=SUM(N24:O24)');
                $sheet->setCellValue("P25", '=SUM(N25:O25)');
                $sheet->setCellValue("P26", '=SUM(N26:O26)');
                $sheet->setCellValue("P27", '=SUM(P24:P26)');
                $sheet->setCellValue("P28", '=SUM(N28:O28)');
                $sheet->setCellValue("P29", '=SUM(N29:O29)');
                $sheet->setCellValue("P30", '=SUM(N30:O30)');
                $sheet->setCellValue("P31", '=SUM(P28:P30)');
                //======================================================================
                //JUMLAH STATUS HASIL UJI
                //MOBIL PENUMPANG
                $sheet->setCellValue("Q8", $dataKendaraan->mp_l_1);
                $sheet->setCellValue("R8", $dataKendaraan->mp_tl_1);
                $sheet->setCellValue("Q9", $dataKendaraan->mp_l_6);
                $sheet->setCellValue("R9", $dataKendaraan->mp_tl_6);
                $sheet->setCellValue("Q10", $dataKendaraan->mp_l_10);
                $sheet->setCellValue("R10", $dataKendaraan->mp_tl_10);
                $sheet->setCellValue("Q11", '=SUM(Q8:Q10)');
                $sheet->setCellValue("R11", '=SUM(R8:R10)');
                //MOBIL BUS
                $sheet->setCellValue("Q12", $dataKendaraan->bus_l_1);
                $sheet->setCellValue("R12", $dataKendaraan->bus_tl_1);
                $sheet->setCellValue("Q13", $dataKendaraan->bus_l_6);
                $sheet->setCellValue("R13", $dataKendaraan->bus_tl_6);
                $sheet->setCellValue("Q14", $dataKendaraan->bus_l_10);
                $sheet->setCellValue("R14", $dataKendaraan->bus_tl_10);
                $sheet->setCellValue("Q15", '=SUM(Q12:Q14)');
                $sheet->setCellValue("R15", '=SUM(R12:R14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("Q16", $dataKendaraan->mk_l_1);
                $sheet->setCellValue("R16", $dataKendaraan->mk_tl_1);
                $sheet->setCellValue("Q17", $dataKendaraan->mk_l_6);
                $sheet->setCellValue("R17", $dataKendaraan->mk_tl_6);
                $sheet->setCellValue("Q18", $dataKendaraan->mk_l_10);
                $sheet->setCellValue("R18", $dataKendaraan->mk_tl_10);
                $sheet->setCellValue("Q19", '=SUM(Q16:Q18)');
                $sheet->setCellValue("R19", '=SUM(R16:R18)');
                //MOBIL BARANG
                $sheet->setCellValue("Q20", $dataKendaraan->mb_l_1);
                $sheet->setCellValue("R20", $dataKendaraan->mb_tl_1);
                $sheet->setCellValue("Q21", $dataKendaraan->mb_l_6);
                $sheet->setCellValue("R21", $dataKendaraan->mb_tl_6);
                $sheet->setCellValue("Q22", $dataKendaraan->mb_l_10);
                $sheet->setCellValue("R22", $dataKendaraan->mb_tl_10);
                $sheet->setCellValue("Q23", '=SUM(Q20:Q22)');
                $sheet->setCellValue("R23", '=SUM(R20:R22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("Q24", $dataKendaraan->kg_l_1);
                $sheet->setCellValue("R24", $dataKendaraan->kg_tl_1);
                $sheet->setCellValue("Q25", $dataKendaraan->kg_l_6);
                $sheet->setCellValue("R25", $dataKendaraan->kg_tl_6);
                $sheet->setCellValue("Q26", $dataKendaraan->kg_l_10);
                $sheet->setCellValue("R26", $dataKendaraan->kg_tl_10);
                $sheet->setCellValue("Q27", '=SUM(Q24:Q26)');
                $sheet->setCellValue("R27", '=SUM(R24:R26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("Q28", $dataKendaraan->kt_l_1);
                $sheet->setCellValue("R28", $dataKendaraan->kt_tl_1);
                $sheet->setCellValue("Q29", $dataKendaraan->kt_l_6);
                $sheet->setCellValue("R29", $dataKendaraan->kt_tl_6);
                $sheet->setCellValue("Q30", $dataKendaraan->kt_l_10);
                $sheet->setCellValue("R30", $dataKendaraan->kt_tl_10);
                $sheet->setCellValue("Q31", '=SUM(Q28:Q30)');
                $sheet->setCellValue("R31", '=SUM(R28:R30)');
                //======================================================================
                $sheet->mergeCells("A32:C32");
                $sheet->setCellValue("A32", "JUMLAH");
                $sheet->setCellValue("D32", "=SUM(D11,D15,D19,D23,D27,D31)");
                $sheet->setCellValue("E32", "=SUM(E11,E15,E19,E23,E27,E31)");
                $sheet->setCellValue("F32", "=SUM(F11,F15,F19,F23,F27,F31)");
                $sheet->setCellValue("G32", "=SUM(G11,G15,G19,G23,G27,G31)");
                $sheet->setCellValue("H32", "=SUM(H11,H15,H19,H23,H27,H31)");
                $sheet->setCellValue("I32", "=SUM(I11,I15,I19,I23,I27,I31)");
                $sheet->setCellValue("J32", "=SUM(J11,J15,J19,J23,J27,J31)");
                $sheet->setCellValue("K32", "=SUM(K11,K15,K19,K23,K27,K31)");
                $sheet->setCellValue("L32", "=SUM(L11,L15,L19,L23,L27,L31)");
                $sheet->setCellValue("M32", "=SUM(M11,M15,M19,M23,M27,M31)");
                $sheet->setCellValue("N32", "=SUM(N11,N15,N19,N23,N27,N31)");
                $sheet->setCellValue("O32", "=SUM(O11,O15,O19,O23,O27,O31)");
                $sheet->setCellValue("P32", "=SUM(P11,P15,P19,P23,P27,P31)");
                $sheet->setCellValue("Q32", "=SUM(Q11,Q15,Q19,Q23,Q27,Q31)");
                $sheet->setCellValue("R32", "=SUM(R11,R15,R19,R23,R27,R31)");
                $sheet->getStyle("A32:R32")->applyFromArray($styleCenter);
                $sheet->getStyle("A32:R32")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                //=====================================================================================================
                $sheet->getStyle("A6:R32")->applyFromArray($styleBorder);
                ob_clean();

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="kementrian_berkala_ltl_' . $thn . '_' . $bln . '.xls"');
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

        public function actionReportBerkala35016000($tgl)
        {
                $blnThn = date("n-Y", strtotime($tgl));
                $explodeBlnThn = explode('-', $blnThn);
                $bln = $explodeBlnThn[0];
                $thn = $explodeBlnThn[1];

                $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

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
                //        $sheet->setTitle('TERDAFTAR');
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToPage(false);
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setScale(82);
                //=============================================
                $sheet->mergeCells("A1:R1");
                $sheet->mergeCells("A2:R2");
                $sheet->mergeCells("A3:R3");
                $sheet->mergeCells("A4:R4");

                $sheet->setCellValue("A1", "JUMLAH PELAYANAN PENGUJIAN BERKALA KENDARAAN BERMOTOR BERDASARKAN UMUR DAN JBB (KG)");
                $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR");
                $sheet->setCellValue("A3", "DINAS PERHUBUNGAN KABUPATEN SAMPANG, JAWA TIMUR");
                $sheet->setCellValue("A4", "TAHUN : " . $thn . " / BULAN : " . strtoupper(Yii::app()->params['bulanArrayInd'][$bln - 1]));

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
                $sheet->setCellValue("B6", "JENIS KEND");
                $sheet->getStyle("B6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("B")->setWidth(15);
                //=====================================================================================================
                $sheet->mergeCells("C6:C7");
                $sheet->setCellValue("C6", "UMUR (TAHUN)");
                $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("C")->setWidth(10);
                //=====================================================================================================
                $sheet->mergeCells("D6:E6");
                $sheet->setCellValue("D6", "S/D 3500");
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D7", "UMUM");
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("E7", "B.UMUM");
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("D")->setWidth(12);
                $sheet->getColumnDimension("E")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("F6:G6");
                $sheet->setCellValue("F6", "3501 S/D 6000");
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F7", "UMUM");
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("G7", "B.UMUM");
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("F")->setWidth(12);
                $sheet->getColumnDimension("G")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("H6:I6");
                $sheet->setCellValue("H6", "6001 S/D 9000");
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H7", "UMUM");
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("I7", "B.UMUM");
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("H")->setWidth(12);
                $sheet->getColumnDimension("I")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("J6:K6");
                $sheet->setCellValue("J6", "9001 S/D 12000");
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J7", "UMUM");
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("K7", "B.UMUM");
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("J")->setWidth(12);
                $sheet->getColumnDimension("K")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("L6:M6");
                $sheet->setCellValue("L6", "DIATAS 12001");
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L7", "UMUM");
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("M7", "B.UMUM");
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("L")->setWidth(12);
                $sheet->getColumnDimension("M")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("N6:P6");
                $sheet->setCellValue("N6", "JUMLAH");
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("N7", "UMUM");
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("O7", "B.UMUM");
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("P7", "U+BU");
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("N")->setWidth(12);
                $sheet->getColumnDimension("O")->setWidth(12);
                $sheet->getColumnDimension("P")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("Q6:R6");
                $sheet->setCellValue("Q6", "JUMLAH STATUS HASIL UJI");
                $sheet->getStyle("Q6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("Q7", "LULUS");
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("R7", "T.LULUS");
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("Q")->setWidth(12);
                $sheet->getColumnDimension("R")->setWidth(12);
                //=====================================================================================================

                $sheet->getStyle("A6:R7")->applyFromArray($styleCenter);
                $sheet->getStyle("A6:R7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                /*
         * BODY 
         */
                $criteria = new CDbCriteria();
                $criteria->select = "SUM(mp_u_1) AS mp_u_1, SUM(mp_tu_1) AS mp_tu_1, SUM(mp_u_6) AS mp_u_6, SUM(mp_tu_6) AS mp_tu_6, SUM(mp_u_10) AS mp_u_10, SUM(mp_tu_10) AS mp_tu_10,"
                        . "SUM(mb_u_1) AS mb_u_1, SUM(mb_tu_1) AS mb_tu_1, SUM(mb_u_6) AS mb_u_6, SUM(mb_tu_6) AS mb_tu_6, SUM(mb_u_10) AS mb_u_10, SUM(mb_tu_10) AS mb_tu_10,"
                        . "SUM(bus_u_1) AS bus_u_1, SUM(bus_tu_1) AS bus_tu_1, SUM(bus_u_6) AS bus_u_6, SUM(bus_tu_6) AS bus_tu_6, SUM(bus_u_10) AS bus_u_10, SUM(bus_tu_10) AS bus_tu_10,"
                        . "SUM(mk_u_1) AS mk_u_1, SUM(mk_tu_1) AS mk_tu_1, SUM(mk_u_6) AS mk_u_6, SUM(mk_tu_6) AS mk_tu_6, SUM(mk_u_10) AS mk_u_10, SUM(mk_tu_10) AS mk_tu_10,"
                        . "SUM(kg_u_1) AS kg_u_1,SUM(kg_tu_1) AS kg_tu_1,SUM(kg_u_6) AS kg_u_6,SUM(kg_tu_6) AS kg_tu_6,SUM(kg_u_10) AS kg_u_10,SUM(kg_tu_10) AS kg_tu_10,"
                        . "SUM(kt_u_1) AS kt_u_1,SUM(kt_tu_1) AS kt_tu_1,SUM(kt_u_6) AS kt_u_6,SUM(kt_tu_6) AS kt_tu_6,SUM(kt_u_10) AS kt_u_10,SUM(kt_tu_10) AS kt_tu_10";
                $criteria->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan = VKementrianBerkala35016000::model()->find($criteria);
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A8:A11");
                $sheet->setCellValue("A8", "1");
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B8:B11");
                $sheet->setCellValue("B8", "MOBIL PENUMPANG");
                $sheet->getStyle("B8")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C8", "1-5");
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F8", $dataKendaraan->mp_u_1);
                $sheet->setCellValue("G8", $dataKendaraan->mp_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C9", "6-10");
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F9", $dataKendaraan->mp_u_6);
                $sheet->setCellValue("G9", $dataKendaraan->mp_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C10", ">10");
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F10", $dataKendaraan->mp_u_10);
                $sheet->setCellValue("G10", $dataKendaraan->mp_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C11", "JUMLAH");
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C11:R11")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D11", '=SUM(D8:D10)');
                $sheet->setCellValue("E11", '=SUM(E8:E10)');
                $sheet->setCellValue("F11", '=SUM(F8:F10)');
                $sheet->setCellValue("G11", '=SUM(G8:G10)');
                $sheet->setCellValue("H11", '=SUM(H8:H10)');
                $sheet->setCellValue("I11", '=SUM(I8:I10)');
                $sheet->setCellValue("J11", '=SUM(J8:J10)');
                $sheet->setCellValue("K11", '=SUM(K8:K10)');
                $sheet->setCellValue("L11", '=SUM(L8:L10)');
                $sheet->setCellValue("M11", '=SUM(M8:M10)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A12:A15");
                $sheet->setCellValue("A12", "2");
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B12:B15");
                $sheet->setCellValue("B12", "MOBIL BUS");
                $sheet->getStyle("B12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C12", "1-5");
                $sheet->getStyle("C12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F12", $dataKendaraan->bus_u_1);
                $sheet->setCellValue("G12", $dataKendaraan->bus_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C13", "6-10");
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F13", $dataKendaraan->bus_u_6);
                $sheet->setCellValue("G13", $dataKendaraan->bus_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C14", ">10");
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F14", $dataKendaraan->bus_u_10);
                $sheet->setCellValue("G14", $dataKendaraan->bus_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C15", "JUMLAH");
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C15:R15")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D15", '=SUM(D12:D14)');
                $sheet->setCellValue("E15", '=SUM(E12:E14)');
                $sheet->setCellValue("F15", '=SUM(F12:F14)');
                $sheet->setCellValue("G15", '=SUM(G12:G14)');
                $sheet->setCellValue("H15", '=SUM(H12:H14)');
                $sheet->setCellValue("I15", '=SUM(I12:I14)');
                $sheet->setCellValue("J15", '=SUM(J12:J14)');
                $sheet->setCellValue("K15", '=SUM(K12:K14)');
                $sheet->setCellValue("L15", '=SUM(L12:L14)');
                $sheet->setCellValue("M15", '=SUM(M12:M14)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A16:A19");
                $sheet->setCellValue("A16", "3");
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B16:B19");
                $sheet->setCellValue("B16", "MOBIL KHUSUS");
                $sheet->getStyle("B16")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C16", "1-5");
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F16", $dataKendaraan->mk_u_1);
                $sheet->setCellValue("G16", $dataKendaraan->mk_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C17", "6-10");
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F17", $dataKendaraan->mk_u_6);
                $sheet->setCellValue("G17", $dataKendaraan->mk_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C18", ">10");
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F18", $dataKendaraan->mk_u_10);
                $sheet->setCellValue("G18", $dataKendaraan->mk_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C19", "JUMLAH");
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C19:R19")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D19", '=SUM(D16:D18)');
                $sheet->setCellValue("E19", '=SUM(E16:E18)');
                $sheet->setCellValue("F19", '=SUM(F16:F18)');
                $sheet->setCellValue("G19", '=SUM(G16:G18)');
                $sheet->setCellValue("H19", '=SUM(H16:H18)');
                $sheet->setCellValue("I19", '=SUM(I16:I18)');
                $sheet->setCellValue("J19", '=SUM(J16:J18)');
                $sheet->setCellValue("K19", '=SUM(K16:K18)');
                $sheet->setCellValue("L19", '=SUM(L16:L18)');
                $sheet->setCellValue("M19", '=SUM(M16:M18)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A20:A23");
                $sheet->setCellValue("A20", "4");
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B20:B23");
                $sheet->setCellValue("B20", "MOBIL BARANG");
                $sheet->getStyle("B20")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C20", "1-5");
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F20", $dataKendaraan->mb_u_1);
                $sheet->setCellValue("G20", $dataKendaraan->mb_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C21", "6-10");
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F21", $dataKendaraan->mb_u_6);
                $sheet->setCellValue("G21", $dataKendaraan->mb_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C22", ">10");
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F22", $dataKendaraan->mb_u_10);
                $sheet->setCellValue("G22", $dataKendaraan->mb_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C23", "JUMLAH");
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C23:R23")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D23", '=SUM(D20:D22)');
                $sheet->setCellValue("E23", '=SUM(E20:E22)');
                $sheet->setCellValue("F23", '=SUM(F20:F22)');
                $sheet->setCellValue("G23", '=SUM(G20:G22)');
                $sheet->setCellValue("H23", '=SUM(H20:H22)');
                $sheet->setCellValue("I23", '=SUM(I20:I22)');
                $sheet->setCellValue("J23", '=SUM(J20:J22)');
                $sheet->setCellValue("K23", '=SUM(K20:K22)');
                $sheet->setCellValue("L23", '=SUM(L20:L22)');
                $sheet->setCellValue("M23", '=SUM(M20:M22)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A24:A27");
                $sheet->setCellValue("A24", "5");
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B24:B27");
                $sheet->setCellValue("B24", "KERETA GANDENG");
                $sheet->getStyle("B24")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C24", "1-5");
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F24", $dataKendaraan->kg_u_1);
                $sheet->setCellValue("G24", $dataKendaraan->kg_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C25", "6-10");
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F25", $dataKendaraan->kg_u_6);
                $sheet->setCellValue("G25", $dataKendaraan->kg_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C26", ">10");
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F26", $dataKendaraan->kg_u_10);
                $sheet->setCellValue("G26", $dataKendaraan->kg_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C27", "JUMLAH");
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C27:R27")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D27", '=SUM(D24:D26)');
                $sheet->setCellValue("E27", '=SUM(E24:E26)');
                $sheet->setCellValue("F27", '=SUM(F24:F26)');
                $sheet->setCellValue("G27", '=SUM(G24:G26)');
                $sheet->setCellValue("H27", '=SUM(H24:H26)');
                $sheet->setCellValue("I27", '=SUM(I24:I26)');
                $sheet->setCellValue("J27", '=SUM(J24:J26)');
                $sheet->setCellValue("K27", '=SUM(K24:K26)');
                $sheet->setCellValue("L27", '=SUM(L24:L26)');
                $sheet->setCellValue("M27", '=SUM(M24:M26)');
                //======================================================================
                ////======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A28:A31");
                $sheet->setCellValue("A28", "6");
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B28:B31");
                $sheet->setCellValue("B28", "KERETA TEMPEL");
                $sheet->getStyle("B28")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C28", "1-5");
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F28", $dataKendaraan->kt_u_1);
                $sheet->setCellValue("G28", $dataKendaraan->kt_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C29", "6-10");
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F29", $dataKendaraan->kt_u_6);
                $sheet->setCellValue("G29", $dataKendaraan->kt_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C30", ">10");
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F30", $dataKendaraan->kt_u_10);
                $sheet->setCellValue("G30", $dataKendaraan->kt_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C31", "JUMLAH");
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C31:R31")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D31", '=SUM(D28:D30)');
                $sheet->setCellValue("E31", '=SUM(E28:E30)');
                $sheet->setCellValue("F31", '=SUM(F28:F30)');
                $sheet->setCellValue("G31", '=SUM(G28:G30)');
                $sheet->setCellValue("H31", '=SUM(H28:H30)');
                $sheet->setCellValue("I31", '=SUM(I28:I30)');
                $sheet->setCellValue("J31", '=SUM(J28:J30)');
                $sheet->setCellValue("K31", '=SUM(K28:K30)');
                $sheet->setCellValue("L31", '=SUM(L28:L30)');
                $sheet->setCellValue("M31", '=SUM(M28:M30)');
                //======================================================================
                //JUMLAH UMUM & BUKAN UMUM
                //======================================================================
                //UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("N8", '=SUM(D8,F8,H8,J8,L8)');
                $sheet->setCellValue("N9", '=SUM(D9,F9,H9,J9,L9)');
                $sheet->setCellValue("N10", '=SUM(D10,F10,H10,J10,L10)');
                $sheet->setCellValue("N11", '=SUM(N8:N10)');
                //MOBIL BUS
                $sheet->setCellValue("N12", '=SUM(D12,F12,H12,J12,L12)');
                $sheet->setCellValue("N13", '=SUM(D13,F13,H13,J13,L13)');
                $sheet->setCellValue("N14", '=SUM(D14,F14,H14,J14,L14)');
                $sheet->setCellValue("N15", '=SUM(N12:N14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("N16", '=SUM(D16,F16,H16,J16,L16)');
                $sheet->setCellValue("N17", '=SUM(D17,F17,H17,J17,L17)');
                $sheet->setCellValue("N18", '=SUM(D18,F18,H18,J18,L18)');
                $sheet->setCellValue("N19", '=SUM(N16:N18)');
                //MOBIL BARANG
                $sheet->setCellValue("N20", '=SUM(D20,F20,H20,J20,L20)');
                $sheet->setCellValue("N21", '=SUM(D21,F21,H21,J21,L21)');
                $sheet->setCellValue("N22", '=SUM(D22,F22,H22,J22,L22)');
                $sheet->setCellValue("N23", '=SUM(N20:N22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("N24", '=SUM(D24,F24,H24,J24,L24)');
                $sheet->setCellValue("N25", '=SUM(D25,F25,H25,J25,L25)');
                $sheet->setCellValue("N26", '=SUM(D26,F26,H26,J26,L26)');
                $sheet->setCellValue("N27", '=SUM(N24:N26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("N28", '=SUM(D28,F28,H28,J28,L28)');
                $sheet->setCellValue("N29", '=SUM(D29,F29,H29,J29,L29)');
                $sheet->setCellValue("N30", '=SUM(D30,F30,H30,J30,L30)');
                $sheet->setCellValue("N31", '=SUM(N28:N30)');
                //======================================================================
                //BUKAN UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("O8", '=SUM(E8,G8,I8,K8,M8)');
                $sheet->setCellValue("O9", '=SUM(E9,G9,I9,K9,M9)');
                $sheet->setCellValue("O10", '=SUM(E10,G10,I10,K10,M10)');
                $sheet->setCellValue("O11", '=SUM(O8:O10)');
                //MOBIL BUS
                $sheet->setCellValue("O12", '=SUM(E12,G12,I12,K12,M12)');
                $sheet->setCellValue("O13", '=SUM(E13,G13,I13,K13,M13)');
                $sheet->setCellValue("O14", '=SUM(E14,G14,I14,K14,M14)');
                $sheet->setCellValue("O15", '=SUM(O12:O14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("O16", '=SUM(E16,G16,I16,K16,M16)');
                $sheet->setCellValue("O17", '=SUM(E17,G17,I17,K17,M17)');
                $sheet->setCellValue("O18", '=SUM(E18,G18,I18,K18,M18)');
                $sheet->setCellValue("O19", '=SUM(O16:O18)');
                //MOBIL BARANG
                $sheet->setCellValue("O20", '=SUM(E20,G20,I20,K20,M20)');
                $sheet->setCellValue("O21", '=SUM(E21,G21,I21,K21,M21)');
                $sheet->setCellValue("O22", '=SUM(E22,G22,I22,K22,M22)');
                $sheet->setCellValue("O23", '=SUM(O20:O22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("O24", '=SUM(E24,G24,I24,K24,M24)');
                $sheet->setCellValue("O25", '=SUM(E25,G25,I25,K25,M25)');
                $sheet->setCellValue("O26", '=SUM(E26,G26,I26,K26,M26)');
                $sheet->setCellValue("O27", '=SUM(O24:O26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("O28", '=SUM(E28,G28,I28,K28,M28)');
                $sheet->setCellValue("O29", '=SUM(E29,G29,I29,K29,M29)');
                $sheet->setCellValue("O30", '=SUM(E30,G30,I30,K30,M30)');
                $sheet->setCellValue("O31", '=SUM(O28:O30)');
                //UMUM + BUKAN UMUM
                $sheet->setCellValue("P8", '=SUM(N8,O8)');
                $sheet->setCellValue("P9", '=SUM(N9,O9)');
                $sheet->setCellValue("P10", '=SUM(N10,O10)');
                $sheet->setCellValue("P11", '=SUM(P8:P10)');
                $sheet->setCellValue("P12", '=SUM(N12,O12)');
                $sheet->setCellValue("P13", '=SUM(N13,O13)');
                $sheet->setCellValue("P14", '=SUM(N14,O14)');
                $sheet->setCellValue("P15", '=SUM(P12:P14)');
                $sheet->setCellValue("P16", '=SUM(N16,O16)');
                $sheet->setCellValue("P17", '=SUM(N17,O17)');
                $sheet->setCellValue("P18", '=SUM(N18,O18)');
                $sheet->setCellValue("P19", '=SUM(P16:P18)');
                $sheet->setCellValue("P20", '=SUM(N20:O20)');
                $sheet->setCellValue("P21", '=SUM(N21:O21)');
                $sheet->setCellValue("P22", '=SUM(N22:O22)');
                $sheet->setCellValue("P23", '=SUM(P20:P22)');
                $sheet->setCellValue("P24", '=SUM(N24:O24)');
                $sheet->setCellValue("P25", '=SUM(N25:O25)');
                $sheet->setCellValue("P26", '=SUM(N26:O26)');
                $sheet->setCellValue("P27", '=SUM(P24:P26)');
                $sheet->setCellValue("P28", '=SUM(N28:O28)');
                $sheet->setCellValue("P29", '=SUM(N29:O29)');
                $sheet->setCellValue("P30", '=SUM(N30:O30)');
                $sheet->setCellValue("P31", '=SUM(P28:P30)');
                //======================================================================
                //JUMLAH STATUS HASIL UJI
                //MOBIL PENUMPANG
                //        $sheet->setCellValue("Q8", $dataKendaraan->mp_l_1);
                //        $sheet->setCellValue("R8", $dataKendaraan->mp_tl_1);
                //        $sheet->setCellValue("Q9", $dataKendaraan->mp_l_6);
                //        $sheet->setCellValue("R9", $dataKendaraan->mp_tl_6);
                //        $sheet->setCellValue("Q10", $dataKendaraan->mp_l_10);
                //        $sheet->setCellValue("R10", $dataKendaraan->mp_tl_10);
                $sheet->setCellValue("Q11", '=SUM(Q8:Q10)');
                $sheet->setCellValue("R11", '=SUM(R8:R10)');
                //MOBIL BUS
                //        $sheet->setCellValue("Q12", $dataKendaraan->bus_l_1);
                //        $sheet->setCellValue("R12", $dataKendaraan->bus_tl_1);
                //        $sheet->setCellValue("Q13", $dataKendaraan->bus_l_6);
                //        $sheet->setCellValue("R13", $dataKendaraan->bus_tl_6);
                //        $sheet->setCellValue("Q14", $dataKendaraan->bus_l_10);
                //        $sheet->setCellValue("R14", $dataKendaraan->bus_tl_10);
                $sheet->setCellValue("Q15", '=SUM(Q12:Q14)');
                $sheet->setCellValue("R15", '=SUM(R12:R14)');
                //MOBIL KHUSUS
                //        $sheet->setCellValue("Q16", $dataKendaraan->mk_l_1);
                //        $sheet->setCellValue("R16", $dataKendaraan->mk_tl_1);
                //        $sheet->setCellValue("Q17", $dataKendaraan->mk_l_6);
                //        $sheet->setCellValue("R17", $dataKendaraan->mk_tl_6);
                //        $sheet->setCellValue("Q18", $dataKendaraan->mk_l_10);
                //        $sheet->setCellValue("R18", $dataKendaraan->mk_tl_10);
                $sheet->setCellValue("Q19", '=SUM(Q16:Q18)');
                $sheet->setCellValue("R19", '=SUM(R16:R18)');
                //MOBIL BARANG
                //        $sheet->setCellValue("Q20", $dataKendaraan->mb_l_1);
                //        $sheet->setCellValue("R20", $dataKendaraan->mb_tl_1);
                //        $sheet->setCellValue("Q21", $dataKendaraan->mb_l_6);
                //        $sheet->setCellValue("R21", $dataKendaraan->mb_tl_6);
                //        $sheet->setCellValue("Q22", $dataKendaraan->mb_l_10);
                //        $sheet->setCellValue("R22", $dataKendaraan->mb_tl_10);
                $sheet->setCellValue("Q23", '=SUM(Q20:Q22)');
                $sheet->setCellValue("R23", '=SUM(R20:R22)');
                //KERETA GANDENGAN
                //        $sheet->setCellValue("Q24", $dataKendaraan->kg_l_1);
                //        $sheet->setCellValue("R24", $dataKendaraan->kg_tl_1);
                //        $sheet->setCellValue("Q25", $dataKendaraan->kg_l_6);
                //        $sheet->setCellValue("R25", $dataKendaraan->kg_tl_6);
                //        $sheet->setCellValue("Q26", $dataKendaraan->kg_l_10);
                //        $sheet->setCellValue("R26", $dataKendaraan->kg_tl_10);
                $sheet->setCellValue("Q27", '=SUM(Q24:Q26)');
                $sheet->setCellValue("R27", '=SUM(R24:R26)');
                //KERETA TEMPELAN
                //        $sheet->setCellValue("Q28", $dataKendaraan->kt_l_1);
                //        $sheet->setCellValue("R28", $dataKendaraan->kt_tl_1);
                //        $sheet->setCellValue("Q29", $dataKendaraan->kt_l_6);
                //        $sheet->setCellValue("R29", $dataKendaraan->kt_tl_6);
                //        $sheet->setCellValue("Q30", $dataKendaraan->kt_l_10);
                //        $sheet->setCellValue("R30", $dataKendaraan->kt_tl_10);
                $sheet->setCellValue("Q31", '=SUM(Q28:Q30)');
                $sheet->setCellValue("R31", '=SUM(R28:R30)');
                //======================================================================
                $sheet->mergeCells("A32:C32");
                $sheet->setCellValue("A32", "JUMLAH");
                $sheet->setCellValue("D32", "=SUM(D11,D15,D19,D23,D27,D31)");
                $sheet->setCellValue("E32", "=SUM(E11,E15,E19,E23,E27,E31)");
                $sheet->setCellValue("F32", "=SUM(F11,F15,F19,F23,F27,F31)");
                $sheet->setCellValue("G32", "=SUM(G11,G15,G19,G23,G27,G31)");
                $sheet->setCellValue("H32", "=SUM(H11,H15,H19,H23,H27,H31)");
                $sheet->setCellValue("I32", "=SUM(I11,I15,I19,I23,I27,I31)");
                $sheet->setCellValue("J32", "=SUM(J11,J15,J19,J23,J27,J31)");
                $sheet->setCellValue("K32", "=SUM(K11,K15,K19,K23,K27,K31)");
                $sheet->setCellValue("L32", "=SUM(L11,L15,L19,L23,L27,L31)");
                $sheet->setCellValue("M32", "=SUM(M11,M15,M19,M23,M27,M31)");
                $sheet->setCellValue("N32", "=SUM(N11,N15,N19,N23,N27,N31)");
                $sheet->setCellValue("O32", "=SUM(O11,O15,O19,O23,O27,O31)");
                $sheet->setCellValue("P32", "=SUM(P11,P15,P19,P23,P27,P31)");
                $sheet->setCellValue("Q32", "=SUM(Q11,Q15,Q19,Q23,Q27,Q31)");
                $sheet->setCellValue("R32", "=SUM(R11,R15,R19,R23,R27,R31)");
                $sheet->getStyle("A32:R32")->applyFromArray($styleCenter);
                $sheet->getStyle("A32:R32")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                //=====================================================================================================
                $sheet->getStyle("A6:R32")->applyFromArray($styleBorder);
                ob_clean();

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="kementrian_berkala_3501_6000_' . $thn . '_' . $bln . '.xls"');
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

        public function actionReportBerkala60019000($tgl)
        {
                $blnThn = date("n-Y", strtotime($tgl));
                $explodeBlnThn = explode('-', $blnThn);
                $bln = $explodeBlnThn[0];
                $thn = $explodeBlnThn[1];

                $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

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
                //        $sheet->setTitle('TERDAFTAR');
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToPage(false);
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setScale(82);
                //=============================================
                $sheet->mergeCells("A1:R1");
                $sheet->mergeCells("A2:R2");
                $sheet->mergeCells("A3:R3");
                $sheet->mergeCells("A4:R4");

                $sheet->setCellValue("A1", "JUMLAH PELAYANAN PENGUJIAN BERKALA KENDARAAN BERMOTOR BERDASARKAN UMUR DAN JBB (KG)");
                $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR");
                $sheet->setCellValue("A3", "DINAS PERHUBUNGAN KABUPATEN SAMPANG, JAWA TIMUR");
                $sheet->setCellValue("A4", "TAHUN : " . $thn . " / BULAN : " . strtoupper(Yii::app()->params['bulanArrayInd'][$bln - 1]));

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
                $sheet->setCellValue("B6", "JENIS KEND");
                $sheet->getStyle("B6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("B")->setWidth(15);
                //=====================================================================================================
                $sheet->mergeCells("C6:C7");
                $sheet->setCellValue("C6", "UMUR (TAHUN)");
                $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("C")->setWidth(10);
                //=====================================================================================================
                $sheet->mergeCells("D6:E6");
                $sheet->setCellValue("D6", "S/D 3500");
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D7", "UMUM");
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("E7", "B.UMUM");
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("D")->setWidth(12);
                $sheet->getColumnDimension("E")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("F6:G6");
                $sheet->setCellValue("F6", "3501 S/D 6000");
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F7", "UMUM");
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("G7", "B.UMUM");
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("F")->setWidth(12);
                $sheet->getColumnDimension("G")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("H6:I6");
                $sheet->setCellValue("H6", "6001 S/D 9000");
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H7", "UMUM");
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("I7", "B.UMUM");
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("H")->setWidth(12);
                $sheet->getColumnDimension("I")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("J6:K6");
                $sheet->setCellValue("J6", "9001 S/D 12000");
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J7", "UMUM");
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("K7", "B.UMUM");
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("J")->setWidth(12);
                $sheet->getColumnDimension("K")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("L6:M6");
                $sheet->setCellValue("L6", "DIATAS 12001");
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L7", "UMUM");
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("M7", "B.UMUM");
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("L")->setWidth(12);
                $sheet->getColumnDimension("M")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("N6:P6");
                $sheet->setCellValue("N6", "JUMLAH");
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("N7", "UMUM");
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("O7", "B.UMUM");
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("P7", "U+BU");
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("N")->setWidth(12);
                $sheet->getColumnDimension("O")->setWidth(12);
                $sheet->getColumnDimension("P")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("Q6:R6");
                $sheet->setCellValue("Q6", "JUMLAH STATUS HASIL UJI");
                $sheet->getStyle("Q6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("Q7", "LULUS");
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("R7", "T.LULUS");
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("Q")->setWidth(12);
                $sheet->getColumnDimension("R")->setWidth(12);
                //=====================================================================================================

                $sheet->getStyle("A6:R7")->applyFromArray($styleCenter);
                $sheet->getStyle("A6:R7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                /*
         * BODY 
         */
                $criteria = new CDbCriteria();
                $criteria->select = "SUM(mp_u_1) AS mp_u_1, SUM(mp_tu_1) AS mp_tu_1, SUM(mp_u_6) AS mp_u_6, SUM(mp_tu_6) AS mp_tu_6, SUM(mp_u_10) AS mp_u_10, SUM(mp_tu_10) AS mp_tu_10,"
                        . "SUM(mb_u_1) AS mb_u_1, SUM(mb_tu_1) AS mb_tu_1, SUM(mb_u_6) AS mb_u_6, SUM(mb_tu_6) AS mb_tu_6, SUM(mb_u_10) AS mb_u_10, SUM(mb_tu_10) AS mb_tu_10,"
                        . "SUM(bus_u_1) AS bus_u_1, SUM(bus_tu_1) AS bus_tu_1, SUM(bus_u_6) AS bus_u_6, SUM(bus_tu_6) AS bus_tu_6, SUM(bus_u_10) AS bus_u_10, SUM(bus_tu_10) AS bus_tu_10,"
                        . "SUM(mk_u_1) AS mk_u_1, SUM(mk_tu_1) AS mk_tu_1, SUM(mk_u_6) AS mk_u_6, SUM(mk_tu_6) AS mk_tu_6, SUM(mk_u_10) AS mk_u_10, SUM(mk_tu_10) AS mk_tu_10,"
                        . "SUM(kg_u_1) AS kg_u_1,SUM(kg_tu_1) AS kg_tu_1,SUM(kg_u_6) AS kg_u_6,SUM(kg_tu_6) AS kg_tu_6,SUM(kg_u_10) AS kg_u_10,SUM(kg_tu_10) AS kg_tu_10,"
                        . "SUM(kt_u_1) AS kt_u_1,SUM(kt_tu_1) AS kt_tu_1,SUM(kt_u_6) AS kt_u_6,SUM(kt_tu_6) AS kt_tu_6,SUM(kt_u_10) AS kt_u_10,SUM(kt_tu_10) AS kt_tu_10";
                $criteria->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan = VKementrianBerkala60019000::model()->find($criteria);
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A8:A11");
                $sheet->setCellValue("A8", "1");
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B8:B11");
                $sheet->setCellValue("B8", "MOBIL PENUMPANG");
                $sheet->getStyle("B8")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C8", "1-5");
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H8", $dataKendaraan->mp_u_1);
                $sheet->setCellValue("I8", $dataKendaraan->mp_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C9", "6-10");
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H9", $dataKendaraan->mp_u_6);
                $sheet->setCellValue("I9", $dataKendaraan->mp_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C10", ">10");
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H10", $dataKendaraan->mp_u_10);
                $sheet->setCellValue("I10", $dataKendaraan->mp_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C11", "JUMLAH");
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C11:R11")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D11", '=SUM(D8:D10)');
                $sheet->setCellValue("E11", '=SUM(E8:E10)');
                $sheet->setCellValue("F11", '=SUM(F8:F10)');
                $sheet->setCellValue("G11", '=SUM(G8:G10)');
                $sheet->setCellValue("H11", '=SUM(H8:H10)');
                $sheet->setCellValue("I11", '=SUM(I8:I10)');
                $sheet->setCellValue("J11", '=SUM(J8:J10)');
                $sheet->setCellValue("K11", '=SUM(K8:K10)');
                $sheet->setCellValue("L11", '=SUM(L8:L10)');
                $sheet->setCellValue("M11", '=SUM(M8:M10)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A12:A15");
                $sheet->setCellValue("A12", "2");
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B12:B15");
                $sheet->setCellValue("B12", "MOBIL BUS");
                $sheet->getStyle("B12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C12", "1-5");
                $sheet->getStyle("C12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H12", $dataKendaraan->bus_u_1);
                $sheet->setCellValue("I12", $dataKendaraan->bus_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C13", "6-10");
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H13", $dataKendaraan->bus_u_6);
                $sheet->setCellValue("I13", $dataKendaraan->bus_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C14", ">10");
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H14", $dataKendaraan->bus_u_10);
                $sheet->setCellValue("I14", $dataKendaraan->bus_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C15", "JUMLAH");
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C15:R15")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D15", '=SUM(D12:D14)');
                $sheet->setCellValue("E15", '=SUM(E12:E14)');
                $sheet->setCellValue("F15", '=SUM(F12:F14)');
                $sheet->setCellValue("G15", '=SUM(G12:G14)');
                $sheet->setCellValue("H15", '=SUM(H12:H14)');
                $sheet->setCellValue("I15", '=SUM(I12:I14)');
                $sheet->setCellValue("J15", '=SUM(J12:J14)');
                $sheet->setCellValue("K15", '=SUM(K12:K14)');
                $sheet->setCellValue("L15", '=SUM(L12:L14)');
                $sheet->setCellValue("M15", '=SUM(M12:M14)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A16:A19");
                $sheet->setCellValue("A16", "3");
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B16:B19");
                $sheet->setCellValue("B16", "MOBIL KHUSUS");
                $sheet->getStyle("B16")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C16", "1-5");
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H16", $dataKendaraan->mk_u_1);
                $sheet->setCellValue("I16", $dataKendaraan->mk_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C17", "6-10");
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H17", $dataKendaraan->mk_u_6);
                $sheet->setCellValue("I17", $dataKendaraan->mk_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C18", ">10");
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H18", $dataKendaraan->mk_u_10);
                $sheet->setCellValue("I18", $dataKendaraan->mk_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C19", "JUMLAH");
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C19:R19")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D19", '=SUM(D16:D18)');
                $sheet->setCellValue("E19", '=SUM(E16:E18)');
                $sheet->setCellValue("F19", '=SUM(F16:F18)');
                $sheet->setCellValue("G19", '=SUM(G16:G18)');
                $sheet->setCellValue("H19", '=SUM(H16:H18)');
                $sheet->setCellValue("I19", '=SUM(I16:I18)');
                $sheet->setCellValue("J19", '=SUM(J16:J18)');
                $sheet->setCellValue("K19", '=SUM(K16:K18)');
                $sheet->setCellValue("L19", '=SUM(L16:L18)');
                $sheet->setCellValue("M19", '=SUM(M16:M18)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A20:A23");
                $sheet->setCellValue("A20", "4");
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B20:B23");
                $sheet->setCellValue("B20", "MOBIL BARANG");
                $sheet->getStyle("B20")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C20", "1-5");
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H20", $dataKendaraan->mb_u_1);
                $sheet->setCellValue("I20", $dataKendaraan->mb_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C21", "6-10");
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H21", $dataKendaraan->mb_u_6);
                $sheet->setCellValue("I21", $dataKendaraan->mb_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C22", ">10");
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H22", $dataKendaraan->mb_u_10);
                $sheet->setCellValue("I22", $dataKendaraan->mb_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C23", "JUMLAH");
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C23:R23")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D23", '=SUM(D20:D22)');
                $sheet->setCellValue("E23", '=SUM(E20:E22)');
                $sheet->setCellValue("F23", '=SUM(F20:F22)');
                $sheet->setCellValue("G23", '=SUM(G20:G22)');
                $sheet->setCellValue("H23", '=SUM(H20:H22)');
                $sheet->setCellValue("I23", '=SUM(I20:I22)');
                $sheet->setCellValue("J23", '=SUM(J20:J22)');
                $sheet->setCellValue("K23", '=SUM(K20:K22)');
                $sheet->setCellValue("L23", '=SUM(L20:L22)');
                $sheet->setCellValue("M23", '=SUM(M20:M22)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A24:A27");
                $sheet->setCellValue("A24", "5");
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B24:B27");
                $sheet->setCellValue("B24", "KERETA GANDENG");
                $sheet->getStyle("B24")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C24", "1-5");
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H24", $dataKendaraan->kg_u_1);
                $sheet->setCellValue("I24", $dataKendaraan->kg_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C25", "6-10");
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H25", $dataKendaraan->kg_u_6);
                $sheet->setCellValue("I25", $dataKendaraan->kg_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C26", ">10");
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H26", $dataKendaraan->kg_u_10);
                $sheet->setCellValue("I26", $dataKendaraan->kg_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C27", "JUMLAH");
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C27:R27")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D27", '=SUM(D24:D26)');
                $sheet->setCellValue("E27", '=SUM(E24:E26)');
                $sheet->setCellValue("F27", '=SUM(F24:F26)');
                $sheet->setCellValue("G27", '=SUM(G24:G26)');
                $sheet->setCellValue("H27", '=SUM(H24:H26)');
                $sheet->setCellValue("I27", '=SUM(I24:I26)');
                $sheet->setCellValue("J27", '=SUM(J24:J26)');
                $sheet->setCellValue("K27", '=SUM(K24:K26)');
                $sheet->setCellValue("L27", '=SUM(L24:L26)');
                $sheet->setCellValue("M27", '=SUM(M24:M26)');
                //======================================================================
                ////======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A28:A31");
                $sheet->setCellValue("A28", "6");
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B28:B31");
                $sheet->setCellValue("B28", "KERETA TEMPEL");
                $sheet->getStyle("B28")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C28", "1-5");
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H28", $dataKendaraan->kt_u_1);
                $sheet->setCellValue("I28", $dataKendaraan->kt_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C29", "6-10");
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H29", $dataKendaraan->kt_u_6);
                $sheet->setCellValue("I29", $dataKendaraan->kt_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C30", ">10");
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H30", $dataKendaraan->kt_u_10);
                $sheet->setCellValue("I30", $dataKendaraan->kt_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C31", "JUMLAH");
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C31:R31")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D31", '=SUM(D28:D30)');
                $sheet->setCellValue("E31", '=SUM(E28:E30)');
                $sheet->setCellValue("F31", '=SUM(F28:F30)');
                $sheet->setCellValue("G31", '=SUM(G28:G30)');
                $sheet->setCellValue("H31", '=SUM(H28:H30)');
                $sheet->setCellValue("I31", '=SUM(I28:I30)');
                $sheet->setCellValue("J31", '=SUM(J28:J30)');
                $sheet->setCellValue("K31", '=SUM(K28:K30)');
                $sheet->setCellValue("L31", '=SUM(L28:L30)');
                $sheet->setCellValue("M31", '=SUM(M28:M30)');
                //======================================================================
                //JUMLAH UMUM & BUKAN UMUM
                //======================================================================
                //UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("N8", '=SUM(D8,F8,H8,J8,L8)');
                $sheet->setCellValue("N9", '=SUM(D9,F9,H9,J9,L9)');
                $sheet->setCellValue("N10", '=SUM(D10,F10,H10,J10,L10)');
                $sheet->setCellValue("N11", '=SUM(N8:N10)');
                //MOBIL BUS
                $sheet->setCellValue("N12", '=SUM(D12,F12,H12,J12,L12)');
                $sheet->setCellValue("N13", '=SUM(D13,F13,H13,J13,L13)');
                $sheet->setCellValue("N14", '=SUM(D14,F14,H14,J14,L14)');
                $sheet->setCellValue("N15", '=SUM(N12:N14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("N16", '=SUM(D16,F16,H16,J16,L16)');
                $sheet->setCellValue("N17", '=SUM(D17,F17,H17,J17,L17)');
                $sheet->setCellValue("N18", '=SUM(D18,F18,H18,J18,L18)');
                $sheet->setCellValue("N19", '=SUM(N16:N18)');
                //MOBIL BARANG
                $sheet->setCellValue("N20", '=SUM(D20,F20,H20,J20,L20)');
                $sheet->setCellValue("N21", '=SUM(D21,F21,H21,J21,L21)');
                $sheet->setCellValue("N22", '=SUM(D22,F22,H22,J22,L22)');
                $sheet->setCellValue("N23", '=SUM(N20:N22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("N24", '=SUM(D24,F24,H24,J24,L24)');
                $sheet->setCellValue("N25", '=SUM(D25,F25,H25,J25,L25)');
                $sheet->setCellValue("N26", '=SUM(D26,F26,H26,J26,L26)');
                $sheet->setCellValue("N27", '=SUM(N24:N26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("N28", '=SUM(D28,F28,H28,J28,L28)');
                $sheet->setCellValue("N29", '=SUM(D29,F29,H29,J29,L29)');
                $sheet->setCellValue("N30", '=SUM(D30,F30,H30,J30,L30)');
                $sheet->setCellValue("N31", '=SUM(N28:N30)');
                //======================================================================
                //BUKAN UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("O8", '=SUM(E8,G8,I8,K8,M8)');
                $sheet->setCellValue("O9", '=SUM(E9,G9,I9,K9,M9)');
                $sheet->setCellValue("O10", '=SUM(E10,G10,I10,K10,M10)');
                $sheet->setCellValue("O11", '=SUM(O8:O10)');
                //MOBIL BUS
                $sheet->setCellValue("O12", '=SUM(E12,G12,I12,K12,M12)');
                $sheet->setCellValue("O13", '=SUM(E13,G13,I13,K13,M13)');
                $sheet->setCellValue("O14", '=SUM(E14,G14,I14,K14,M14)');
                $sheet->setCellValue("O15", '=SUM(O12:O14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("O16", '=SUM(E16,G16,I16,K16,M16)');
                $sheet->setCellValue("O17", '=SUM(E17,G17,I17,K17,M17)');
                $sheet->setCellValue("O18", '=SUM(E18,G18,I18,K18,M18)');
                $sheet->setCellValue("O19", '=SUM(O16:O18)');
                //MOBIL BARANG
                $sheet->setCellValue("O20", '=SUM(E20,G20,I20,K20,M20)');
                $sheet->setCellValue("O21", '=SUM(E21,G21,I21,K21,M21)');
                $sheet->setCellValue("O22", '=SUM(E22,G22,I22,K22,M22)');
                $sheet->setCellValue("O23", '=SUM(O20:O22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("O24", '=SUM(E24,G24,I24,K24,M24)');
                $sheet->setCellValue("O25", '=SUM(E25,G25,I25,K25,M25)');
                $sheet->setCellValue("O26", '=SUM(E26,G26,I26,K26,M26)');
                $sheet->setCellValue("O27", '=SUM(O24:O26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("O28", '=SUM(E28,G28,I28,K28,M28)');
                $sheet->setCellValue("O29", '=SUM(E29,G29,I29,K29,M29)');
                $sheet->setCellValue("O30", '=SUM(E30,G30,I30,K30,M30)');
                $sheet->setCellValue("O31", '=SUM(O28:O30)');
                //UMUM + BUKAN UMUM
                $sheet->setCellValue("P8", '=SUM(N8,O8)');
                $sheet->setCellValue("P9", '=SUM(N9,O9)');
                $sheet->setCellValue("P10", '=SUM(N10,O10)');
                $sheet->setCellValue("P11", '=SUM(P8:P10)');
                $sheet->setCellValue("P12", '=SUM(N12,O12)');
                $sheet->setCellValue("P13", '=SUM(N13,O13)');
                $sheet->setCellValue("P14", '=SUM(N14,O14)');
                $sheet->setCellValue("P15", '=SUM(P12:P14)');
                $sheet->setCellValue("P16", '=SUM(N16,O16)');
                $sheet->setCellValue("P17", '=SUM(N17,O17)');
                $sheet->setCellValue("P18", '=SUM(N18,O18)');
                $sheet->setCellValue("P19", '=SUM(P16:P18)');
                $sheet->setCellValue("P20", '=SUM(N20:O20)');
                $sheet->setCellValue("P21", '=SUM(N21:O21)');
                $sheet->setCellValue("P22", '=SUM(N22:O22)');
                $sheet->setCellValue("P23", '=SUM(P20:P22)');
                $sheet->setCellValue("P24", '=SUM(N24:O24)');
                $sheet->setCellValue("P25", '=SUM(N25:O25)');
                $sheet->setCellValue("P26", '=SUM(N26:O26)');
                $sheet->setCellValue("P27", '=SUM(P24:P26)');
                $sheet->setCellValue("P28", '=SUM(N28:O28)');
                $sheet->setCellValue("P29", '=SUM(N29:O29)');
                $sheet->setCellValue("P30", '=SUM(N30:O30)');
                $sheet->setCellValue("P31", '=SUM(P28:P30)');
                //======================================================================
                //JUMLAH STATUS HASIL UJI
                //MOBIL PENUMPANG
                //        $sheet->setCellValue("Q8", $dataKendaraan->mp_l_1);
                //        $sheet->setCellValue("R8", $dataKendaraan->mp_tl_1);
                //        $sheet->setCellValue("Q9", $dataKendaraan->mp_l_6);
                //        $sheet->setCellValue("R9", $dataKendaraan->mp_tl_6);
                //        $sheet->setCellValue("Q10", $dataKendaraan->mp_l_10);
                //        $sheet->setCellValue("R10", $dataKendaraan->mp_tl_10);
                $sheet->setCellValue("Q11", '=SUM(Q8:Q10)');
                $sheet->setCellValue("R11", '=SUM(R8:R10)');
                //MOBIL BUS
                //        $sheet->setCellValue("Q12", $dataKendaraan->bus_l_1);
                //        $sheet->setCellValue("R12", $dataKendaraan->bus_tl_1);
                //        $sheet->setCellValue("Q13", $dataKendaraan->bus_l_6);
                //        $sheet->setCellValue("R13", $dataKendaraan->bus_tl_6);
                //        $sheet->setCellValue("Q14", $dataKendaraan->bus_l_10);
                //        $sheet->setCellValue("R14", $dataKendaraan->bus_tl_10);
                $sheet->setCellValue("Q15", '=SUM(Q12:Q14)');
                $sheet->setCellValue("R15", '=SUM(R12:R14)');
                //MOBIL KHUSUS
                //        $sheet->setCellValue("Q16", $dataKendaraan->mk_l_1);
                //        $sheet->setCellValue("R16", $dataKendaraan->mk_tl_1);
                //        $sheet->setCellValue("Q17", $dataKendaraan->mk_l_6);
                //        $sheet->setCellValue("R17", $dataKendaraan->mk_tl_6);
                //        $sheet->setCellValue("Q18", $dataKendaraan->mk_l_10);
                //        $sheet->setCellValue("R18", $dataKendaraan->mk_tl_10);
                $sheet->setCellValue("Q19", '=SUM(Q16:Q18)');
                $sheet->setCellValue("R19", '=SUM(R16:R18)');
                //MOBIL BARANG
                //        $sheet->setCellValue("Q20", $dataKendaraan->mb_l_1);
                //        $sheet->setCellValue("R20", $dataKendaraan->mb_tl_1);
                //        $sheet->setCellValue("Q21", $dataKendaraan->mb_l_6);
                //        $sheet->setCellValue("R21", $dataKendaraan->mb_tl_6);
                //        $sheet->setCellValue("Q22", $dataKendaraan->mb_l_10);
                //        $sheet->setCellValue("R22", $dataKendaraan->mb_tl_10);
                $sheet->setCellValue("Q23", '=SUM(Q20:Q22)');
                $sheet->setCellValue("R23", '=SUM(R20:R22)');
                //KERETA GANDENGAN
                //        $sheet->setCellValue("Q24", $dataKendaraan->kg_l_1);
                //        $sheet->setCellValue("R24", $dataKendaraan->kg_tl_1);
                //        $sheet->setCellValue("Q25", $dataKendaraan->kg_l_6);
                //        $sheet->setCellValue("R25", $dataKendaraan->kg_tl_6);
                //        $sheet->setCellValue("Q26", $dataKendaraan->kg_l_10);
                //        $sheet->setCellValue("R26", $dataKendaraan->kg_tl_10);
                $sheet->setCellValue("Q27", '=SUM(Q24:Q26)');
                $sheet->setCellValue("R27", '=SUM(R24:R26)');
                //KERETA TEMPELAN
                //        $sheet->setCellValue("Q28", $dataKendaraan->kt_l_1);
                //        $sheet->setCellValue("R28", $dataKendaraan->kt_tl_1);
                //        $sheet->setCellValue("Q29", $dataKendaraan->kt_l_6);
                //        $sheet->setCellValue("R29", $dataKendaraan->kt_tl_6);
                //        $sheet->setCellValue("Q30", $dataKendaraan->kt_l_10);
                //        $sheet->setCellValue("R30", $dataKendaraan->kt_tl_10);
                $sheet->setCellValue("Q31", '=SUM(Q28:Q30)');
                $sheet->setCellValue("R31", '=SUM(R28:R30)');
                //======================================================================
                $sheet->mergeCells("A32:C32");
                $sheet->setCellValue("A32", "JUMLAH");
                $sheet->setCellValue("D32", "=SUM(D11,D15,D19,D23,D27,D31)");
                $sheet->setCellValue("E32", "=SUM(E11,E15,E19,E23,E27,E31)");
                $sheet->setCellValue("F32", "=SUM(F11,F15,F19,F23,F27,F31)");
                $sheet->setCellValue("G32", "=SUM(G11,G15,G19,G23,G27,G31)");
                $sheet->setCellValue("H32", "=SUM(H11,H15,H19,H23,H27,H31)");
                $sheet->setCellValue("I32", "=SUM(I11,I15,I19,I23,I27,I31)");
                $sheet->setCellValue("J32", "=SUM(J11,J15,J19,J23,J27,J31)");
                $sheet->setCellValue("K32", "=SUM(K11,K15,K19,K23,K27,K31)");
                $sheet->setCellValue("L32", "=SUM(L11,L15,L19,L23,L27,L31)");
                $sheet->setCellValue("M32", "=SUM(M11,M15,M19,M23,M27,M31)");
                $sheet->setCellValue("N32", "=SUM(N11,N15,N19,N23,N27,N31)");
                $sheet->setCellValue("O32", "=SUM(O11,O15,O19,O23,O27,O31)");
                $sheet->setCellValue("P32", "=SUM(P11,P15,P19,P23,P27,P31)");
                $sheet->setCellValue("Q32", "=SUM(Q11,Q15,Q19,Q23,Q27,Q31)");
                $sheet->setCellValue("R32", "=SUM(R11,R15,R19,R23,R27,R31)");
                $sheet->getStyle("A32:R32")->applyFromArray($styleCenter);
                $sheet->getStyle("A32:R32")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                //=====================================================================================================
                $sheet->getStyle("A6:R32")->applyFromArray($styleBorder);
                ob_clean();

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="kementrian_berkala_6001_9000_' . $thn . '_' . $bln . '.xls"');
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

        public function actionReportBerkala900112000($tgl)
        {
                $blnThn = date("n-Y", strtotime($tgl));
                $explodeBlnThn = explode('-', $blnThn);
                $bln = $explodeBlnThn[0];
                $thn = $explodeBlnThn[1];

                $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

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
                //        $sheet->setTitle('TERDAFTAR');
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToPage(false);
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setScale(82);
                //=============================================
                $sheet->mergeCells("A1:R1");
                $sheet->mergeCells("A2:R2");
                $sheet->mergeCells("A3:R3");
                $sheet->mergeCells("A4:R4");

                $sheet->setCellValue("A1", "JUMLAH PELAYANAN PENGUJIAN BERKALA KENDARAAN BERMOTOR BERDASARKAN UMUR DAN JBB (KG)");
                $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR");
                $sheet->setCellValue("A3", "DINAS PERHUBUNGAN KABUPATEN SAMPANG, JAWA TIMUR");
                $sheet->setCellValue("A4", "TAHUN : " . $thn . " / BULAN : " . strtoupper(Yii::app()->params['bulanArrayInd'][$bln - 1]));

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
                $sheet->setCellValue("B6", "JENIS KEND");
                $sheet->getStyle("B6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("B")->setWidth(15);
                //=====================================================================================================
                $sheet->mergeCells("C6:C7");
                $sheet->setCellValue("C6", "UMUR (TAHUN)");
                $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("C")->setWidth(10);
                //=====================================================================================================
                $sheet->mergeCells("D6:E6");
                $sheet->setCellValue("D6", "S/D 3500");
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D7", "UMUM");
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("E7", "B.UMUM");
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("D")->setWidth(12);
                $sheet->getColumnDimension("E")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("F6:G6");
                $sheet->setCellValue("F6", "3501 S/D 6000");
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F7", "UMUM");
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("G7", "B.UMUM");
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("F")->setWidth(12);
                $sheet->getColumnDimension("G")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("H6:I6");
                $sheet->setCellValue("H6", "6001 S/D 9000");
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H7", "UMUM");
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("I7", "B.UMUM");
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("H")->setWidth(12);
                $sheet->getColumnDimension("I")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("J6:K6");
                $sheet->setCellValue("J6", "9001 S/D 12000");
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J7", "UMUM");
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("K7", "B.UMUM");
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("J")->setWidth(12);
                $sheet->getColumnDimension("K")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("L6:M6");
                $sheet->setCellValue("L6", "DIATAS 12001");
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L7", "UMUM");
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("M7", "B.UMUM");
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("L")->setWidth(12);
                $sheet->getColumnDimension("M")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("N6:P6");
                $sheet->setCellValue("N6", "JUMLAH");
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("N7", "UMUM");
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("O7", "B.UMUM");
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("P7", "U+BU");
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("N")->setWidth(12);
                $sheet->getColumnDimension("O")->setWidth(12);
                $sheet->getColumnDimension("P")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("Q6:R6");
                $sheet->setCellValue("Q6", "JUMLAH STATUS HASIL UJI");
                $sheet->getStyle("Q6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("Q7", "LULUS");
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("R7", "T.LULUS");
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("Q")->setWidth(12);
                $sheet->getColumnDimension("R")->setWidth(12);
                //=====================================================================================================

                $sheet->getStyle("A6:R7")->applyFromArray($styleCenter);
                $sheet->getStyle("A6:R7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                /*
         * BODY 
         */
                $criteria = new CDbCriteria();
                $criteria->select = "SUM(mp_u_1) AS mp_u_1, SUM(mp_tu_1) AS mp_tu_1, SUM(mp_u_6) AS mp_u_6, SUM(mp_tu_6) AS mp_tu_6, SUM(mp_u_10) AS mp_u_10, SUM(mp_tu_10) AS mp_tu_10,"
                        . "SUM(mb_u_1) AS mb_u_1, SUM(mb_tu_1) AS mb_tu_1, SUM(mb_u_6) AS mb_u_6, SUM(mb_tu_6) AS mb_tu_6, SUM(mb_u_10) AS mb_u_10, SUM(mb_tu_10) AS mb_tu_10,"
                        . "SUM(bus_u_1) AS bus_u_1, SUM(bus_tu_1) AS bus_tu_1, SUM(bus_u_6) AS bus_u_6, SUM(bus_tu_6) AS bus_tu_6, SUM(bus_u_10) AS bus_u_10, SUM(bus_tu_10) AS bus_tu_10,"
                        . "SUM(mk_u_1) AS mk_u_1, SUM(mk_tu_1) AS mk_tu_1, SUM(mk_u_6) AS mk_u_6, SUM(mk_tu_6) AS mk_tu_6, SUM(mk_u_10) AS mk_u_10, SUM(mk_tu_10) AS mk_tu_10,"
                        . "SUM(kg_u_1) AS kg_u_1,SUM(kg_tu_1) AS kg_tu_1,SUM(kg_u_6) AS kg_u_6,SUM(kg_tu_6) AS kg_tu_6,SUM(kg_u_10) AS kg_u_10,SUM(kg_tu_10) AS kg_tu_10,"
                        . "SUM(kt_u_1) AS kt_u_1,SUM(kt_tu_1) AS kt_tu_1,SUM(kt_u_6) AS kt_u_6,SUM(kt_tu_6) AS kt_tu_6,SUM(kt_u_10) AS kt_u_10,SUM(kt_tu_10) AS kt_tu_10";
                $criteria->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan = VKementrianBerkala900112000::model()->find($criteria);
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A8:A11");
                $sheet->setCellValue("A8", "1");
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B8:B11");
                $sheet->setCellValue("B8", "MOBIL PENUMPANG");
                $sheet->getStyle("B8")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C8", "1-5");
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J8", $dataKendaraan->mp_u_1);
                $sheet->setCellValue("K8", $dataKendaraan->mp_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C9", "6-10");
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J9", $dataKendaraan->mp_u_6);
                $sheet->setCellValue("K9", $dataKendaraan->mp_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C10", ">10");
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J10", $dataKendaraan->mp_u_10);
                $sheet->setCellValue("K10", $dataKendaraan->mp_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C11", "JUMLAH");
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C11:R11")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D11", '=SUM(D8:D10)');
                $sheet->setCellValue("E11", '=SUM(E8:E10)');
                $sheet->setCellValue("F11", '=SUM(F8:F10)');
                $sheet->setCellValue("G11", '=SUM(G8:G10)');
                $sheet->setCellValue("H11", '=SUM(H8:H10)');
                $sheet->setCellValue("I11", '=SUM(I8:I10)');
                $sheet->setCellValue("J11", '=SUM(J8:J10)');
                $sheet->setCellValue("K11", '=SUM(K8:K10)');
                $sheet->setCellValue("L11", '=SUM(L8:L10)');
                $sheet->setCellValue("M11", '=SUM(M8:M10)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A12:A15");
                $sheet->setCellValue("A12", "2");
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B12:B15");
                $sheet->setCellValue("B12", "MOBIL BUS");
                $sheet->getStyle("B12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C12", "1-5");
                $sheet->getStyle("C12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J12", $dataKendaraan->bus_u_1);
                $sheet->setCellValue("K12", $dataKendaraan->bus_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C13", "6-10");
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J13", $dataKendaraan->bus_u_6);
                $sheet->setCellValue("K13", $dataKendaraan->bus_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C14", ">10");
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J14", $dataKendaraan->bus_u_10);
                $sheet->setCellValue("K14", $dataKendaraan->bus_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C15", "JUMLAH");
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C15:R15")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D15", '=SUM(D12:D14)');
                $sheet->setCellValue("E15", '=SUM(E12:E14)');
                $sheet->setCellValue("F15", '=SUM(F12:F14)');
                $sheet->setCellValue("G15", '=SUM(G12:G14)');
                $sheet->setCellValue("H15", '=SUM(H12:H14)');
                $sheet->setCellValue("I15", '=SUM(I12:I14)');
                $sheet->setCellValue("J15", '=SUM(J12:J14)');
                $sheet->setCellValue("K15", '=SUM(K12:K14)');
                $sheet->setCellValue("L15", '=SUM(L12:L14)');
                $sheet->setCellValue("M15", '=SUM(M12:M14)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A16:A19");
                $sheet->setCellValue("A16", "3");
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B16:B19");
                $sheet->setCellValue("B16", "MOBIL KHUSUS");
                $sheet->getStyle("B16")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C16", "1-5");
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J16", $dataKendaraan->mk_u_1);
                $sheet->setCellValue("K16", $dataKendaraan->mk_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C17", "6-10");
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J17", $dataKendaraan->mk_u_6);
                $sheet->setCellValue("K17", $dataKendaraan->mk_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C18", ">10");
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J18", $dataKendaraan->mk_u_10);
                $sheet->setCellValue("K18", $dataKendaraan->mk_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C19", "JUMLAH");
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C19:R19")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D19", '=SUM(D16:D18)');
                $sheet->setCellValue("E19", '=SUM(E16:E18)');
                $sheet->setCellValue("F19", '=SUM(F16:F18)');
                $sheet->setCellValue("G19", '=SUM(G16:G18)');
                $sheet->setCellValue("H19", '=SUM(H16:H18)');
                $sheet->setCellValue("I19", '=SUM(I16:I18)');
                $sheet->setCellValue("J19", '=SUM(J16:J18)');
                $sheet->setCellValue("K19", '=SUM(K16:K18)');
                $sheet->setCellValue("L19", '=SUM(L16:L18)');
                $sheet->setCellValue("M19", '=SUM(M16:M18)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A20:A23");
                $sheet->setCellValue("A20", "4");
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B20:B23");
                $sheet->setCellValue("B20", "MOBIL BARANG");
                $sheet->getStyle("B20")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C20", "1-5");
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J20", $dataKendaraan->mb_u_1);
                $sheet->setCellValue("K20", $dataKendaraan->mb_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C21", "6-10");
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J21", $dataKendaraan->mb_u_6);
                $sheet->setCellValue("K21", $dataKendaraan->mb_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C22", ">10");
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J22", $dataKendaraan->mb_u_10);
                $sheet->setCellValue("K22", $dataKendaraan->mb_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C23", "JUMLAH");
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C23:R23")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D23", '=SUM(D20:D22)');
                $sheet->setCellValue("E23", '=SUM(E20:E22)');
                $sheet->setCellValue("F23", '=SUM(F20:F22)');
                $sheet->setCellValue("G23", '=SUM(G20:G22)');
                $sheet->setCellValue("H23", '=SUM(H20:H22)');
                $sheet->setCellValue("I23", '=SUM(I20:I22)');
                $sheet->setCellValue("J23", '=SUM(J20:J22)');
                $sheet->setCellValue("K23", '=SUM(K20:K22)');
                $sheet->setCellValue("L23", '=SUM(L20:L22)');
                $sheet->setCellValue("M23", '=SUM(M20:M22)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A24:A27");
                $sheet->setCellValue("A24", "5");
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B24:B27");
                $sheet->setCellValue("B24", "KERETA GANDENG");
                $sheet->getStyle("B24")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C24", "1-5");
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J24", $dataKendaraan->kg_u_1);
                $sheet->setCellValue("K24", $dataKendaraan->kg_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C25", "6-10");
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J25", $dataKendaraan->kg_u_6);
                $sheet->setCellValue("K25", $dataKendaraan->kg_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C26", ">10");
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J26", $dataKendaraan->kg_u_10);
                $sheet->setCellValue("K26", $dataKendaraan->kg_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C27", "JUMLAH");
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C27:R27")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D27", '=SUM(D24:D26)');
                $sheet->setCellValue("E27", '=SUM(E24:E26)');
                $sheet->setCellValue("F27", '=SUM(F24:F26)');
                $sheet->setCellValue("G27", '=SUM(G24:G26)');
                $sheet->setCellValue("H27", '=SUM(H24:H26)');
                $sheet->setCellValue("I27", '=SUM(I24:I26)');
                $sheet->setCellValue("J27", '=SUM(J24:J26)');
                $sheet->setCellValue("K27", '=SUM(K24:K26)');
                $sheet->setCellValue("L27", '=SUM(L24:L26)');
                $sheet->setCellValue("M27", '=SUM(M24:M26)');
                //======================================================================
                ////======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A28:A31");
                $sheet->setCellValue("A28", "6");
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B28:B31");
                $sheet->setCellValue("B28", "KERETA TEMPEL");
                $sheet->getStyle("B28")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C28", "1-5");
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J28", $dataKendaraan->kt_u_1);
                $sheet->setCellValue("K28", $dataKendaraan->kt_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C29", "6-10");
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J29", $dataKendaraan->kt_u_6);
                $sheet->setCellValue("K29", $dataKendaraan->kt_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C30", ">10");
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J30", $dataKendaraan->kt_u_10);
                $sheet->setCellValue("K30", $dataKendaraan->kt_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C31", "JUMLAH");
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C31:R31")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D31", '=SUM(D28:D30)');
                $sheet->setCellValue("E31", '=SUM(E28:E30)');
                $sheet->setCellValue("F31", '=SUM(F28:F30)');
                $sheet->setCellValue("G31", '=SUM(G28:G30)');
                $sheet->setCellValue("H31", '=SUM(H28:H30)');
                $sheet->setCellValue("I31", '=SUM(I28:I30)');
                $sheet->setCellValue("J31", '=SUM(J28:J30)');
                $sheet->setCellValue("K31", '=SUM(K28:K30)');
                $sheet->setCellValue("L31", '=SUM(L28:L30)');
                $sheet->setCellValue("M31", '=SUM(M28:M30)');
                //======================================================================
                //JUMLAH UMUM & BUKAN UMUM
                //======================================================================
                //UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("N8", '=SUM(D8,F8,H8,J8,L8)');
                $sheet->setCellValue("N9", '=SUM(D9,F9,H9,J9,L9)');
                $sheet->setCellValue("N10", '=SUM(D10,F10,H10,J10,L10)');
                $sheet->setCellValue("N11", '=SUM(N8:N10)');
                //MOBIL BUS
                $sheet->setCellValue("N12", '=SUM(D12,F12,H12,J12,L12)');
                $sheet->setCellValue("N13", '=SUM(D13,F13,H13,J13,L13)');
                $sheet->setCellValue("N14", '=SUM(D14,F14,H14,J14,L14)');
                $sheet->setCellValue("N15", '=SUM(N12:N14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("N16", '=SUM(D16,F16,H16,J16,L16)');
                $sheet->setCellValue("N17", '=SUM(D17,F17,H17,J17,L17)');
                $sheet->setCellValue("N18", '=SUM(D18,F18,H18,J18,L18)');
                $sheet->setCellValue("N19", '=SUM(N16:N18)');
                //MOBIL BARANG
                $sheet->setCellValue("N20", '=SUM(D20,F20,H20,J20,L20)');
                $sheet->setCellValue("N21", '=SUM(D21,F21,H21,J21,L21)');
                $sheet->setCellValue("N22", '=SUM(D22,F22,H22,J22,L22)');
                $sheet->setCellValue("N23", '=SUM(N20:N22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("N24", '=SUM(D24,F24,H24,J24,L24)');
                $sheet->setCellValue("N25", '=SUM(D25,F25,H25,J25,L25)');
                $sheet->setCellValue("N26", '=SUM(D26,F26,H26,J26,L26)');
                $sheet->setCellValue("N27", '=SUM(N24:N26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("N28", '=SUM(D28,F28,H28,J28,L28)');
                $sheet->setCellValue("N29", '=SUM(D29,F29,H29,J29,L29)');
                $sheet->setCellValue("N30", '=SUM(D30,F30,H30,J30,L30)');
                $sheet->setCellValue("N31", '=SUM(N28:N30)');
                //======================================================================
                //BUKAN UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("O8", '=SUM(E8,G8,I8,K8,M8)');
                $sheet->setCellValue("O9", '=SUM(E9,G9,I9,K9,M9)');
                $sheet->setCellValue("O10", '=SUM(E10,G10,I10,K10,M10)');
                $sheet->setCellValue("O11", '=SUM(O8:O10)');
                //MOBIL BUS
                $sheet->setCellValue("O12", '=SUM(E12,G12,I12,K12,M12)');
                $sheet->setCellValue("O13", '=SUM(E13,G13,I13,K13,M13)');
                $sheet->setCellValue("O14", '=SUM(E14,G14,I14,K14,M14)');
                $sheet->setCellValue("O15", '=SUM(O12:O14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("O16", '=SUM(E16,G16,I16,K16,M16)');
                $sheet->setCellValue("O17", '=SUM(E17,G17,I17,K17,M17)');
                $sheet->setCellValue("O18", '=SUM(E18,G18,I18,K18,M18)');
                $sheet->setCellValue("O19", '=SUM(O16:O18)');
                //MOBIL BARANG
                $sheet->setCellValue("O20", '=SUM(E20,G20,I20,K20,M20)');
                $sheet->setCellValue("O21", '=SUM(E21,G21,I21,K21,M21)');
                $sheet->setCellValue("O22", '=SUM(E22,G22,I22,K22,M22)');
                $sheet->setCellValue("O23", '=SUM(O20:O22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("O24", '=SUM(E24,G24,I24,K24,M24)');
                $sheet->setCellValue("O25", '=SUM(E25,G25,I25,K25,M25)');
                $sheet->setCellValue("O26", '=SUM(E26,G26,I26,K26,M26)');
                $sheet->setCellValue("O27", '=SUM(O24:O26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("O28", '=SUM(E28,G28,I28,K28,M28)');
                $sheet->setCellValue("O29", '=SUM(E29,G29,I29,K29,M29)');
                $sheet->setCellValue("O30", '=SUM(E30,G30,I30,K30,M30)');
                $sheet->setCellValue("O31", '=SUM(O28:O30)');
                //UMUM + BUKAN UMUM
                $sheet->setCellValue("P8", '=SUM(N8,O8)');
                $sheet->setCellValue("P9", '=SUM(N9,O9)');
                $sheet->setCellValue("P10", '=SUM(N10,O10)');
                $sheet->setCellValue("P11", '=SUM(P8:P10)');
                $sheet->setCellValue("P12", '=SUM(N12,O12)');
                $sheet->setCellValue("P13", '=SUM(N13,O13)');
                $sheet->setCellValue("P14", '=SUM(N14,O14)');
                $sheet->setCellValue("P15", '=SUM(P12:P14)');
                $sheet->setCellValue("P16", '=SUM(N16,O16)');
                $sheet->setCellValue("P17", '=SUM(N17,O17)');
                $sheet->setCellValue("P18", '=SUM(N18,O18)');
                $sheet->setCellValue("P19", '=SUM(P16:P18)');
                $sheet->setCellValue("P20", '=SUM(N20:O20)');
                $sheet->setCellValue("P21", '=SUM(N21:O21)');
                $sheet->setCellValue("P22", '=SUM(N22:O22)');
                $sheet->setCellValue("P23", '=SUM(P20:P22)');
                $sheet->setCellValue("P24", '=SUM(N24:O24)');
                $sheet->setCellValue("P25", '=SUM(N25:O25)');
                $sheet->setCellValue("P26", '=SUM(N26:O26)');
                $sheet->setCellValue("P27", '=SUM(P24:P26)');
                $sheet->setCellValue("P28", '=SUM(N28:O28)');
                $sheet->setCellValue("P29", '=SUM(N29:O29)');
                $sheet->setCellValue("P30", '=SUM(N30:O30)');
                $sheet->setCellValue("P31", '=SUM(P28:P30)');
                //======================================================================
                //JUMLAH STATUS HASIL UJI
                //MOBIL PENUMPANG
                //        $sheet->setCellValue("Q8", $dataKendaraan->mp_l_1);
                //        $sheet->setCellValue("R8", $dataKendaraan->mp_tl_1);
                //        $sheet->setCellValue("Q9", $dataKendaraan->mp_l_6);
                //        $sheet->setCellValue("R9", $dataKendaraan->mp_tl_6);
                //        $sheet->setCellValue("Q10", $dataKendaraan->mp_l_10);
                //        $sheet->setCellValue("R10", $dataKendaraan->mp_tl_10);
                $sheet->setCellValue("Q11", '=SUM(Q8:Q10)');
                $sheet->setCellValue("R11", '=SUM(R8:R10)');
                //MOBIL BUS
                //        $sheet->setCellValue("Q12", $dataKendaraan->bus_l_1);
                //        $sheet->setCellValue("R12", $dataKendaraan->bus_tl_1);
                //        $sheet->setCellValue("Q13", $dataKendaraan->bus_l_6);
                //        $sheet->setCellValue("R13", $dataKendaraan->bus_tl_6);
                //        $sheet->setCellValue("Q14", $dataKendaraan->bus_l_10);
                //        $sheet->setCellValue("R14", $dataKendaraan->bus_tl_10);
                $sheet->setCellValue("Q15", '=SUM(Q12:Q14)');
                $sheet->setCellValue("R15", '=SUM(R12:R14)');
                //MOBIL KHUSUS
                //        $sheet->setCellValue("Q16", $dataKendaraan->mk_l_1);
                //        $sheet->setCellValue("R16", $dataKendaraan->mk_tl_1);
                //        $sheet->setCellValue("Q17", $dataKendaraan->mk_l_6);
                //        $sheet->setCellValue("R17", $dataKendaraan->mk_tl_6);
                //        $sheet->setCellValue("Q18", $dataKendaraan->mk_l_10);
                //        $sheet->setCellValue("R18", $dataKendaraan->mk_tl_10);
                $sheet->setCellValue("Q19", '=SUM(Q16:Q18)');
                $sheet->setCellValue("R19", '=SUM(R16:R18)');
                //MOBIL BARANG
                //        $sheet->setCellValue("Q20", $dataKendaraan->mb_l_1);
                //        $sheet->setCellValue("R20", $dataKendaraan->mb_tl_1);
                //        $sheet->setCellValue("Q21", $dataKendaraan->mb_l_6);
                //        $sheet->setCellValue("R21", $dataKendaraan->mb_tl_6);
                //        $sheet->setCellValue("Q22", $dataKendaraan->mb_l_10);
                //        $sheet->setCellValue("R22", $dataKendaraan->mb_tl_10);
                $sheet->setCellValue("Q23", '=SUM(Q20:Q22)');
                $sheet->setCellValue("R23", '=SUM(R20:R22)');
                //KERETA GANDENGAN
                //        $sheet->setCellValue("Q24", $dataKendaraan->kg_l_1);
                //        $sheet->setCellValue("R24", $dataKendaraan->kg_tl_1);
                //        $sheet->setCellValue("Q25", $dataKendaraan->kg_l_6);
                //        $sheet->setCellValue("R25", $dataKendaraan->kg_tl_6);
                //        $sheet->setCellValue("Q26", $dataKendaraan->kg_l_10);
                //        $sheet->setCellValue("R26", $dataKendaraan->kg_tl_10);
                $sheet->setCellValue("Q27", '=SUM(Q24:Q26)');
                $sheet->setCellValue("R27", '=SUM(R24:R26)');
                //KERETA TEMPELAN
                //        $sheet->setCellValue("Q28", $dataKendaraan->kt_l_1);
                //        $sheet->setCellValue("R28", $dataKendaraan->kt_tl_1);
                //        $sheet->setCellValue("Q29", $dataKendaraan->kt_l_6);
                //        $sheet->setCellValue("R29", $dataKendaraan->kt_tl_6);
                //        $sheet->setCellValue("Q30", $dataKendaraan->kt_l_10);
                //        $sheet->setCellValue("R30", $dataKendaraan->kt_tl_10);
                $sheet->setCellValue("Q31", '=SUM(Q28:Q30)');
                $sheet->setCellValue("R31", '=SUM(R28:R30)');
                //======================================================================
                $sheet->mergeCells("A32:C32");
                $sheet->setCellValue("A32", "JUMLAH");
                $sheet->setCellValue("D32", "=SUM(D11,D15,D19,D23,D27,D31)");
                $sheet->setCellValue("E32", "=SUM(E11,E15,E19,E23,E27,E31)");
                $sheet->setCellValue("F32", "=SUM(F11,F15,F19,F23,F27,F31)");
                $sheet->setCellValue("G32", "=SUM(G11,G15,G19,G23,G27,G31)");
                $sheet->setCellValue("H32", "=SUM(H11,H15,H19,H23,H27,H31)");
                $sheet->setCellValue("I32", "=SUM(I11,I15,I19,I23,I27,I31)");
                $sheet->setCellValue("J32", "=SUM(J11,J15,J19,J23,J27,J31)");
                $sheet->setCellValue("K32", "=SUM(K11,K15,K19,K23,K27,K31)");
                $sheet->setCellValue("L32", "=SUM(L11,L15,L19,L23,L27,L31)");
                $sheet->setCellValue("M32", "=SUM(M11,M15,M19,M23,M27,M31)");
                $sheet->setCellValue("N32", "=SUM(N11,N15,N19,N23,N27,N31)");
                $sheet->setCellValue("O32", "=SUM(O11,O15,O19,O23,O27,O31)");
                $sheet->setCellValue("P32", "=SUM(P11,P15,P19,P23,P27,P31)");
                $sheet->setCellValue("Q32", "=SUM(Q11,Q15,Q19,Q23,Q27,Q31)");
                $sheet->setCellValue("R32", "=SUM(R11,R15,R19,R23,R27,R31)");
                $sheet->getStyle("A32:R32")->applyFromArray($styleCenter);
                $sheet->getStyle("A32:R32")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                //=====================================================================================================
                $sheet->getStyle("A6:R32")->applyFromArray($styleBorder);
                ob_clean();

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="kementrian_berkala_9001_12000_' . $thn . '_' . $bln . '.xls"');
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

        public function actionReportBerkala12000($tgl)
        {
                $blnThn = date("n-Y", strtotime($tgl));
                $explodeBlnThn = explode('-', $blnThn);
                $bln = $explodeBlnThn[0];
                $thn = $explodeBlnThn[1];

                $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

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
                //        $sheet->setTitle('TERDAFTAR');
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToPage(false);
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setScale(82);
                //=============================================
                $sheet->mergeCells("A1:R1");
                $sheet->mergeCells("A2:R2");
                $sheet->mergeCells("A3:R3");
                $sheet->mergeCells("A4:R4");

                $sheet->setCellValue("A1", "JUMLAH PELAYANAN PENGUJIAN BERKALA KENDARAAN BERMOTOR BERDASARKAN UMUR DAN JBB (KG)");
                $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR");
                $sheet->setCellValue("A3", "DINAS PERHUBUNGAN KABUPATEN SAMPANG, JAWA TIMUR");
                $sheet->setCellValue("A4", "TAHUN : " . $thn . " / BULAN : " . strtoupper(Yii::app()->params['bulanArrayInd'][$bln - 1]));

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
                $sheet->setCellValue("B6", "JENIS KEND");
                $sheet->getStyle("B6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("B")->setWidth(15);
                //=====================================================================================================
                $sheet->mergeCells("C6:C7");
                $sheet->setCellValue("C6", "UMUR (TAHUN)");
                $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("C")->setWidth(10);
                //=====================================================================================================
                $sheet->mergeCells("D6:E6");
                $sheet->setCellValue("D6", "S/D 3500");
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D7", "UMUM");
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("E7", "B.UMUM");
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("D")->setWidth(12);
                $sheet->getColumnDimension("E")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("F6:G6");
                $sheet->setCellValue("F6", "3501 S/D 6000");
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F7", "UMUM");
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("G7", "B.UMUM");
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("F")->setWidth(12);
                $sheet->getColumnDimension("G")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("H6:I6");
                $sheet->setCellValue("H6", "6001 S/D 9000");
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H7", "UMUM");
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("I7", "B.UMUM");
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("H")->setWidth(12);
                $sheet->getColumnDimension("I")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("J6:K6");
                $sheet->setCellValue("J6", "9001 S/D 12000");
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J7", "UMUM");
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("K7", "B.UMUM");
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("J")->setWidth(12);
                $sheet->getColumnDimension("K")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("L6:M6");
                $sheet->setCellValue("L6", "DIATAS 12001");
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L7", "UMUM");
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("M7", "B.UMUM");
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("L")->setWidth(12);
                $sheet->getColumnDimension("M")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("N6:P6");
                $sheet->setCellValue("N6", "JUMLAH");
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("N7", "UMUM");
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("O7", "B.UMUM");
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("P7", "U+BU");
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("N")->setWidth(12);
                $sheet->getColumnDimension("O")->setWidth(12);
                $sheet->getColumnDimension("P")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("Q6:R6");
                $sheet->setCellValue("Q6", "JUMLAH STATUS HASIL UJI");
                $sheet->getStyle("Q6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("Q7", "LULUS");
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("R7", "T.LULUS");
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("Q")->setWidth(12);
                $sheet->getColumnDimension("R")->setWidth(12);
                //=====================================================================================================

                $sheet->getStyle("A6:R7")->applyFromArray($styleCenter);
                $sheet->getStyle("A6:R7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                /*
         * BODY 
         */
                $criteria = new CDbCriteria();
                $criteria->select = "SUM(mp_u_1) AS mp_u_1, SUM(mp_tu_1) AS mp_tu_1, SUM(mp_u_6) AS mp_u_6, SUM(mp_tu_6) AS mp_tu_6, SUM(mp_u_10) AS mp_u_10, SUM(mp_tu_10) AS mp_tu_10,"
                        . "SUM(mb_u_1) AS mb_u_1, SUM(mb_tu_1) AS mb_tu_1, SUM(mb_u_6) AS mb_u_6, SUM(mb_tu_6) AS mb_tu_6, SUM(mb_u_10) AS mb_u_10, SUM(mb_tu_10) AS mb_tu_10,"
                        . "SUM(bus_u_1) AS bus_u_1, SUM(bus_tu_1) AS bus_tu_1, SUM(bus_u_6) AS bus_u_6, SUM(bus_tu_6) AS bus_tu_6, SUM(bus_u_10) AS bus_u_10, SUM(bus_tu_10) AS bus_tu_10,"
                        . "SUM(mk_u_1) AS mk_u_1, SUM(mk_tu_1) AS mk_tu_1, SUM(mk_u_6) AS mk_u_6, SUM(mk_tu_6) AS mk_tu_6, SUM(mk_u_10) AS mk_u_10, SUM(mk_tu_10) AS mk_tu_10,"
                        . "SUM(kg_u_1) AS kg_u_1,SUM(kg_tu_1) AS kg_tu_1,SUM(kg_u_6) AS kg_u_6,SUM(kg_tu_6) AS kg_tu_6,SUM(kg_u_10) AS kg_u_10,SUM(kg_tu_10) AS kg_tu_10,"
                        . "SUM(kt_u_1) AS kt_u_1,SUM(kt_tu_1) AS kt_tu_1,SUM(kt_u_6) AS kt_u_6,SUM(kt_tu_6) AS kt_tu_6,SUM(kt_u_10) AS kt_u_10,SUM(kt_tu_10) AS kt_tu_10";
                $criteria->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan = VKementrianBerkala12000::model()->find($criteria);
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A8:A11");
                $sheet->setCellValue("A8", "1");
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B8:B11");
                $sheet->setCellValue("B8", "MOBIL PENUMPANG");
                $sheet->getStyle("B8")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C8", "1-5");
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L8", $dataKendaraan->mp_u_1);
                $sheet->setCellValue("M8", $dataKendaraan->mp_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C9", "6-10");
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L9", $dataKendaraan->mp_u_6);
                $sheet->setCellValue("M9", $dataKendaraan->mp_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C10", ">10");
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L10", $dataKendaraan->mp_u_10);
                $sheet->setCellValue("M10", $dataKendaraan->mp_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C11", "JUMLAH");
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C11:R11")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D11", '=SUM(D8:D10)');
                $sheet->setCellValue("E11", '=SUM(E8:E10)');
                $sheet->setCellValue("F11", '=SUM(F8:F10)');
                $sheet->setCellValue("G11", '=SUM(G8:G10)');
                $sheet->setCellValue("H11", '=SUM(H8:H10)');
                $sheet->setCellValue("I11", '=SUM(I8:I10)');
                $sheet->setCellValue("J11", '=SUM(J8:J10)');
                $sheet->setCellValue("K11", '=SUM(K8:K10)');
                $sheet->setCellValue("L11", '=SUM(L8:L10)');
                $sheet->setCellValue("M11", '=SUM(M8:M10)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A12:A15");
                $sheet->setCellValue("A12", "2");
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B12:B15");
                $sheet->setCellValue("B12", "MOBIL BUS");
                $sheet->getStyle("B12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C12", "1-5");
                $sheet->getStyle("C12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L12", $dataKendaraan->bus_u_1);
                $sheet->setCellValue("M12", $dataKendaraan->bus_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C13", "6-10");
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L13", $dataKendaraan->bus_u_6);
                $sheet->setCellValue("M13", $dataKendaraan->bus_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C14", ">10");
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L14", $dataKendaraan->bus_u_10);
                $sheet->setCellValue("M14", $dataKendaraan->bus_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C15", "JUMLAH");
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C15:R15")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D15", '=SUM(D12:D14)');
                $sheet->setCellValue("E15", '=SUM(E12:E14)');
                $sheet->setCellValue("F15", '=SUM(F12:F14)');
                $sheet->setCellValue("G15", '=SUM(G12:G14)');
                $sheet->setCellValue("H15", '=SUM(H12:H14)');
                $sheet->setCellValue("I15", '=SUM(I12:I14)');
                $sheet->setCellValue("J15", '=SUM(J12:J14)');
                $sheet->setCellValue("K15", '=SUM(K12:K14)');
                $sheet->setCellValue("L15", '=SUM(L12:L14)');
                $sheet->setCellValue("M15", '=SUM(M12:M14)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A16:A19");
                $sheet->setCellValue("A16", "3");
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B16:B19");
                $sheet->setCellValue("B16", "MOBIL KHUSUS");
                $sheet->getStyle("B16")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C16", "1-5");
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L16", $dataKendaraan->mk_u_1);
                $sheet->setCellValue("M16", $dataKendaraan->mk_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C17", "6-10");
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L17", $dataKendaraan->mk_u_6);
                $sheet->setCellValue("M17", $dataKendaraan->mk_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C18", ">10");
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L18", $dataKendaraan->mk_u_10);
                $sheet->setCellValue("M18", $dataKendaraan->mk_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C19", "JUMLAH");
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C19:R19")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D19", '=SUM(D16:D18)');
                $sheet->setCellValue("E19", '=SUM(E16:E18)');
                $sheet->setCellValue("F19", '=SUM(F16:F18)');
                $sheet->setCellValue("G19", '=SUM(G16:G18)');
                $sheet->setCellValue("H19", '=SUM(H16:H18)');
                $sheet->setCellValue("I19", '=SUM(I16:I18)');
                $sheet->setCellValue("J19", '=SUM(J16:J18)');
                $sheet->setCellValue("K19", '=SUM(K16:K18)');
                $sheet->setCellValue("L19", '=SUM(L16:L18)');
                $sheet->setCellValue("M19", '=SUM(M16:M18)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A20:A23");
                $sheet->setCellValue("A20", "4");
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B20:B23");
                $sheet->setCellValue("B20", "MOBIL BARANG");
                $sheet->getStyle("B20")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C20", "1-5");
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L20", $dataKendaraan->mb_u_1);
                $sheet->setCellValue("M20", $dataKendaraan->mb_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C21", "6-10");
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L21", $dataKendaraan->mb_u_6);
                $sheet->setCellValue("M21", $dataKendaraan->mb_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C22", ">10");
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L22", $dataKendaraan->mb_u_10);
                $sheet->setCellValue("M22", $dataKendaraan->mb_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C23", "JUMLAH");
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C23:R23")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D23", '=SUM(D20:D22)');
                $sheet->setCellValue("E23", '=SUM(E20:E22)');
                $sheet->setCellValue("F23", '=SUM(F20:F22)');
                $sheet->setCellValue("G23", '=SUM(G20:G22)');
                $sheet->setCellValue("H23", '=SUM(H20:H22)');
                $sheet->setCellValue("I23", '=SUM(I20:I22)');
                $sheet->setCellValue("J23", '=SUM(J20:J22)');
                $sheet->setCellValue("K23", '=SUM(K20:K22)');
                $sheet->setCellValue("L23", '=SUM(L20:L22)');
                $sheet->setCellValue("M23", '=SUM(M20:M22)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A24:A27");
                $sheet->setCellValue("A24", "5");
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B24:B27");
                $sheet->setCellValue("B24", "KERETA GANDENG");
                $sheet->getStyle("B24")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C24", "1-5");
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L24", $dataKendaraan->kg_u_1);
                $sheet->setCellValue("M24", $dataKendaraan->kg_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C25", "6-10");
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L25", $dataKendaraan->kg_u_6);
                $sheet->setCellValue("M25", $dataKendaraan->kg_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C26", ">10");
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L26", $dataKendaraan->kg_u_10);
                $sheet->setCellValue("M26", $dataKendaraan->kg_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C27", "JUMLAH");
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C27:R27")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D27", '=SUM(D24:D26)');
                $sheet->setCellValue("E27", '=SUM(E24:E26)');
                $sheet->setCellValue("F27", '=SUM(F24:F26)');
                $sheet->setCellValue("G27", '=SUM(G24:G26)');
                $sheet->setCellValue("H27", '=SUM(H24:H26)');
                $sheet->setCellValue("I27", '=SUM(I24:I26)');
                $sheet->setCellValue("J27", '=SUM(J24:J26)');
                $sheet->setCellValue("K27", '=SUM(K24:K26)');
                $sheet->setCellValue("L27", '=SUM(L24:L26)');
                $sheet->setCellValue("M27", '=SUM(M24:M26)');
                //======================================================================
                ////======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A28:A31");
                $sheet->setCellValue("A28", "6");
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B28:B31");
                $sheet->setCellValue("B28", "KERETA TEMPEL");
                $sheet->getStyle("B28")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C28", "1-5");
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L28", $dataKendaraan->kt_u_1);
                $sheet->setCellValue("M28", $dataKendaraan->kt_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C29", "6-10");
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L29", $dataKendaraan->kt_u_6);
                $sheet->setCellValue("M29", $dataKendaraan->kt_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C30", ">10");
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L30", $dataKendaraan->kt_u_10);
                $sheet->setCellValue("M30", $dataKendaraan->kt_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C31", "JUMLAH");
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C31:R31")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D31", '=SUM(D28:D30)');
                $sheet->setCellValue("E31", '=SUM(E28:E30)');
                $sheet->setCellValue("F31", '=SUM(F28:F30)');
                $sheet->setCellValue("G31", '=SUM(G28:G30)');
                $sheet->setCellValue("H31", '=SUM(H28:H30)');
                $sheet->setCellValue("I31", '=SUM(I28:I30)');
                $sheet->setCellValue("J31", '=SUM(J28:J30)');
                $sheet->setCellValue("K31", '=SUM(K28:K30)');
                $sheet->setCellValue("L31", '=SUM(L28:L30)');
                $sheet->setCellValue("M31", '=SUM(M28:M30)');
                //======================================================================
                //JUMLAH UMUM & BUKAN UMUM
                //======================================================================
                //UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("N8", '=SUM(D8,F8,H8,J8,L8)');
                $sheet->setCellValue("N9", '=SUM(D9,F9,H9,J9,L9)');
                $sheet->setCellValue("N10", '=SUM(D10,F10,H10,J10,L10)');
                $sheet->setCellValue("N11", '=SUM(N8:N10)');
                //MOBIL BUS
                $sheet->setCellValue("N12", '=SUM(D12,F12,H12,J12,L12)');
                $sheet->setCellValue("N13", '=SUM(D13,F13,H13,J13,L13)');
                $sheet->setCellValue("N14", '=SUM(D14,F14,H14,J14,L14)');
                $sheet->setCellValue("N15", '=SUM(N12:N14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("N16", '=SUM(D16,F16,H16,J16,L16)');
                $sheet->setCellValue("N17", '=SUM(D17,F17,H17,J17,L17)');
                $sheet->setCellValue("N18", '=SUM(D18,F18,H18,J18,L18)');
                $sheet->setCellValue("N19", '=SUM(N16:N18)');
                //MOBIL BARANG
                $sheet->setCellValue("N20", '=SUM(D20,F20,H20,J20,L20)');
                $sheet->setCellValue("N21", '=SUM(D21,F21,H21,J21,L21)');
                $sheet->setCellValue("N22", '=SUM(D22,F22,H22,J22,L22)');
                $sheet->setCellValue("N23", '=SUM(N20:N22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("N24", '=SUM(D24,F24,H24,J24,L24)');
                $sheet->setCellValue("N25", '=SUM(D25,F25,H25,J25,L25)');
                $sheet->setCellValue("N26", '=SUM(D26,F26,H26,J26,L26)');
                $sheet->setCellValue("N27", '=SUM(N24:N26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("N28", '=SUM(D28,F28,H28,J28,L28)');
                $sheet->setCellValue("N29", '=SUM(D29,F29,H29,J29,L29)');
                $sheet->setCellValue("N30", '=SUM(D30,F30,H30,J30,L30)');
                $sheet->setCellValue("N31", '=SUM(N28:N30)');
                //======================================================================
                //BUKAN UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("O8", '=SUM(E8,G8,I8,K8,M8)');
                $sheet->setCellValue("O9", '=SUM(E9,G9,I9,K9,M9)');
                $sheet->setCellValue("O10", '=SUM(E10,G10,I10,K10,M10)');
                $sheet->setCellValue("O11", '=SUM(O8:O10)');
                //MOBIL BUS
                $sheet->setCellValue("O12", '=SUM(E12,G12,I12,K12,M12)');
                $sheet->setCellValue("O13", '=SUM(E13,G13,I13,K13,M13)');
                $sheet->setCellValue("O14", '=SUM(E14,G14,I14,K14,M14)');
                $sheet->setCellValue("O15", '=SUM(O12:O14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("O16", '=SUM(E16,G16,I16,K16,M16)');
                $sheet->setCellValue("O17", '=SUM(E17,G17,I17,K17,M17)');
                $sheet->setCellValue("O18", '=SUM(E18,G18,I18,K18,M18)');
                $sheet->setCellValue("O19", '=SUM(O16:O18)');
                //MOBIL BARANG
                $sheet->setCellValue("O20", '=SUM(E20,G20,I20,K20,M20)');
                $sheet->setCellValue("O21", '=SUM(E21,G21,I21,K21,M21)');
                $sheet->setCellValue("O22", '=SUM(E22,G22,I22,K22,M22)');
                $sheet->setCellValue("O23", '=SUM(O20:O22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("O24", '=SUM(E24,G24,I24,K24,M24)');
                $sheet->setCellValue("O25", '=SUM(E25,G25,I25,K25,M25)');
                $sheet->setCellValue("O26", '=SUM(E26,G26,I26,K26,M26)');
                $sheet->setCellValue("O27", '=SUM(O24:O26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("O28", '=SUM(E28,G28,I28,K28,M28)');
                $sheet->setCellValue("O29", '=SUM(E29,G29,I29,K29,M29)');
                $sheet->setCellValue("O30", '=SUM(E30,G30,I30,K30,M30)');
                $sheet->setCellValue("O31", '=SUM(O28:O30)');
                //UMUM + BUKAN UMUM
                $sheet->setCellValue("P8", '=SUM(N8,O8)');
                $sheet->setCellValue("P9", '=SUM(N9,O9)');
                $sheet->setCellValue("P10", '=SUM(N10,O10)');
                $sheet->setCellValue("P11", '=SUM(P8:P10)');
                $sheet->setCellValue("P12", '=SUM(N12,O12)');
                $sheet->setCellValue("P13", '=SUM(N13,O13)');
                $sheet->setCellValue("P14", '=SUM(N14,O14)');
                $sheet->setCellValue("P15", '=SUM(P12:P14)');
                $sheet->setCellValue("P16", '=SUM(N16,O16)');
                $sheet->setCellValue("P17", '=SUM(N17,O17)');
                $sheet->setCellValue("P18", '=SUM(N18,O18)');
                $sheet->setCellValue("P19", '=SUM(P16:P18)');
                $sheet->setCellValue("P20", '=SUM(N20:O20)');
                $sheet->setCellValue("P21", '=SUM(N21:O21)');
                $sheet->setCellValue("P22", '=SUM(N22:O22)');
                $sheet->setCellValue("P23", '=SUM(P20:P22)');
                $sheet->setCellValue("P24", '=SUM(N24:O24)');
                $sheet->setCellValue("P25", '=SUM(N25:O25)');
                $sheet->setCellValue("P26", '=SUM(N26:O26)');
                $sheet->setCellValue("P27", '=SUM(P24:P26)');
                $sheet->setCellValue("P28", '=SUM(N28:O28)');
                $sheet->setCellValue("P29", '=SUM(N29:O29)');
                $sheet->setCellValue("P30", '=SUM(N30:O30)');
                $sheet->setCellValue("P31", '=SUM(P28:P30)');
                //======================================================================
                //JUMLAH STATUS HASIL UJI
                //MOBIL PENUMPANG
                //        $sheet->setCellValue("Q8", $dataKendaraan->mp_l_1);
                //        $sheet->setCellValue("R8", $dataKendaraan->mp_tl_1);
                //        $sheet->setCellValue("Q9", $dataKendaraan->mp_l_6);
                //        $sheet->setCellValue("R9", $dataKendaraan->mp_tl_6);
                //        $sheet->setCellValue("Q10", $dataKendaraan->mp_l_10);
                //        $sheet->setCellValue("R10", $dataKendaraan->mp_tl_10);
                $sheet->setCellValue("Q11", '=SUM(Q8:Q10)');
                $sheet->setCellValue("R11", '=SUM(R8:R10)');
                //MOBIL BUS
                //        $sheet->setCellValue("Q12", $dataKendaraan->bus_l_1);
                //        $sheet->setCellValue("R12", $dataKendaraan->bus_tl_1);
                //        $sheet->setCellValue("Q13", $dataKendaraan->bus_l_6);
                //        $sheet->setCellValue("R13", $dataKendaraan->bus_tl_6);
                //        $sheet->setCellValue("Q14", $dataKendaraan->bus_l_10);
                //        $sheet->setCellValue("R14", $dataKendaraan->bus_tl_10);
                $sheet->setCellValue("Q15", '=SUM(Q12:Q14)');
                $sheet->setCellValue("R15", '=SUM(R12:R14)');
                //MOBIL KHUSUS
                //        $sheet->setCellValue("Q16", $dataKendaraan->mk_l_1);
                //        $sheet->setCellValue("R16", $dataKendaraan->mk_tl_1);
                //        $sheet->setCellValue("Q17", $dataKendaraan->mk_l_6);
                //        $sheet->setCellValue("R17", $dataKendaraan->mk_tl_6);
                //        $sheet->setCellValue("Q18", $dataKendaraan->mk_l_10);
                //        $sheet->setCellValue("R18", $dataKendaraan->mk_tl_10);
                $sheet->setCellValue("Q19", '=SUM(Q16:Q18)');
                $sheet->setCellValue("R19", '=SUM(R16:R18)');
                //MOBIL BARANG
                //        $sheet->setCellValue("Q20", $dataKendaraan->mb_l_1);
                //        $sheet->setCellValue("R20", $dataKendaraan->mb_tl_1);
                //        $sheet->setCellValue("Q21", $dataKendaraan->mb_l_6);
                //        $sheet->setCellValue("R21", $dataKendaraan->mb_tl_6);
                //        $sheet->setCellValue("Q22", $dataKendaraan->mb_l_10);
                //        $sheet->setCellValue("R22", $dataKendaraan->mb_tl_10);
                $sheet->setCellValue("Q23", '=SUM(Q20:Q22)');
                $sheet->setCellValue("R23", '=SUM(R20:R22)');
                //KERETA GANDENGAN
                //        $sheet->setCellValue("Q24", $dataKendaraan->kg_l_1);
                //        $sheet->setCellValue("R24", $dataKendaraan->kg_tl_1);
                //        $sheet->setCellValue("Q25", $dataKendaraan->kg_l_6);
                //        $sheet->setCellValue("R25", $dataKendaraan->kg_tl_6);
                //        $sheet->setCellValue("Q26", $dataKendaraan->kg_l_10);
                //        $sheet->setCellValue("R26", $dataKendaraan->kg_tl_10);
                $sheet->setCellValue("Q27", '=SUM(Q24:Q26)');
                $sheet->setCellValue("R27", '=SUM(R24:R26)');
                //KERETA TEMPELAN
                //        $sheet->setCellValue("Q28", $dataKendaraan->kt_l_1);
                //        $sheet->setCellValue("R28", $dataKendaraan->kt_tl_1);
                //        $sheet->setCellValue("Q29", $dataKendaraan->kt_l_6);
                //        $sheet->setCellValue("R29", $dataKendaraan->kt_tl_6);
                //        $sheet->setCellValue("Q30", $dataKendaraan->kt_l_10);
                //        $sheet->setCellValue("R30", $dataKendaraan->kt_tl_10);
                $sheet->setCellValue("Q31", '=SUM(Q28:Q30)');
                $sheet->setCellValue("R31", '=SUM(R28:R30)');
                //======================================================================
                $sheet->mergeCells("A32:C32");
                $sheet->setCellValue("A32", "JUMLAH");
                $sheet->setCellValue("D32", "=SUM(D11,D15,D19,D23,D27,D31)");
                $sheet->setCellValue("E32", "=SUM(E11,E15,E19,E23,E27,E31)");
                $sheet->setCellValue("F32", "=SUM(F11,F15,F19,F23,F27,F31)");
                $sheet->setCellValue("G32", "=SUM(G11,G15,G19,G23,G27,G31)");
                $sheet->setCellValue("H32", "=SUM(H11,H15,H19,H23,H27,H31)");
                $sheet->setCellValue("I32", "=SUM(I11,I15,I19,I23,I27,I31)");
                $sheet->setCellValue("J32", "=SUM(J11,J15,J19,J23,J27,J31)");
                $sheet->setCellValue("K32", "=SUM(K11,K15,K19,K23,K27,K31)");
                $sheet->setCellValue("L32", "=SUM(L11,L15,L19,L23,L27,L31)");
                $sheet->setCellValue("M32", "=SUM(M11,M15,M19,M23,M27,M31)");
                $sheet->setCellValue("N32", "=SUM(N11,N15,N19,N23,N27,N31)");
                $sheet->setCellValue("O32", "=SUM(O11,O15,O19,O23,O27,O31)");
                $sheet->setCellValue("P32", "=SUM(P11,P15,P19,P23,P27,P31)");
                $sheet->setCellValue("Q32", "=SUM(Q11,Q15,Q19,Q23,Q27,Q31)");
                $sheet->setCellValue("R32", "=SUM(R11,R15,R19,R23,R27,R31)");
                $sheet->getStyle("A32:R32")->applyFromArray($styleCenter);
                $sheet->getStyle("A32:R32")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                //=====================================================================================================
                $sheet->getStyle("A6:R32")->applyFromArray($styleBorder);
                ob_clean();

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="kementrian_berkala_12000_' . $thn . '_' . $bln . '.xls"');
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
        /*
     * UJI PERTAMA
     */

        public function actionUjiPertama()
        {
                $this->pageTitle = 'Kementrian Uji Pertama';
                $this->render('pertama');
        }

        public function actionReportUjiPertama($tgl)
        {
                $blnThn = date("n-Y", strtotime($tgl));
                $explodeBlnThn = explode('-', $blnThn);
                $bln = $explodeBlnThn[0];
                $thn = $explodeBlnThn[1];

                $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

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
                //        $sheet->setTitle('TERDAFTAR');
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToPage(false);
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setScale(82);
                //=============================================
                $sheet->mergeCells("A1:K1");
                $sheet->mergeCells("A2:K2");
                $sheet->mergeCells("A3:K3");
                $sheet->mergeCells("A4:K4");

                $sheet->setCellValue("A1", "JUMLAH KBWU YANG DISAHKAN PERTAMA KALI");
                $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR");
                $sheet->setCellValue("A3", "DINAS PERHUBUNGAN KABUPATEN SAMPANG, JAWA TIMUR");
                $sheet->setCellValue("A4", "TAHUN : " . $thn . " / BULAN : " . strtoupper(Yii::app()->params['bulanArrayInd'][$bln - 1]));

                $sheet->getStyle("A1")->applyFromArray($styleCenter);
                $sheet->getStyle("A2")->applyFromArray($styleCenter);
                $sheet->getStyle("A3")->applyFromArray($styleCenter);
                $sheet->getStyle("A4")->applyFromArray($styleCenter);
                //=====================================================================================================
                //=====================================================================================================
                //=====================================================================================================
                /*
         * BODY HEADER
         */
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
                $sheet->setCellValue("K6", "NO SRUT");
                $sheet->getStyle("K6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("K6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("K")->setAutoSize(true);
                //=====================================================================================================

                $sheet->getStyle("A6:K6")->applyFromArray($styleCenter);
                $sheet->getStyle("A6:K6")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                /*
         * BODY 
         */
                $criteria = new CDbCriteria();
                $criteria->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan = VKementrianBaru::model()->findAll($criteria);
                //======================================================================
                //======================================================================
                //======================================================================
                $baris = 7;
                $no = 1;
                foreach ($dataKendaraan as $data) :
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
                        $sheet->setCellValue("K" . $baris, $data->no_regis);
                        $baris++;
                        $no++;
                endforeach;
                $baris_border = $baris - 1;
                $sheet->getStyle("A6:K" . $baris_border)->applyFromArray($styleBorder);
                //=====================================================================================================
                ob_clean();

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="kementrian_pertama_' . $thn . '_' . $bln . '.xls"');
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

        /*
        * KBWU
        */

        public function actionKbwu()
        {
                $this->pageTitle = 'KBWU';
                $this->render('kbwu');
        }

        public function actionReportKbwu($thn)
        {

                $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

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
                * HEADER UJI PERTAMA DAN BERKALA
                */
                $xls->setActiveSheetIndex(0);
                $sheet = $xls->getActiveSheet();
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToPage(false);
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setScale(82);
                //=============================================
                $sheet->getColumnDimension('A')->setWidth(11.56);
                $sheet->getColumnDimension('O')->setWidth(11.56);
                $sheet->mergeCells("A1:J1");
                $sheet->mergeCells("A2:J2");
                $sheet->mergeCells("A3:J3");
                $sheet->mergeCells("A4:J4");

                $sheet->setCellValue("A1", "RENCANA PELAYANAN PENGUJIAN BERKALA");
                $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR");
                $sheet->setCellValue("A3", "DINAS PERHUBUNGAN KABUPATEN SAMPANG, JAWA TIMUR");
                $sheet->setCellValue("A4", "TAHUN : " . $thn);

                $sheet->getStyle("A1")->applyFromArray($styleCenter);
                $sheet->getStyle("A2")->applyFromArray($styleCenter);
                $sheet->getStyle("A3")->applyFromArray($styleCenter);
                $sheet->getStyle("A4")->applyFromArray($styleCenter);
                //======================================================================
                //======================================================================
                //======================================================================
                /*
                * BODY HEADER UJI PERTAMA
                */
                $sheet->mergeCells("A6:A7");
                $sheet->setCellValue("A6", "NO");
                $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("A")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("A")->setWidth(5);
                //======================================================================
                $sheet->mergeCells("B6:B7");
                $sheet->setCellValue("B6", "BULAN");
                $sheet->getStyle("B6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("B")->setAutoSize(true);
                //======================================================================
                $sheet->mergeCells("C6:J6");
                $sheet->setCellValue("C6", "JENIS KENDARAAN");
                $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("C")->setAutoSize(true);
                //======================================================================
                $sheet->setCellValue("C7", "MOBIL PENUMPANG");
                $sheet->getStyle("C7")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //======================================================================
                $sheet->setCellValue("D7", "MOBIL BUS");
                $sheet->getStyle("D7")->getAlignment()->setWrapText(true);
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("D")->setAutoSize(true);
                //======================================================================
                $sheet->setCellValue("E7", "MOBIL DINAS");
                $sheet->getStyle("E7")->getAlignment()->setWrapText(true);
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("E")->setAutoSize(true);
                //======================================================================
                $sheet->setCellValue("F7", "MOBIL BARANG");
                $sheet->getStyle("F7")->getAlignment()->setWrapText(true);
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("F")->setAutoSize(true);
                //======================================================================
                $sheet->setCellValue("G7", "KRT GANDENG");
                $sheet->getStyle("G7")->getAlignment()->setWrapText(true);
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("G")->setAutoSize(true);
                //======================================================================
                $sheet->setCellValue("H7", "KRT TEMEPELAN");
                $sheet->getStyle("H7")->getAlignment()->setWrapText(true);
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("H")->setAutoSize(true);
                //======================================================================
                $sheet->setCellValue("I7", "TRACTOR HEAD");
                $sheet->getStyle("I7")->getAlignment()->setWrapText(true);
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("I")->setAutoSize(true);
                //======================================================================
                $sheet->setCellValue("J7", "M. BARANG CURAH");
                $sheet->getStyle("J7")->getAlignment()->setWrapText(true);
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("J")->setAutoSize(true);
                //======================================================================
                $sheet->getStyle("A6:J7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                /*
                * BODY UJI PERTAMA
                */
                $baris = 8;
                $baris_awal = $baris;
                $baris_akhir = $baris_awal + 11;
                $no = 1;
                $tahun = $thn - 1;
                for ($bln = 1; $bln <= 12; $bln++) :
                        //======================================================================
                        $dataBarang     = VKementrianKbwu::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $tahun, 'id_jns' => 0));
                        //======================================================================
                        $dataPenumpang  = VKementrianKbwu::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $tahun, 'id_jns' => 1));
                        //======================================================================
                        $dataBis        = VKementrianKbwu::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $tahun, 'id_jns' => 2));
                        //======================================================================
                        $dataTraktor    = VKementrianKbwu::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $tahun, 'id_jns' => 3));
                        //======================================================================
                        $dataGandengan  = VKementrianKbwu::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $tahun, 'id_jns' => 4));
                        //======================================================================
                        $dataTempelan   = VKementrianKbwu::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $tahun, 'id_jns' => 5));
                        //======================================================================
                        $dataCurah      = VKementrianKbwu::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $tahun, 'id_jns' => 6));
                        //======================================================================
                        $dataDinas      = VKementrianKbwu::model()->findByAttributes(array('bulan' => $bln, 'tahun' => $tahun, 'id_jns' => 7));

                        $sheet->setCellValue("A" . $baris, $no);
                        $sheet->setCellValue("B" . $baris, Yii::app()->params['bulanArrayInd'][$bln - 1]);
                        $sheet->setCellValue("C" . $baris, !empty($dataPenumpang) ? $dataPenumpang->jumlah : 0);
                        $sheet->setCellValue("D" . $baris, !empty($dataBis) ? $dataBis->jumlah : 0);
                        $sheet->setCellValue("E" . $baris, !empty($dataDinas) ? $dataDinas->jumlah : 0);
                        $sheet->setCellValue("F" . $baris, !empty($dataBarang) ? $dataBarang->jumlah : 0);
                        $sheet->setCellValue("G" . $baris, !empty($dataGandengan) ? $dataGandengan->jumlah : 0);
                        $sheet->setCellValue("H" . $baris, !empty($dataTempelan) ? $dataTempelan->jumlah : 0);
                        $sheet->setCellValue("I" . $baris, !empty($dataTraktor) ? $dataTraktor->jumlah : 0);
                        $sheet->setCellValue("J" . $baris, !empty($dataCurah) ? $dataCurah->jumlah : 0);
                        $baris++;
                        $no++;
                endfor;
                $sheet->mergeCells("A" . $baris . ":B" . $baris);
                $sheet->setCellValue("A" . $baris, "TOTAL");
                $sheet->setCellValue("C" . $baris, "=SUM(C" . $baris_awal . ":C" . $baris_akhir . ")");
                $sheet->setCellValue("D" . $baris, "=SUM(D" . $baris_awal . ":D" . $baris_akhir . ")");
                $sheet->setCellValue("E" . $baris, "=SUM(E" . $baris_awal . ":E" . $baris_akhir . ")");
                $sheet->setCellValue("F" . $baris, "=SUM(F" . $baris_awal . ":F" . $baris_akhir . ")");
                $sheet->setCellValue("G" . $baris, "=SUM(G" . $baris_awal . ":G" . $baris_akhir . ")");
                $sheet->setCellValue("H" . $baris, "=SUM(H" . $baris_awal . ":H" . $baris_akhir . ")");
                $sheet->setCellValue("I" . $baris, "=SUM(I" . $baris_awal . ":I" . $baris_akhir . ")");
                $sheet->setCellValue("J" . $baris, "=SUM(J" . $baris_awal . ":J" . $baris_akhir . ")");
                $row = $baris + 1;
                $sheet->mergeCells("A" . $row . ":B" . $row);
                $sheet->mergeCells("C" . $row . ":J" . $row);
                $sheet->setCellValue("A" . $row, "TOTAL KENDARAAN");
                $sheet->setCellValue("C" . $row, "=SUM(D" . $baris . ":J" . $baris . ")");
                $sheet->getStyle("A6:J" . $row)->applyFromArray($styleBorder);
                $sheet->getStyle("A" . $baris . ":J" . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('B3C6Cf');
                $sheet->getStyle("A" . $row . ":J" . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('33FF99');
                //======================================================================
                ob_clean();

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="report_kbwu_' . $thn . '.xls"');
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

        public function actionReportBerkala($tgl)
        {
                $blnThn = date("n-Y", strtotime($tgl));
                $explodeBlnThn = explode('-', $blnThn);
                $bln = $explodeBlnThn[0];
                $thn = $explodeBlnThn[1];

                $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

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
                //        $sheet->setTitle('TERDAFTAR');
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setFitToPage(false);
                $sheet->getPageSetup()->setHorizontalCentered(true);
                $sheet->getPageSetup()->setScale(82);
                //=============================================
                $sheet->mergeCells("A1:R1");
                $sheet->mergeCells("A2:R2");
                $sheet->mergeCells("A3:R3");
                $sheet->mergeCells("A4:R4");

                $sheet->setCellValue("A1", "JUMLAH PELAYANAN PENGUJIAN BERKALA KENDARAAN BERMOTOR BERDASARKAN UMUR DAN JBB (KG)");
                $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR");
                $sheet->setCellValue("A3", "DINAS PERHUBUNGAN KABUPATEN SAMPANG, JAWA TIMUR");
                $sheet->setCellValue("A4", "TAHUN : " . $thn . " / BULAN : " . strtoupper(Yii::app()->params['bulanArrayInd'][$bln - 1]));

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
                $sheet->setCellValue("B6", "JENIS KEND");
                $sheet->getStyle("B6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("B")->setWidth(15);
                //=====================================================================================================
                $sheet->mergeCells("C6:C7");
                $sheet->setCellValue("C6", "UMUR (TAHUN)");
                $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("C")->setWidth(10);
                //=====================================================================================================
                $sheet->mergeCells("D6:E6");
                $sheet->setCellValue("D6", "S/D 3500");
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D7", "UMUM");
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("E7", "B.UMUM");
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("D")->setWidth(12);
                $sheet->getColumnDimension("E")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("F6:G6");
                $sheet->setCellValue("F6", "3501 S/D 6000");
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("F7", "UMUM");
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("G7", "B.UMUM");
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("F")->setWidth(12);
                $sheet->getColumnDimension("G")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("H6:I6");
                $sheet->setCellValue("H6", "6001 S/D 9000");
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("H7", "UMUM");
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("I7", "B.UMUM");
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("H")->setWidth(12);
                $sheet->getColumnDimension("I")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("J6:K6");
                $sheet->setCellValue("J6", "9001 S/D 12000");
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("J7", "UMUM");
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("K7", "B.UMUM");
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("J")->setWidth(12);
                $sheet->getColumnDimension("K")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("L6:M6");
                $sheet->setCellValue("L6", "DIATAS 12001");
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("L7", "UMUM");
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("L")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("M7", "B.UMUM");
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("M")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("L")->setWidth(12);
                $sheet->getColumnDimension("M")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("N6:P6");
                $sheet->setCellValue("N6", "JUMLAH");
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("N7", "UMUM");
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("N")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("O7", "B.UMUM");
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("O")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("P7", "U+BU");
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("P")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("N")->setWidth(12);
                $sheet->getColumnDimension("O")->setWidth(12);
                $sheet->getColumnDimension("P")->setWidth(12);
                //=====================================================================================================
                $sheet->mergeCells("Q6:R6");
                $sheet->setCellValue("Q6", "JUMLAH STATUS HASIL UJI");
                $sheet->getStyle("Q6")->getAlignment()->setWrapText(true);
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("Q7", "LULUS");
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("Q")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->setCellValue("R7", "T.LULUS");
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("R")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getColumnDimension("Q")->setWidth(12);
                $sheet->getColumnDimension("R")->setWidth(12);
                //=====================================================================================================

                $sheet->getStyle("A6:R7")->applyFromArray($styleCenter);
                $sheet->getStyle("A6:R7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                /*
         * BODY 
         */
                $criteria3500 = new CDbCriteria();
                $criteria3500->select = "SUM(mp_u_1) AS mp_u_1, SUM(mp_tu_1) AS mp_tu_1, SUM(mp_u_6) AS mp_u_6, SUM(mp_tu_6) AS mp_tu_6, SUM(mp_u_10) AS mp_u_10, SUM(mp_tu_10) AS mp_tu_10,"
                        . "SUM(mb_u_1) AS mb_u_1, SUM(mb_tu_1) AS mb_tu_1, SUM(mb_u_6) AS mb_u_6, SUM(mb_tu_6) AS mb_tu_6, SUM(mb_u_10) AS mb_u_10, SUM(mb_tu_10) AS mb_tu_10,"
                        . "SUM(bus_u_1) AS bus_u_1, SUM(bus_tu_1) AS bus_tu_1, SUM(bus_u_6) AS bus_u_6, SUM(bus_tu_6) AS bus_tu_6, SUM(bus_u_10) AS bus_u_10, SUM(bus_tu_10) AS bus_tu_10,"
                        . "SUM(mk_u_1) AS mk_u_1, SUM(mk_tu_1) AS mk_tu_1, SUM(mk_u_6) AS mk_u_6, SUM(mk_tu_6) AS mk_tu_6, SUM(mk_u_10) AS mk_u_10, SUM(mk_tu_10) AS mk_tu_10,"
                        . "SUM(kg_u_1) AS kg_u_1,SUM(kg_tu_1) AS kg_tu_1,SUM(kg_u_6) AS kg_u_6,SUM(kg_tu_6) AS kg_tu_6,SUM(kg_u_10) AS kg_u_10,SUM(kg_tu_10) AS kg_tu_10,"
                        . "SUM(kt_u_1) AS kt_u_1,SUM(kt_tu_1) AS kt_tu_1,SUM(kt_u_6) AS kt_u_6,SUM(kt_tu_6) AS kt_tu_6,SUM(kt_u_10) AS kt_u_10,SUM(kt_tu_10) AS kt_tu_10";
                $criteria3500->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria3500->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan3500 = VKementrianBerkala3500::model()->find($criteria3500);

                $criteria35016000 = new CDbCriteria();
                $criteria35016000->select = "SUM(mp_u_1) AS mp_u_1, SUM(mp_tu_1) AS mp_tu_1, SUM(mp_u_6) AS mp_u_6, SUM(mp_tu_6) AS mp_tu_6, SUM(mp_u_10) AS mp_u_10, SUM(mp_tu_10) AS mp_tu_10,"
                        . "SUM(mb_u_1) AS mb_u_1, SUM(mb_tu_1) AS mb_tu_1, SUM(mb_u_6) AS mb_u_6, SUM(mb_tu_6) AS mb_tu_6, SUM(mb_u_10) AS mb_u_10, SUM(mb_tu_10) AS mb_tu_10,"
                        . "SUM(bus_u_1) AS bus_u_1, SUM(bus_tu_1) AS bus_tu_1, SUM(bus_u_6) AS bus_u_6, SUM(bus_tu_6) AS bus_tu_6, SUM(bus_u_10) AS bus_u_10, SUM(bus_tu_10) AS bus_tu_10,"
                        . "SUM(mk_u_1) AS mk_u_1, SUM(mk_tu_1) AS mk_tu_1, SUM(mk_u_6) AS mk_u_6, SUM(mk_tu_6) AS mk_tu_6, SUM(mk_u_10) AS mk_u_10, SUM(mk_tu_10) AS mk_tu_10,"
                        . "SUM(kg_u_1) AS kg_u_1,SUM(kg_tu_1) AS kg_tu_1,SUM(kg_u_6) AS kg_u_6,SUM(kg_tu_6) AS kg_tu_6,SUM(kg_u_10) AS kg_u_10,SUM(kg_tu_10) AS kg_tu_10,"
                        . "SUM(kt_u_1) AS kt_u_1,SUM(kt_tu_1) AS kt_tu_1,SUM(kt_u_6) AS kt_u_6,SUM(kt_tu_6) AS kt_tu_6,SUM(kt_u_10) AS kt_u_10,SUM(kt_tu_10) AS kt_tu_10";
                $criteria35016000->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria35016000->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan35016000 = VKementrianBerkala35016000::model()->find($criteria35016000);

                $criteria60019000 = new CDbCriteria();
                $criteria60019000->select = "SUM(mp_u_1) AS mp_u_1, SUM(mp_tu_1) AS mp_tu_1, SUM(mp_u_6) AS mp_u_6, SUM(mp_tu_6) AS mp_tu_6, SUM(mp_u_10) AS mp_u_10, SUM(mp_tu_10) AS mp_tu_10,"
                        . "SUM(mb_u_1) AS mb_u_1, SUM(mb_tu_1) AS mb_tu_1, SUM(mb_u_6) AS mb_u_6, SUM(mb_tu_6) AS mb_tu_6, SUM(mb_u_10) AS mb_u_10, SUM(mb_tu_10) AS mb_tu_10,"
                        . "SUM(bus_u_1) AS bus_u_1, SUM(bus_tu_1) AS bus_tu_1, SUM(bus_u_6) AS bus_u_6, SUM(bus_tu_6) AS bus_tu_6, SUM(bus_u_10) AS bus_u_10, SUM(bus_tu_10) AS bus_tu_10,"
                        . "SUM(mk_u_1) AS mk_u_1, SUM(mk_tu_1) AS mk_tu_1, SUM(mk_u_6) AS mk_u_6, SUM(mk_tu_6) AS mk_tu_6, SUM(mk_u_10) AS mk_u_10, SUM(mk_tu_10) AS mk_tu_10,"
                        . "SUM(kg_u_1) AS kg_u_1,SUM(kg_tu_1) AS kg_tu_1,SUM(kg_u_6) AS kg_u_6,SUM(kg_tu_6) AS kg_tu_6,SUM(kg_u_10) AS kg_u_10,SUM(kg_tu_10) AS kg_tu_10,"
                        . "SUM(kt_u_1) AS kt_u_1,SUM(kt_tu_1) AS kt_tu_1,SUM(kt_u_6) AS kt_u_6,SUM(kt_tu_6) AS kt_tu_6,SUM(kt_u_10) AS kt_u_10,SUM(kt_tu_10) AS kt_tu_10";
                $criteria60019000->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria60019000->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan60019000 = VKementrianBerkala60019000::model()->find($criteria60019000);

                $criteria900112000 = new CDbCriteria();
                $criteria900112000->select = "SUM(mp_u_1) AS mp_u_1, SUM(mp_tu_1) AS mp_tu_1, SUM(mp_u_6) AS mp_u_6, SUM(mp_tu_6) AS mp_tu_6, SUM(mp_u_10) AS mp_u_10, SUM(mp_tu_10) AS mp_tu_10,"
                        . "SUM(mb_u_1) AS mb_u_1, SUM(mb_tu_1) AS mb_tu_1, SUM(mb_u_6) AS mb_u_6, SUM(mb_tu_6) AS mb_tu_6, SUM(mb_u_10) AS mb_u_10, SUM(mb_tu_10) AS mb_tu_10,"
                        . "SUM(bus_u_1) AS bus_u_1, SUM(bus_tu_1) AS bus_tu_1, SUM(bus_u_6) AS bus_u_6, SUM(bus_tu_6) AS bus_tu_6, SUM(bus_u_10) AS bus_u_10, SUM(bus_tu_10) AS bus_tu_10,"
                        . "SUM(mk_u_1) AS mk_u_1, SUM(mk_tu_1) AS mk_tu_1, SUM(mk_u_6) AS mk_u_6, SUM(mk_tu_6) AS mk_tu_6, SUM(mk_u_10) AS mk_u_10, SUM(mk_tu_10) AS mk_tu_10,"
                        . "SUM(kg_u_1) AS kg_u_1,SUM(kg_tu_1) AS kg_tu_1,SUM(kg_u_6) AS kg_u_6,SUM(kg_tu_6) AS kg_tu_6,SUM(kg_u_10) AS kg_u_10,SUM(kg_tu_10) AS kg_tu_10,"
                        . "SUM(kt_u_1) AS kt_u_1,SUM(kt_tu_1) AS kt_tu_1,SUM(kt_u_6) AS kt_u_6,SUM(kt_tu_6) AS kt_tu_6,SUM(kt_u_10) AS kt_u_10,SUM(kt_tu_10) AS kt_tu_10";
                $criteria900112000->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria900112000->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan900112000 = VKementrianBerkala900112000::model()->find($criteria900112000);

                $criteria12000 = new CDbCriteria();
                $criteria12000->select = "SUM(mp_u_1) AS mp_u_1, SUM(mp_tu_1) AS mp_tu_1, SUM(mp_u_6) AS mp_u_6, SUM(mp_tu_6) AS mp_tu_6, SUM(mp_u_10) AS mp_u_10, SUM(mp_tu_10) AS mp_tu_10,"
                        . "SUM(mb_u_1) AS mb_u_1, SUM(mb_tu_1) AS mb_tu_1, SUM(mb_u_6) AS mb_u_6, SUM(mb_tu_6) AS mb_tu_6, SUM(mb_u_10) AS mb_u_10, SUM(mb_tu_10) AS mb_tu_10,"
                        . "SUM(bus_u_1) AS bus_u_1, SUM(bus_tu_1) AS bus_tu_1, SUM(bus_u_6) AS bus_u_6, SUM(bus_tu_6) AS bus_tu_6, SUM(bus_u_10) AS bus_u_10, SUM(bus_tu_10) AS bus_tu_10,"
                        . "SUM(mk_u_1) AS mk_u_1, SUM(mk_tu_1) AS mk_tu_1, SUM(mk_u_6) AS mk_u_6, SUM(mk_tu_6) AS mk_tu_6, SUM(mk_u_10) AS mk_u_10, SUM(mk_tu_10) AS mk_tu_10,"
                        . "SUM(kg_u_1) AS kg_u_1,SUM(kg_tu_1) AS kg_tu_1,SUM(kg_u_6) AS kg_u_6,SUM(kg_tu_6) AS kg_tu_6,SUM(kg_u_10) AS kg_u_10,SUM(kg_tu_10) AS kg_tu_10,"
                        . "SUM(kt_u_1) AS kt_u_1,SUM(kt_tu_1) AS kt_tu_1,SUM(kt_u_6) AS kt_u_6,SUM(kt_tu_6) AS kt_tu_6,SUM(kt_u_10) AS kt_u_10,SUM(kt_tu_10) AS kt_tu_10";
                $criteria12000->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteria12000->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraan12000 = VKementrianBerkala12000::model()->find($criteria12000);

                $criteriaLtl = new CDbCriteria();
                $criteriaLtl->select = "SUM(mb_l_1) AS mb_l_1, SUM(mb_l_6) AS mb_l_6, SUM(mb_l_10) AS mb_l_10, SUM(mb_tl_1) AS mb_tl_1, SUM(mb_tl_6) AS mb_tl_6, SUM(mb_tl_10) AS mb_tl_10,"
                        . "SUM(bus_l_1) AS bus_l_1, SUM(bus_l_6) AS bus_l_6, SUM(bus_l_10) AS bus_l_10, SUM(bus_tl_1) AS bus_tl_1, SUM(bus_tl_6) AS bus_tl_6, SUM(bus_tl_10) AS bus_tl_10,"
                        . "SUM(mp_l_1) AS mp_l_1, SUM(mp_l_6) AS mp_l_6, SUM(mp_l_10) AS mp_l_10, SUM(mp_tl_1) AS mp_tl_1, SUM(mp_tl_6) AS mp_tl_6, SUM(mp_tl_10) AS mp_tl_10,"
                        . "SUM(mk_l_1) AS mk_l_1, SUM(mk_l_6) AS mk_l_6, SUM(mk_l_10) AS mk_l_10, SUM(mk_tl_1) AS mk_tl_1, SUM(mk_tl_6) AS mk_tl_6, SUM(mk_tl_10) AS mk_tl_10,"
                        . "SUM(kg_l_1) AS kg_l_1,SUM(kg_l_6) AS kg_l_6,SUM(kg_l_10) AS kg_l_10,SUM(kg_tl_1) AS kg_tl_1,SUM(kg_tl_6) AS kg_tl_6,SUM(kg_tl_10) AS kg_tl_10,"
                        . "SUM(kt_l_1) AS kt_l_1,SUM(kt_l_6) AS kt_l_6,SUM(kt_l_10) AS kt_l_10,SUM(kt_tl_1) AS kt_tl_1,SUM(kt_tl_6) AS kt_tl_6,SUM(kt_tl_10) AS kt_tl_10";
                $criteriaLtl->addCondition("EXTRACT(YEAR FROM tgl_uji) =" . $thn);
                $criteriaLtl->addCondition("EXTRACT(MONTH FROM tgl_uji) =" . $bln);
                $dataKendaraanLtl = VKementrianBerkalaLtl::model()->find($criteriaLtl);

                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A8:A11");
                $sheet->setCellValue("A8", "1");
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B8:B11");
                $sheet->setCellValue("B8", "MOBIL PENUMPANG");
                $sheet->getStyle("B8")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C8", "1-5");
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C8")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D8", $dataKendaraan3500->mp_u_1);
                $sheet->setCellValue("E8", $dataKendaraan3500->mp_tu_1);
                $sheet->setCellValue("F8", $dataKendaraan35016000->mp_u_1);
                $sheet->setCellValue("G8", $dataKendaraan35016000->mp_tu_1);
                $sheet->setCellValue("H8", $dataKendaraan60019000->mp_u_1);
                $sheet->setCellValue("I8", $dataKendaraan60019000->mp_tu_1);
                $sheet->setCellValue("J8", $dataKendaraan900112000->mp_u_1);
                $sheet->setCellValue("K8", $dataKendaraan900112000->mp_tu_1);
                $sheet->setCellValue("L8", $dataKendaraan12000->mp_u_1);
                $sheet->setCellValue("M8", $dataKendaraan12000->mp_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C9", "6-10");
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C9")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D9", $dataKendaraan3500->mp_u_6);
                $sheet->setCellValue("E9", $dataKendaraan3500->mp_tu_6);
                $sheet->setCellValue("F9", $dataKendaraan35016000->mp_u_6);
                $sheet->setCellValue("G9", $dataKendaraan35016000->mp_tu_6);
                $sheet->setCellValue("H9", $dataKendaraan60019000->mp_u_6);
                $sheet->setCellValue("I9", $dataKendaraan60019000->mp_tu_6);
                $sheet->setCellValue("J9", $dataKendaraan900112000->mp_u_6);
                $sheet->setCellValue("K9", $dataKendaraan900112000->mp_tu_6);
                $sheet->setCellValue("L9", $dataKendaraan12000->mp_u_6);
                $sheet->setCellValue("M9", $dataKendaraan12000->mp_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C10", ">10");
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C10")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D10", $dataKendaraan3500->mp_u_10);
                $sheet->setCellValue("E10", $dataKendaraan3500->mp_tu_10);
                $sheet->setCellValue("F10", $dataKendaraan35016000->mp_u_10);
                $sheet->setCellValue("G10", $dataKendaraan35016000->mp_tu_10);
                $sheet->setCellValue("H10", $dataKendaraan60019000->mp_u_10);
                $sheet->setCellValue("I10", $dataKendaraan60019000->mp_tu_10);
                $sheet->setCellValue("J10", $dataKendaraan900112000->mp_u_10);
                $sheet->setCellValue("K10", $dataKendaraan900112000->mp_tu_10);
                $sheet->setCellValue("L10", $dataKendaraan12000->mp_u_10);
                $sheet->setCellValue("M10", $dataKendaraan12000->mp_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C11", "JUMLAH");
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C11")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C11:R11")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D11", '=SUM(D8:D10)');
                $sheet->setCellValue("E11", '=SUM(E8:E10)');
                $sheet->setCellValue("F11", '=SUM(F8:F10)');
                $sheet->setCellValue("G11", '=SUM(G8:G10)');
                $sheet->setCellValue("H11", '=SUM(H8:H10)');
                $sheet->setCellValue("I11", '=SUM(I8:I10)');
                $sheet->setCellValue("J11", '=SUM(J8:J10)');
                $sheet->setCellValue("K11", '=SUM(K8:K10)');
                $sheet->setCellValue("L11", '=SUM(L8:L10)');
                $sheet->setCellValue("M11", '=SUM(M8:M10)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A12:A15");
                $sheet->setCellValue("A12", "2");
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B12:B15");
                $sheet->setCellValue("B12", "MOBIL BUS");
                $sheet->getStyle("B12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C12", "1-5");
                $sheet->getStyle("C12")->getAlignment()->setWrapText(true);
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C12")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D12", $dataKendaraan3500->bus_u_1);
                $sheet->setCellValue("E12", $dataKendaraan3500->bus_tu_1);
                $sheet->setCellValue("F12", $dataKendaraan35016000->bus_u_1);
                $sheet->setCellValue("G12", $dataKendaraan35016000->bus_tu_1);
                $sheet->setCellValue("H12", $dataKendaraan60019000->bus_u_1);
                $sheet->setCellValue("I12", $dataKendaraan60019000->bus_tu_1);
                $sheet->setCellValue("J12", $dataKendaraan900112000->bus_u_1);
                $sheet->setCellValue("K12", $dataKendaraan900112000->bus_tu_1);
                $sheet->setCellValue("L12", $dataKendaraan12000->bus_u_1);
                $sheet->setCellValue("M12", $dataKendaraan12000->bus_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C13", "6-10");
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C13")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D13", $dataKendaraan3500->bus_u_6);
                $sheet->setCellValue("E13", $dataKendaraan3500->bus_tu_6);
                $sheet->setCellValue("F13", $dataKendaraan35016000->bus_u_6);
                $sheet->setCellValue("G13", $dataKendaraan35016000->bus_tu_6);
                $sheet->setCellValue("H13", $dataKendaraan60019000->bus_u_6);
                $sheet->setCellValue("I13", $dataKendaraan60019000->bus_tu_6);
                $sheet->setCellValue("J13", $dataKendaraan900112000->bus_u_6);
                $sheet->setCellValue("K13", $dataKendaraan900112000->bus_tu_6);
                $sheet->setCellValue("L13", $dataKendaraan12000->bus_u_6);
                $sheet->setCellValue("M13", $dataKendaraan12000->bus_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C14", ">10");
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C14")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D14", $dataKendaraan3500->bus_u_10);
                $sheet->setCellValue("E14", $dataKendaraan3500->bus_tu_10);
                $sheet->setCellValue("F14", $dataKendaraan35016000->bus_u_10);
                $sheet->setCellValue("G14", $dataKendaraan35016000->bus_tu_10);
                $sheet->setCellValue("H14", $dataKendaraan60019000->bus_u_10);
                $sheet->setCellValue("I14", $dataKendaraan60019000->bus_tu_10);
                $sheet->setCellValue("J14", $dataKendaraan900112000->bus_u_10);
                $sheet->setCellValue("K14", $dataKendaraan900112000->bus_tu_10);
                $sheet->setCellValue("L14", $dataKendaraan12000->bus_u_10);
                $sheet->setCellValue("M14", $dataKendaraan12000->bus_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C15", "JUMLAH");
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C15")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C15:R15")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D15", '=SUM(D12:D14)');
                $sheet->setCellValue("E15", '=SUM(E12:E14)');
                $sheet->setCellValue("F15", '=SUM(F12:F14)');
                $sheet->setCellValue("G15", '=SUM(G12:G14)');
                $sheet->setCellValue("H15", '=SUM(H12:H14)');
                $sheet->setCellValue("I15", '=SUM(I12:I14)');
                $sheet->setCellValue("J15", '=SUM(J12:J14)');
                $sheet->setCellValue("K15", '=SUM(K12:K14)');
                $sheet->setCellValue("L15", '=SUM(L12:L14)');
                $sheet->setCellValue("M15", '=SUM(M12:M14)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A16:A19");
                $sheet->setCellValue("A16", "3");
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B16:B19");
                $sheet->setCellValue("B16", "MOBIL KHUSUS");
                $sheet->getStyle("B16")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C16", "1-5");
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C16:M16")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D16", $dataKendaraan3500->mk_u_1);
                $sheet->setCellValue("E16", $dataKendaraan3500->mk_tu_1);
                $sheet->setCellValue("F16", $dataKendaraan35016000->mk_u_1);
                $sheet->setCellValue("G16", $dataKendaraan35016000->mk_tu_1);
                $sheet->setCellValue("H16", $dataKendaraan60019000->mk_u_1);
                $sheet->setCellValue("I16", $dataKendaraan60019000->mk_tu_1);
                $sheet->setCellValue("J16", $dataKendaraan900112000->mk_u_1);
                $sheet->setCellValue("K16", $dataKendaraan900112000->mk_tu_1);
                $sheet->setCellValue("L16", $dataKendaraan12000->mk_u_1);
                $sheet->setCellValue("M16", $dataKendaraan12000->mk_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C17", "6-10");
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C17:M17")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D17", $dataKendaraan3500->mk_u_6);
                $sheet->setCellValue("E17", $dataKendaraan3500->mk_tu_6);
                $sheet->setCellValue("F17", $dataKendaraan35016000->mk_u_6);
                $sheet->setCellValue("G17", $dataKendaraan35016000->mk_tu_6);
                $sheet->setCellValue("H17", $dataKendaraan60019000->mk_u_6);
                $sheet->setCellValue("I17", $dataKendaraan60019000->mk_tu_6);
                $sheet->setCellValue("J17", $dataKendaraan900112000->mk_u_6);
                $sheet->setCellValue("K17", $dataKendaraan900112000->mk_tu_6);
                $sheet->setCellValue("L17", $dataKendaraan12000->mk_u_6);
                $sheet->setCellValue("M17", $dataKendaraan12000->mk_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C18", ">10");
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C18:M18")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D18", $dataKendaraan3500->mk_u_10);
                $sheet->setCellValue("E18", $dataKendaraan3500->mk_tu_10);
                $sheet->setCellValue("F18", $dataKendaraan35016000->mk_u_10);
                $sheet->setCellValue("G18", $dataKendaraan35016000->mk_tu_10);
                $sheet->setCellValue("H18", $dataKendaraan60019000->mk_u_10);
                $sheet->setCellValue("I18", $dataKendaraan60019000->mk_tu_10);
                $sheet->setCellValue("J18", $dataKendaraan900112000->mk_u_10);
                $sheet->setCellValue("K18", $dataKendaraan900112000->mk_tu_10);
                $sheet->setCellValue("L18", $dataKendaraan12000->mk_u_10);
                $sheet->setCellValue("M18", $dataKendaraan12000->mk_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C19", "JUMLAH");
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C19")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C19:R19")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D19", '=SUM(D16:D18)');
                $sheet->setCellValue("E19", '=SUM(E16:E18)');
                $sheet->setCellValue("F19", '=SUM(F16:F18)');
                $sheet->setCellValue("G19", '=SUM(G16:G18)');
                $sheet->setCellValue("H19", '=SUM(H16:H18)');
                $sheet->setCellValue("I19", '=SUM(I16:I18)');
                $sheet->setCellValue("J19", '=SUM(J16:J18)');
                $sheet->setCellValue("K19", '=SUM(K16:K18)');
                $sheet->setCellValue("L19", '=SUM(L16:L18)');
                $sheet->setCellValue("M19", '=SUM(M16:M18)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A20:A23");
                $sheet->setCellValue("A20", "4");
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B20:B23");
                $sheet->setCellValue("B20", "MOBIL BARANG");
                $sheet->getStyle("B20")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C20", "1-5");
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C20")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D20", $dataKendaraan3500->mb_u_1);
                $sheet->setCellValue("E20", $dataKendaraan3500->mb_tu_1);
                $sheet->setCellValue("F20", $dataKendaraan35016000->mb_u_1);
                $sheet->setCellValue("G20", $dataKendaraan35016000->mb_tu_1);
                $sheet->setCellValue("H20", $dataKendaraan60019000->mb_u_1);
                $sheet->setCellValue("I20", $dataKendaraan60019000->mb_tu_1);
                $sheet->setCellValue("J20", $dataKendaraan900112000->mb_u_1);
                $sheet->setCellValue("K20", $dataKendaraan900112000->mb_tu_1);
                $sheet->setCellValue("L20", $dataKendaraan12000->mb_u_1);
                $sheet->setCellValue("M20", $dataKendaraan12000->mb_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C21", "6-10");
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C21")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D21", $dataKendaraan3500->mb_u_6);
                $sheet->setCellValue("E21", $dataKendaraan3500->mb_tu_6);
                $sheet->setCellValue("F21", $dataKendaraan35016000->mb_u_6);
                $sheet->setCellValue("G21", $dataKendaraan35016000->mb_tu_6);
                $sheet->setCellValue("H21", $dataKendaraan60019000->mb_u_6);
                $sheet->setCellValue("I21", $dataKendaraan60019000->mb_tu_6);
                $sheet->setCellValue("J21", $dataKendaraan900112000->mb_u_6);
                $sheet->setCellValue("K21", $dataKendaraan900112000->mb_tu_6);
                $sheet->setCellValue("L21", $dataKendaraan12000->mb_u_6);
                $sheet->setCellValue("M21", $dataKendaraan12000->mb_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C22", ">10");
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C22")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D22", $dataKendaraan3500->mb_u_10);
                $sheet->setCellValue("E22", $dataKendaraan3500->mb_tu_10);
                $sheet->setCellValue("F22", $dataKendaraan35016000->mb_u_10);
                $sheet->setCellValue("G22", $dataKendaraan35016000->mb_tu_10);
                $sheet->setCellValue("H22", $dataKendaraan60019000->mb_u_10);
                $sheet->setCellValue("I22", $dataKendaraan60019000->mb_tu_10);
                $sheet->setCellValue("J22", $dataKendaraan900112000->mb_u_10);
                $sheet->setCellValue("K22", $dataKendaraan900112000->mb_tu_10);
                $sheet->setCellValue("L22", $dataKendaraan12000->mb_u_10);
                $sheet->setCellValue("M22", $dataKendaraan12000->mb_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C23", "JUMLAH");
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C23")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C23:R23")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D23", '=SUM(D20:D22)');
                $sheet->setCellValue("E23", '=SUM(E20:E22)');
                $sheet->setCellValue("F23", '=SUM(F20:F22)');
                $sheet->setCellValue("G23", '=SUM(G20:G22)');
                $sheet->setCellValue("H23", '=SUM(H20:H22)');
                $sheet->setCellValue("I23", '=SUM(I20:I22)');
                $sheet->setCellValue("J23", '=SUM(J20:J22)');
                $sheet->setCellValue("K23", '=SUM(K20:K22)');
                $sheet->setCellValue("L23", '=SUM(L20:L22)');
                $sheet->setCellValue("M23", '=SUM(M20:M22)');
                //======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A24:A27");
                $sheet->setCellValue("A24", "5");
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B24:B27");
                $sheet->setCellValue("B24", "KERETA GANDENG");
                $sheet->getStyle("B24")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C24", "1-5");
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C24")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D24", $dataKendaraan3500->kg_u_1);
                $sheet->setCellValue("E24", $dataKendaraan3500->kg_tu_1);
                $sheet->setCellValue("F24", $dataKendaraan35016000->kg_u_1);
                $sheet->setCellValue("G24", $dataKendaraan35016000->kg_tu_1);
                $sheet->setCellValue("H24", $dataKendaraan60019000->kg_u_1);
                $sheet->setCellValue("I24", $dataKendaraan60019000->kg_tu_1);
                $sheet->setCellValue("J24", $dataKendaraan900112000->kg_u_1);
                $sheet->setCellValue("K24", $dataKendaraan900112000->kg_tu_1);
                $sheet->setCellValue("L24", $dataKendaraan12000->kg_u_1);
                $sheet->setCellValue("M24", $dataKendaraan12000->kg_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C25", "6-10");
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C25")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D25", $dataKendaraan3500->kg_u_6);
                $sheet->setCellValue("E25", $dataKendaraan3500->kg_tu_6);
                $sheet->setCellValue("F25", $dataKendaraan35016000->kg_u_6);
                $sheet->setCellValue("G25", $dataKendaraan35016000->kg_tu_6);
                $sheet->setCellValue("H25", $dataKendaraan60019000->kg_u_6);
                $sheet->setCellValue("I25", $dataKendaraan60019000->kg_tu_6);
                $sheet->setCellValue("J25", $dataKendaraan900112000->kg_u_6);
                $sheet->setCellValue("K25", $dataKendaraan900112000->kg_tu_6);
                $sheet->setCellValue("L25", $dataKendaraan12000->kg_u_6);
                $sheet->setCellValue("M25", $dataKendaraan12000->kg_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C26", ">10");
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C26")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D26", $dataKendaraan3500->kg_u_10);
                $sheet->setCellValue("E26", $dataKendaraan3500->kg_tu_10);
                $sheet->setCellValue("F26", $dataKendaraan35016000->kg_u_10);
                $sheet->setCellValue("G26", $dataKendaraan35016000->kg_tu_10);
                $sheet->setCellValue("H26", $dataKendaraan60019000->kg_u_10);
                $sheet->setCellValue("I26", $dataKendaraan60019000->kg_tu_10);
                $sheet->setCellValue("J26", $dataKendaraan900112000->kg_u_10);
                $sheet->setCellValue("K26", $dataKendaraan900112000->kg_tu_10);
                $sheet->setCellValue("L26", $dataKendaraan12000->kg_u_10);
                $sheet->setCellValue("M26", $dataKendaraan12000->kg_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C27", "JUMLAH");
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C27")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C27:R27")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D27", '=SUM(D24:D26)');
                $sheet->setCellValue("E27", '=SUM(E24:E26)');
                $sheet->setCellValue("F27", '=SUM(F24:F26)');
                $sheet->setCellValue("G27", '=SUM(G24:G26)');
                $sheet->setCellValue("H27", '=SUM(H24:H26)');
                $sheet->setCellValue("I27", '=SUM(I24:I26)');
                $sheet->setCellValue("J27", '=SUM(J24:J26)');
                $sheet->setCellValue("K27", '=SUM(K24:K26)');
                $sheet->setCellValue("L27", '=SUM(L24:L26)');
                $sheet->setCellValue("M27", '=SUM(M24:M26)');
                //======================================================================
                ////======================================================================
                //======================================================================
                //======================================================================
                $sheet->mergeCells("A28:A31");
                $sheet->setCellValue("A28", "6");
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("A28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->mergeCells("B28:B31");
                $sheet->setCellValue("B28", "KERETA TEMPEL");
                $sheet->getStyle("B28")->getAlignment()->setWrapText(true);
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("B28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                //-------------------------------------------
                $sheet->setCellValue("C28", "1-5");
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C28")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D28", $dataKendaraan3500->kt_u_1);
                $sheet->setCellValue("E28", $dataKendaraan3500->kt_tu_1);
                $sheet->setCellValue("F28", $dataKendaraan35016000->kt_u_1);
                $sheet->setCellValue("G28", $dataKendaraan35016000->kt_tu_1);
                $sheet->setCellValue("H28", $dataKendaraan60019000->kt_u_1);
                $sheet->setCellValue("I28", $dataKendaraan60019000->kt_tu_1);
                $sheet->setCellValue("J28", $dataKendaraan900112000->kt_u_1);
                $sheet->setCellValue("K28", $dataKendaraan900112000->kt_tu_1);
                $sheet->setCellValue("L28", $dataKendaraan12000->kt_u_1);
                $sheet->setCellValue("M28", $dataKendaraan12000->kt_tu_1);
                //-------------------------------------------
                $sheet->setCellValue("C29", "6-10");
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C29")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D29", $dataKendaraan3500->kt_u_6);
                $sheet->setCellValue("E29", $dataKendaraan3500->kt_tu_6);
                $sheet->setCellValue("F29", $dataKendaraan35016000->kt_u_6);
                $sheet->setCellValue("G29", $dataKendaraan35016000->kt_tu_6);
                $sheet->setCellValue("H29", $dataKendaraan60019000->kt_u_6);
                $sheet->setCellValue("I29", $dataKendaraan60019000->kt_tu_6);
                $sheet->setCellValue("J29", $dataKendaraan900112000->kt_u_6);
                $sheet->setCellValue("K29", $dataKendaraan900112000->kt_tu_6);
                $sheet->setCellValue("L29", $dataKendaraan12000->kt_u_6);
                $sheet->setCellValue("M29", $dataKendaraan12000->kt_tu_6);
                //-------------------------------------------
                $sheet->setCellValue("C30", ">10");
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C30")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

                $sheet->setCellValue("D30", $dataKendaraan3500->kt_u_10);
                $sheet->setCellValue("E30", $dataKendaraan3500->kt_tu_10);
                $sheet->setCellValue("F30", $dataKendaraan35016000->kt_u_10);
                $sheet->setCellValue("G30", $dataKendaraan35016000->kt_tu_10);
                $sheet->setCellValue("H30", $dataKendaraan60019000->kt_u_10);
                $sheet->setCellValue("I30", $dataKendaraan60019000->kt_tu_10);
                $sheet->setCellValue("J30", $dataKendaraan900112000->kt_u_10);
                $sheet->setCellValue("K30", $dataKendaraan900112000->kt_tu_10);
                $sheet->setCellValue("L30", $dataKendaraan12000->kt_u_10);
                $sheet->setCellValue("M30", $dataKendaraan12000->kt_tu_10);
                //-------------------------------------------
                $sheet->setCellValue("C31", "JUMLAH");
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
                $sheet->getStyle("C31")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
                $sheet->getStyle("C31:R31")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('f2f2f2');

                $sheet->setCellValue("D31", '=SUM(D28:D30)');
                $sheet->setCellValue("E31", '=SUM(E28:E30)');
                $sheet->setCellValue("F31", '=SUM(F28:F30)');
                $sheet->setCellValue("G31", '=SUM(G28:G30)');
                $sheet->setCellValue("H31", '=SUM(H28:H30)');
                $sheet->setCellValue("I31", '=SUM(I28:I30)');
                $sheet->setCellValue("J31", '=SUM(J28:J30)');
                $sheet->setCellValue("K31", '=SUM(K28:K30)');
                $sheet->setCellValue("L31", '=SUM(L28:L30)');
                $sheet->setCellValue("M31", '=SUM(M28:M30)');
                //======================================================================
                //JUMLAH UMUM & BUKAN UMUM
                //======================================================================
                //UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("N8", '=SUM(D8,F8,H8,J8,L8)');
                $sheet->setCellValue("N9", '=SUM(D9,F9,H9,J9,L9)');
                $sheet->setCellValue("N10", '=SUM(D10,F10,H10,J10,L10)');
                $sheet->setCellValue("N11", '=SUM(N8:N10)');
                //MOBIL BUS
                $sheet->setCellValue("N12", '=SUM(D12,F12,H12,J12,L12)');
                $sheet->setCellValue("N13", '=SUM(D13,F13,H13,J13,L13)');
                $sheet->setCellValue("N14", '=SUM(D14,F14,H14,J14,L14)');
                $sheet->setCellValue("N15", '=SUM(N12:N14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("N16", '=SUM(D16,F16,H16,J16,L16)');
                $sheet->setCellValue("N17", '=SUM(D17,F17,H17,J17,L17)');
                $sheet->setCellValue("N18", '=SUM(D18,F18,H18,J18,L18)');
                $sheet->setCellValue("N19", '=SUM(N16:N18)');
                //MOBIL BARANG
                $sheet->setCellValue("N20", '=SUM(D20,F20,H20,J20,L20)');
                $sheet->setCellValue("N21", '=SUM(D21,F21,H21,J21,L21)');
                $sheet->setCellValue("N22", '=SUM(D22,F22,H22,J22,L22)');
                $sheet->setCellValue("N23", '=SUM(N20:N22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("N24", '=SUM(D24,F24,H24,J24,L24)');
                $sheet->setCellValue("N25", '=SUM(D25,F25,H25,J25,L25)');
                $sheet->setCellValue("N26", '=SUM(D26,F26,H26,J26,L26)');
                $sheet->setCellValue("N27", '=SUM(N24:N26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("N28", '=SUM(D28,F28,H28,J28,L28)');
                $sheet->setCellValue("N29", '=SUM(D29,F29,H29,J29,L29)');
                $sheet->setCellValue("N30", '=SUM(D30,F30,H30,J30,L30)');
                $sheet->setCellValue("N31", '=SUM(N28:N30)');
                //======================================================================
                //BUKAN UMUM
                //======================================================================
                //MOBIL PENUMPANG
                $sheet->setCellValue("O8", '=SUM(E8,G8,I8,K8,M8)');
                $sheet->setCellValue("O9", '=SUM(E9,G9,I9,K9,M9)');
                $sheet->setCellValue("O10", '=SUM(E10,G10,I10,K10,M10)');
                $sheet->setCellValue("O11", '=SUM(O8:O10)');
                //MOBIL BUS
                $sheet->setCellValue("O12", '=SUM(E12,G12,I12,K12,M12)');
                $sheet->setCellValue("O13", '=SUM(E13,G13,I13,K13,M13)');
                $sheet->setCellValue("O14", '=SUM(E14,G14,I14,K14,M14)');
                $sheet->setCellValue("O15", '=SUM(O12:O14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("O16", '=SUM(E16,G16,I16,K16,M16)');
                $sheet->setCellValue("O17", '=SUM(E17,G17,I17,K17,M17)');
                $sheet->setCellValue("O18", '=SUM(E18,G18,I18,K18,M18)');
                $sheet->setCellValue("O19", '=SUM(O16:O18)');
                //MOBIL BARANG
                $sheet->setCellValue("O20", '=SUM(E20,G20,I20,K20,M20)');
                $sheet->setCellValue("O21", '=SUM(E21,G21,I21,K21,M21)');
                $sheet->setCellValue("O22", '=SUM(E22,G22,I22,K22,M22)');
                $sheet->setCellValue("O23", '=SUM(O20:O22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("O24", '=SUM(E24,G24,I24,K24,M24)');
                $sheet->setCellValue("O25", '=SUM(E25,G25,I25,K25,M25)');
                $sheet->setCellValue("O26", '=SUM(E26,G26,I26,K26,M26)');
                $sheet->setCellValue("O27", '=SUM(O24:O26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("O28", '=SUM(E28,G28,I28,K28,M28)');
                $sheet->setCellValue("O29", '=SUM(E29,G29,I29,K29,M29)');
                $sheet->setCellValue("O30", '=SUM(E30,G30,I30,K30,M30)');
                $sheet->setCellValue("O31", '=SUM(O28:O30)');
                //UMUM + BUKAN UMUM
                $sheet->setCellValue("P8", '=SUM(N8,O8)');
                $sheet->setCellValue("P9", '=SUM(N9,O9)');
                $sheet->setCellValue("P10", '=SUM(N10,O10)');
                $sheet->setCellValue("P11", '=SUM(P8:P10)');
                $sheet->setCellValue("P12", '=SUM(N12,O12)');
                $sheet->setCellValue("P13", '=SUM(N13,O13)');
                $sheet->setCellValue("P14", '=SUM(N14,O14)');
                $sheet->setCellValue("P15", '=SUM(P12:P14)');
                $sheet->setCellValue("P16", '=SUM(N16,O16)');
                $sheet->setCellValue("P17", '=SUM(N17,O17)');
                $sheet->setCellValue("P18", '=SUM(N18,O18)');
                $sheet->setCellValue("P19", '=SUM(P16:P18)');
                $sheet->setCellValue("P20", '=SUM(N20:O20)');
                $sheet->setCellValue("P21", '=SUM(N21:O21)');
                $sheet->setCellValue("P22", '=SUM(N22:O22)');
                $sheet->setCellValue("P23", '=SUM(P20:P22)');
                $sheet->setCellValue("P24", '=SUM(N24:O24)');
                $sheet->setCellValue("P25", '=SUM(N25:O25)');
                $sheet->setCellValue("P26", '=SUM(N26:O26)');
                $sheet->setCellValue("P27", '=SUM(P24:P26)');
                $sheet->setCellValue("P28", '=SUM(N28:O28)');
                $sheet->setCellValue("P29", '=SUM(N29:O29)');
                $sheet->setCellValue("P30", '=SUM(N30:O30)');
                $sheet->setCellValue("P31", '=SUM(P28:P30)');
                //======================================================================
                //JUMLAH STATUS HASIL UJI
                //MOBIL PENUMPANG
                $sheet->setCellValue("Q8", $dataKendaraanLtl->mp_l_1);
                $sheet->setCellValue("R8", $dataKendaraanLtl->mp_tl_1);
                $sheet->setCellValue("Q9", $dataKendaraanLtl->mp_l_6);
                $sheet->setCellValue("R9", $dataKendaraanLtl->mp_tl_6);
                $sheet->setCellValue("Q10", $dataKendaraanLtl->mp_l_10);
                $sheet->setCellValue("R10", $dataKendaraanLtl->mp_tl_10);
                $sheet->setCellValue("Q11", '=SUM(Q8:Q10)');
                $sheet->setCellValue("R11", '=SUM(R8:R10)');
                //MOBIL BUS
                $sheet->setCellValue("Q12", $dataKendaraanLtl->bus_l_1);
                $sheet->setCellValue("R12", $dataKendaraanLtl->bus_tl_1);
                $sheet->setCellValue("Q13", $dataKendaraanLtl->bus_l_6);
                $sheet->setCellValue("R13", $dataKendaraanLtl->bus_tl_6);
                $sheet->setCellValue("Q14", $dataKendaraanLtl->bus_l_10);
                $sheet->setCellValue("R14", $dataKendaraanLtl->bus_tl_10);
                $sheet->setCellValue("Q15", '=SUM(Q12:Q14)');
                $sheet->setCellValue("R15", '=SUM(R12:R14)');
                //MOBIL KHUSUS
                $sheet->setCellValue("Q16", $dataKendaraanLtl->mk_l_1);
                $sheet->setCellValue("R16", $dataKendaraanLtl->mk_tl_1);
                $sheet->setCellValue("Q17", $dataKendaraanLtl->mk_l_6);
                $sheet->setCellValue("R17", $dataKendaraanLtl->mk_tl_6);
                $sheet->setCellValue("Q18", $dataKendaraanLtl->mk_l_10);
                $sheet->setCellValue("R18", $dataKendaraanLtl->mk_tl_10);
                $sheet->setCellValue("Q19", '=SUM(Q16:Q18)');
                $sheet->setCellValue("R19", '=SUM(R16:R18)');
                //MOBIL BARANG
                $sheet->setCellValue("Q20", $dataKendaraanLtl->mb_l_1);
                $sheet->setCellValue("R20", $dataKendaraanLtl->mb_tl_1);
                $sheet->setCellValue("Q21", $dataKendaraanLtl->mb_l_6);
                $sheet->setCellValue("R21", $dataKendaraanLtl->mb_tl_6);
                $sheet->setCellValue("Q22", $dataKendaraanLtl->mb_l_10);
                $sheet->setCellValue("R22", $dataKendaraanLtl->mb_tl_10);
                $sheet->setCellValue("Q23", '=SUM(Q20:Q22)');
                $sheet->setCellValue("R23", '=SUM(R20:R22)');
                //KERETA GANDENGAN
                $sheet->setCellValue("Q24", $dataKendaraanLtl->kg_l_1);
                $sheet->setCellValue("R24", $dataKendaraanLtl->kg_tl_1);
                $sheet->setCellValue("Q25", $dataKendaraanLtl->kg_l_6);
                $sheet->setCellValue("R25", $dataKendaraanLtl->kg_tl_6);
                $sheet->setCellValue("Q26", $dataKendaraanLtl->kg_l_10);
                $sheet->setCellValue("R26", $dataKendaraanLtl->kg_tl_10);
                $sheet->setCellValue("Q27", '=SUM(Q24:Q26)');
                $sheet->setCellValue("R27", '=SUM(R24:R26)');
                //KERETA TEMPELAN
                $sheet->setCellValue("Q28", $dataKendaraanLtl->kt_l_1);
                $sheet->setCellValue("R28", $dataKendaraanLtl->kt_tl_1);
                $sheet->setCellValue("Q29", $dataKendaraanLtl->kt_l_6);
                $sheet->setCellValue("R29", $dataKendaraanLtl->kt_tl_6);
                $sheet->setCellValue("Q30", $dataKendaraanLtl->kt_l_10);
                $sheet->setCellValue("R30", $dataKendaraanLtl->kt_tl_10);
                $sheet->setCellValue("Q31", '=SUM(Q28:Q30)');
                $sheet->setCellValue("R31", '=SUM(R28:R30)');
                //======================================================================
                $sheet->mergeCells("A32:C32");
                $sheet->setCellValue("A32", "JUMLAH");
                $sheet->setCellValue("D32", "=SUM(D11,D15,D19,D23,D27,D31)");
                $sheet->setCellValue("E32", "=SUM(E11,E15,E19,E23,E27,E31)");
                $sheet->setCellValue("F32", "=SUM(F11,F15,F19,F23,F27,F31)");
                $sheet->setCellValue("G32", "=SUM(G11,G15,G19,G23,G27,G31)");
                $sheet->setCellValue("H32", "=SUM(H11,H15,H19,H23,H27,H31)");
                $sheet->setCellValue("I32", "=SUM(I11,I15,I19,I23,I27,I31)");
                $sheet->setCellValue("J32", "=SUM(J11,J15,J19,J23,J27,J31)");
                $sheet->setCellValue("K32", "=SUM(K11,K15,K19,K23,K27,K31)");
                $sheet->setCellValue("L32", "=SUM(L11,L15,L19,L23,L27,L31)");
                $sheet->setCellValue("M32", "=SUM(M11,M15,M19,M23,M27,M31)");
                $sheet->setCellValue("N32", "=SUM(N11,N15,N19,N23,N27,N31)");
                $sheet->setCellValue("O32", "=SUM(O11,O15,O19,O23,O27,O31)");
                $sheet->setCellValue("P32", "=SUM(P11,P15,P19,P23,P27,P31)");
                $sheet->setCellValue("Q32", "=SUM(Q11,Q15,Q19,Q23,Q27,Q31)");
                $sheet->setCellValue("R32", "=SUM(R11,R15,R19,R23,R27,R31)");
                $sheet->getStyle("A32:R32")->applyFromArray($styleCenter);
                $sheet->getStyle("A32:R32")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
                //=====================================================================================================
                $sheet->getStyle("A6:R32")->applyFromArray($styleBorder);
                ob_clean();

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="KEMENTRIAN BERKALA ' . $bln . '_' . $thn . '.xls"');
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
