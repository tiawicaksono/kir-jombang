<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BapTlController extends Controller
{

    public function filters()
    {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }

    public function actionReportRiwayatPemeriksaan()
    {
        $id_hasil_uji = $_GET['id_hasil_uji'];
        $data = VVerifikasi::model()->findByAttributes(array('id_hasil_uji' => $id_hasil_uji));
        $dataUji = VPrintHasil::model()->findByAttributes(array('id_hasil_uji' => $id_hasil_uji));

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $xls->createSheet(0);
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('Riwayat Pemeriksaan');
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        // $sheet->getPageSetup()->setVerticalCentered(true);
        $sheet->getPageSetup()->setScale(90);
        //======================================================================
        $styleTengah = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            )
        );
        $styleTengahHorizontal = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            )
        );
        $styleTop = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            )
        );
        $styleFont9 = array(
            'font' => array(
                'size' => 9,
                'name' => 'Arial'
            )
        );
        $styleFont11 = array(
            'font' => array(
                'size' => 11,
                'name' => 'Arial'
            )
        );
        $styleFont12 = array(
            'font' => array(
                'size' => 12,
                'name' => 'Arial'
            )
        );
        $styleBold9 = array(
            'font' => array(
                'bold' => true,
                'size' => 9,
                'name' => 'Arial'
            )
        );
        $styleBold11 = array(
            'font' => array(
                'bold' => true,
                'size' => 11,
                'name' => 'Arial'
            )
        );
        $styleBold12 = array(
            'font' => array(
                'bold' => true,
                'size' => 12,
                'name' => 'Arial'
            )
        );
        //======================================================================
        //HEADER        
        $sheet->getColumnDimension('A')->setWidth(3.78);
        $sheet->getColumnDimension('B')->setWidth(13.77);
        $sheet->getColumnDimension('F')->setWidth(3.78);
        $sheet->getColumnDimension('G')->setWidth(13.77);
        $sheet->getRowDimension(3)->setVisible(FALSE);

        $sheet->mergeCells("A1:J1");
        $sheet->setCellValue("A1", "PENGUJIAN KENDARAAN BERMOTOR");
        $sheet->getStyle("A1")->applyFromArray($styleBold12);
        $sheet->getStyle("A1")->applyFromArray($styleTengah);

        $sheet->mergeCells("A2:J2");
        $sheet->setCellValue("A2", "DINAS PERHUBUNGAN KABUPATEN SAMPANG");
        $sheet->getStyle("A2")->applyFromArray($styleBold12);
        $sheet->getStyle("A2")->applyFromArray($styleTengah);

        $sheet->mergeCells("A4:J4");
        $sheet->setCellValue("A4", "HASIL PEMERIKSAAN UJI KENDARAAN");
        $sheet->getStyle("A4")->applyFromArray($styleFont11);
        $sheet->getStyle("A4")->applyFromArray($styleTengah);

        /*
         * ===================================
         * IDENTITAS KENDARAAN
         * ===================================
         */

        $sheet->mergeCells("A5:J5");
        $sheet->setCellValue("A5", "IDENTITAS KENDARAAN");
        $sheet->getStyle("A5")->applyFromArray($styleBold9);

        $sheet->setCellValue("A6", "a.");
        $sheet->getStyle("A6")->applyFromArray($styleTengah);
        $sheet->setCellValue("A7", "c.");
        $sheet->getStyle("A7")->applyFromArray($styleTengah);
        $sheet->setCellValue("A8", "e.");
        $sheet->getStyle("A8")->applyFromArray($styleTengah);
        $sheet->setCellValue("B6", "No Uji");
        $sheet->getStyle("B6")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B7", "No Mesin");
        $sheet->getStyle("B7")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B8", "Status");
        $sheet->getStyle("B8")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("C6:E6");
        $sheet->mergeCells("C7:E7");
        $sheet->mergeCells("C8:E8");

        $sheet->setCellValue("C6", ": " . $data->no_uji);
        $sheet->getStyle("C6")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C7", ": " . $data->no_mesin);
        $sheet->getStyle("C7")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C8", ": Datang");
        $sheet->getStyle("C8")->applyFromArray($styleTengahHorizontal);

        //===================================

        $sheet->setCellValue("F6", "b.");
        $sheet->getStyle("F6")->applyFromArray($styleTengah);
        $sheet->setCellValue("F7", "d.");
        $sheet->getStyle("F7")->applyFromArray($styleTengah);
        // $sheet->setCellValue("F8", "f.");
        $sheet->getStyle("F8")->applyFromArray($styleTengah);
        $sheet->setCellValue("G6", "No Kendaraan");
        $sheet->getStyle("G6")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G7", "No Chasis");
        $sheet->getStyle("G7")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("G8", "Tgl TD / TL");
        // $sheet->getStyle("G8")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("H6:J6");
        $sheet->mergeCells("H7:J7");
        $sheet->mergeCells("F8:J8");

        $sheet->setCellValue("H6", ": " . $data->no_kendaraan);
        $sheet->getStyle("H6")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("H7", ": " . $data->no_chasis);
        $sheet->getStyle("H7")->applyFromArray($styleTengahHorizontal);
        //        $sheet->setCellValue("H8", ": " . date("d F Y H:i:s A", strtotime($data->jdatang)));
        //        $sheet->getStyle("H8")->applyFromArray($styleTengahHorizontal);

        /*
         * ===================================
         * IDENTITAS PEMILIK
         * ===================================
         */

        $sheet->mergeCells("A9:J9");
        $sheet->setCellValue("A9", "IDENTITAS PEMILIK");
        $sheet->getStyle("A9")->applyFromArray($styleBold9);

        $sheet->setCellValue("A10", "a.");
        $sheet->getStyle("A10")->applyFromArray($styleTengah);
        $sheet->setCellValue("A11", "c.");
        $sheet->getStyle("A11")->applyFromArray($styleTengah);
        $sheet->setCellValue("B10", "No KTP");
        $sheet->getStyle("B10")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B11", "Alamat");
        $sheet->getStyle("B11")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("C10:E10");
        $sheet->mergeCells("C11:J11");

        $sheet->setCellValue("C10", ": " . $data->no_identitas);
        $sheet->getStyle("C10")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C11", ": " . $data->alamat);
        $sheet->getStyle("C11")->applyFromArray($styleTengahHorizontal);

        //===================================

        $sheet->setCellValue("F10", "b.");
        $sheet->getStyle("F10")->applyFromArray($styleTengah);
        $sheet->setCellValue("G10", "Nama");
        $sheet->getStyle("G10")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("H10:J10");

        $sheet->setCellValue("H10", ": " . $data->nama_pemilik);
        $sheet->getStyle("H10")->applyFromArray($styleTengahHorizontal);

        /*
         * ===================================
         * PENDAFTARAN
         * ===================================
         */

        $sheet->mergeCells("A12:J12");
        $sheet->setCellValue("A12", "PENDAFTARAN");
        $sheet->getStyle("A12")->applyFromArray($styleBold9);

        $sheet->setCellValue("A13", "a.");
        $sheet->getStyle("A13")->applyFromArray($styleTengah);
        $sheet->setCellValue("A14", "c.");
        $sheet->getStyle("A14")->applyFromArray($styleTengah);
        $sheet->setCellValue("A15", "e.");
        $sheet->getStyle("A15")->applyFromArray($styleTengah);
        $sheet->setCellValue("A19", "");
        $sheet->getStyle("A19")->applyFromArray($styleTengah);
        $sheet->setCellValue("B13", "Tgl Daftar");
        $sheet->getStyle("B13")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B14", "Tgl Mati Uji");
        $sheet->getStyle("B14")->applyFromArray($styleTengahHorizontal);
        $sheet->mergeCells("B15:E15");
        $sheet->setCellValue("B15", "Dikuasakan Orang");
        $sheet->getStyle("B15")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B16", "- Nomor KTP");
        $sheet->getStyle("B16")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B17", "- Nama");
        $sheet->getStyle("B17")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B18", "- Alamat");
        $sheet->getStyle("B18")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B19", "");
        $sheet->getStyle("B19")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("C13:E13");
        $sheet->mergeCells("C14:E14");
        $sheet->mergeCells("C16:E16");
        $sheet->mergeCells("C17:E17");
        $sheet->mergeCells("C18:J18");
        $sheet->mergeCells("C19:E19");

        $sheet->setCellValue("C13", ": " . date("d F Y H:i:s A", strtotime($data->jdatang)));
        $sheet->getStyle("C13")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("C14", ": " . date("d F Y", strtotime($data->tglmati)));
        $sheet->setCellValue("C14", ": ");
        $sheet->getStyle("C14")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C16", ": -");
        $sheet->getStyle("C16")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C17", ": -");
        $sheet->getStyle("C17")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C18", ": -");
        $sheet->getStyle("C18")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C19", "");
        $sheet->getStyle("C19")->applyFromArray($styleTengahHorizontal);

        //===================================

        $sheet->setCellValue("F13", "b.");
        $sheet->getStyle("F13")->applyFromArray($styleTengah);
        $sheet->setCellValue("F14", "d.");
        $sheet->getStyle("F14")->applyFromArray($styleTengah);
        $sheet->setCellValue("F15", "f.");
        $sheet->getStyle("F15")->applyFromArray($styleTengah);
        $sheet->setCellValue("F19", "g.");
        $sheet->getStyle("F19")->applyFromArray($styleTengah);
        $sheet->setCellValue("G13", "Diuji Untuk");
        $sheet->getStyle("G13")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G14", "Tgl Uji");
        $sheet->getStyle("G14")->applyFromArray($styleTengahHorizontal);
        $sheet->mergeCells("G15:J15");
        $sheet->setCellValue("G15", "Dikuasakan Badan");
        $sheet->getStyle("G15")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G16", "- Nama Badan");
        $sheet->getStyle("G16")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G17", "- Pengurus");
        $sheet->getStyle("G17")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G19", "Petugas");
        $sheet->getStyle("G19")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("H13:J13");
        $sheet->mergeCells("H14:J14");
        $sheet->mergeCells("H16:J16");
        $sheet->mergeCells("H17:J17");
        $sheet->mergeCells("H19:J19");

        // $sheet->setCellValue("H13", ": " . $data->nm_uji);
        $sheet->setCellValue("H13", ": ");
        $sheet->getStyle("H13")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("H14", ": " . date("d F Y", strtotime($data->tgl_uji)));
        $sheet->setCellValue("H14", ": ");
        $sheet->getStyle("H14")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("H16", ": -");
        $sheet->getStyle("H16")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("H17", ": -");
        $sheet->getStyle("H17")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("H19", ": " . $data->ptgs_daftar);
        $sheet->setCellValue("H19", ": ");
        $sheet->getStyle("H19")->applyFromArray($styleTengahHorizontal);

        //======================================================================

        /*
         * ===================================
         * RETRIBUSI
         * ===================================
         */

        $sheet->mergeCells("A20:J20");
        $sheet->setCellValue("A20", "RETRIBUSI");
        $sheet->getStyle("A20")->applyFromArray($styleBold9);

        $sheet->setCellValue("A21", "a.");
        $sheet->getStyle("A21")->applyFromArray($styleTengah);

        $sheet->setCellValue("B21", "- Retribusi Uji");
        $sheet->getStyle("B21")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B22", "- Denda");
        $sheet->getStyle("B22")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B23", "- Bukti Lulus Uji");
        $sheet->getStyle("B23")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B24", "  Total");
        $sheet->getStyle("B24")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("C21:E21");
        $sheet->mergeCells("C22:E22");
        $sheet->mergeCells("C23:E23");
        $sheet->mergeCells("C24:E24");

        $sheet->setCellValue("C21", ": Rp." . number_format($data->b_daftar + $data->b_pertama + $data->b_jbb + $data->b_rekom, 0));
        $sheet->getStyle("C21")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C22", ": Rp." . number_format($data->b_tlt_uji, 0));
        $sheet->getStyle("C22")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C23", ": Rp." . number_format($data->b_gnt_buku + $data->b_plat_uji + $data->b_tnd_samping, 0));
        $sheet->getStyle("C23")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C24", ": Rp." . number_format($data->b_total));
        $sheet->getStyle("C24")->applyFromArray($styleTengahHorizontal);

        //===================================

        $sheet->setCellValue("F21", "b.");
        $sheet->getStyle("F21")->applyFromArray($styleTengah);
        $sheet->setCellValue("F22", "c.");
        $sheet->getStyle("F22")->applyFromArray($styleTengah);

        $sheet->setCellValue("G21", "Nomor Kuitansi");
        $sheet->getStyle("G21")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G22", "Petugas");
        $sheet->getStyle("G22")->applyFromArray($styleTengahHorizontal);


        $sheet->mergeCells("H21:J21");
        $sheet->mergeCells("H22:J22");
        $sheet->mergeCells("G23:J23");
        $sheet->mergeCells("G24:J24");

        $sheet->setCellValue("H21", ": " . $data->numerator);
        $sheet->getStyle("H21")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("H22", ": " . $data->ptgs_daftar);
        $sheet->getStyle("H22")->applyFromArray($styleTengahHorizontal);

        //======================================================================

        /*
         * ===================================
         * KEDATANGAN
         * ===================================
         */

        $sheet->mergeCells("A25:J25");
        $sheet->setCellValue("A25", "KEDATANGAN");
        $sheet->getStyle("A25")->applyFromArray($styleBold9);

        $sheet->setCellValue("A26", "a.");
        $sheet->getStyle("A26")->applyFromArray($styleTengah);
        $sheet->setCellValue("A27", "c.");
        $sheet->getStyle("A27")->applyFromArray($styleTengah);

        $sheet->setCellValue("B26", "Tgl Datang");
        $sheet->getStyle("B26")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B27", "Nomor Antrian");
        $sheet->getStyle("B27")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("C26:E26");
        $sheet->mergeCells("C27:E27");

        $sheet->setCellValue("C26", ": " . date("d F Y", strtotime($data->jdatang)));
        $sheet->getStyle("C26")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C27", ": ");
        $sheet->getStyle("C27")->applyFromArray($styleTengahHorizontal);

        //===================================

        $sheet->setCellValue("F26", "b.");
        $sheet->getStyle("F26")->applyFromArray($styleTengah);
        $sheet->setCellValue("F27", "");
        $sheet->getStyle("F27")->applyFromArray($styleTengah);

        $sheet->setCellValue("G26", "Jam Datang");
        $sheet->getStyle("G26")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G27", "");
        $sheet->getStyle("G27")->applyFromArray($styleTengahHorizontal);


        $sheet->mergeCells("H26:J26");
        $sheet->mergeCells("H27:J27");

        // $sheet->setCellValue("H26", ": " . date("h:i:s", strtotime($data->jproses)));
        $sheet->setCellValue("H26", ": ");
        $sheet->getStyle("H26")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("H27", "");
        $sheet->getStyle("H27")->applyFromArray($styleTengahHorizontal);

        //======================================================================

        /*
         * ===================================
         * PEMERIKSAAN KENDARAAN
         * ===================================
         */

        $sheet->mergeCells("A28:J28");
        $sheet->setCellValue("A28", "PEMERIKSAAN KENDARAAN");
        $sheet->getStyle("A28")->applyFromArray($styleBold9);

        $sheet->setCellValue("A29", "1");
        $sheet->getStyle("A29")->applyFromArray($styleTengah);
        $sheet->setCellValue("A31", "2");
        $sheet->getStyle("A31")->applyFromArray($styleTengah);
        $sheet->setCellValue("A36", "3");
        $sheet->getStyle("A36")->applyFromArray($styleTengah);
        $sheet->setCellValue("A38", "4");
        $sheet->getStyle("A38")->applyFromArray($styleTengah);
        $sheet->setCellValue("A44", "5");
        $sheet->getStyle("A44")->applyFromArray($styleTengah);

        $sheet->mergeCells("B29:D29");
        $sheet->mergeCells("E29:J29");
        $sheet->mergeCells("B30:J30");

        $sheet->mergeCells("B31:D31");
        $sheet->mergeCells("E31:J31");
        $sheet->mergeCells("B32:D32");
        $sheet->mergeCells("E32:J32");
        $sheet->mergeCells("B33:D33");
        $sheet->mergeCells("E33:J33");
        $sheet->mergeCells("B34:D34");
        $sheet->mergeCells("E34:J34");
        $sheet->mergeCells("B35:J35");

        $sheet->mergeCells("B36:D36");
        $sheet->mergeCells("E36:J36");
        $sheet->mergeCells("B37:J37");

        $sheet->mergeCells("B38:D38");
        $sheet->mergeCells("E38:J38");
        $sheet->mergeCells("B39:D39");
        $sheet->mergeCells("E39:J39");
        $sheet->mergeCells("B40:D40");
        $sheet->mergeCells("E40:J40");
        $sheet->mergeCells("B41:D41");
        $sheet->mergeCells("E41:J41");
        $sheet->mergeCells("B42:D42");
        $sheet->mergeCells("E42:J42");
        $sheet->mergeCells("B43:J43");

        $sheet->mergeCells("B44:D44");
        $sheet->mergeCells("E44:J44");
        $sheet->mergeCells("B45:D45");
        $sheet->mergeCells("E45:J45");
        $sheet->mergeCells("B46:D46");
        $sheet->mergeCells("E46:J46");
        $sheet->mergeCells("B47:D47");
        $sheet->mergeCells("E47:J47");
        $sheet->mergeCells("B48:D48");
        $sheet->mergeCells("E48:J48");
        $sheet->mergeCells("B49:D49");
        $sheet->mergeCells("E49:J49");
        $sheet->mergeCells("B50:D50");
        $sheet->mergeCells("E50:J50");
        $sheet->mergeCells("B51:D51");
        $sheet->mergeCells("E51:J51");
        $sheet->mergeCells("B52:D52");
        $sheet->mergeCells("E52:J52");
        $sheet->mergeCells("B53:J53");

        $sheet->mergeCells("B54:D54");
        $sheet->mergeCells("E54:J54");
        $sheet->mergeCells("B55:D55");
        $sheet->mergeCells("E55:J55");

        $sheet->mergeCells("A56:J56");

        /**
         * PRAUJI
         */
        $sheet->setCellValue("B29", "Prauji");
        $sheet->getStyle("B29")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E29", ": " . $data->ptgs_prauji);
        $sheet->getStyle("E29")->applyFromArray($styleTengahHorizontal);

        $dataTlPrauji = VDetailTl::model()->findAllByAttributes(array('id_hasil_uji' => $id_hasil_uji, 'input_tl' => 'Prauji'));
        $keteranganTlPrauji[] = "";
        if (!empty($dataTlPrauji)) {
            foreach ($dataTlPrauji as $resultTlPrauji) {
                $keteranganTlPrauji[] = $resultTlPrauji->kelulusan;
            }
        }
        $excelTlPrauji = implode("\n", $keteranganTlPrauji);
        $sheet->setCellValue("B30", "KETERANGAN TIDAK LULUS \n $excelTlPrauji");
        $sheet->getStyle("B30")->getAlignment()->setWrapText(true);
        $sheet->getStyle("B30")->applyFromArray($styleTengahHorizontal);
        $sheet->getRowDimension(30)->setRowHeight(50);

        /**
         * EMISI
         */
        $sheet->setCellValue("B31", "Emisi Gas Buang");
        $sheet->getStyle("B31")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E31", ": " . $data->ptgs_prauji);
        $sheet->getStyle("E31")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B32", "a. Diesel");
        $sheet->getStyle("B32")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E32", ": " . $dataUji->ems_diesel . " %");
        $sheet->getStyle("E32")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B33", "b. Mesin HC");
        $sheet->getStyle("B33")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E33", ": " . $dataUji->ems_mesin_hc . " ppm");
        $sheet->getStyle("E33")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B34", "c. Mesin CO");
        $sheet->getStyle("B34")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E34", ": " . $dataUji->ems_mesin_co . " ppm");
        $sheet->getStyle("E34")->applyFromArray($styleTengahHorizontal);

        $dataTlEmisi = VDetailTl::model()->findAllByAttributes(array('id_hasil_uji' => $id_hasil_uji, 'input_tl' => 'Emisi'));
        $keteranganTlEmisi[] = "";
        if (!empty($dataTlEmisi)) {
            foreach ($dataTlEmisi as $resultTlEmisi) {
                $keteranganTlEmisi[] = $resultTlEmisi->kelulusan;
            }
        }
        $excelTlEmisi = implode("\n", $keteranganTlEmisi);
        $sheet->setCellValue("B35", "KETERANGAN TIDAK LULUS \n $excelTlEmisi");
        $sheet->getStyle("B35")->getAlignment()->setWrapText(true);
        $sheet->getStyle("B35")->applyFromArray($styleTengahHorizontal);
        $sheet->getRowDimension(35)->setRowHeight(50);

        /**
         * PITLIFT
         */
        $sheet->setCellValue("B36", "Bawah Kendaraan");
        $sheet->getStyle("B36")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E36", ": " . $data->ptgs_pitlift);
        $sheet->getStyle("E36")->applyFromArray($styleTengahHorizontal);

        $dataTlPitlift = VDetailTl::model()->findAllByAttributes(array('id_hasil_uji' => $id_hasil_uji, 'input_tl' => 'Pitlift'));
        $keteranganTlPitlift[] = "";
        if (!empty($dataTlPitlift)) {
            foreach ($dataTlPitlift as $resultTlPitlift) {
                $keteranganTlPitlift[] = $resultTlPitlift->kelulusan;
            }
        }
        $excelTlPitlift = implode("\n", $keteranganTlPitlift);
        $sheet->setCellValue("B37", "KETERANGAN TIDAK LULUS \n $excelTlPitlift");
        $sheet->getStyle("B37")->getAlignment()->setWrapText(true);
        $sheet->getStyle("B37")->applyFromArray($styleTengahHorizontal);
        $sheet->getRowDimension(37)->setRowHeight(50);

        /**
         * LAMPU
         */
        $sheet->setCellValue("B38", "Lampu Utama");
        $sheet->getStyle("B38")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E38", ": " . $data->ptgs_lampu);
        $sheet->getStyle("E38")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B39", "a. Kuat Pancar Lampu Utama Kanan");
        $sheet->getStyle("B39")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E39", ": " . $dataUji->ktlamp_kanan . " cd");
        $sheet->getStyle("E39")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B40", "b. Sudut Penyimpangan Kanan");
        $sheet->getStyle("B40")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E40", ": " . $dataUji->dev_kanan . " degree");
        $sheet->getStyle("E40")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B41", "c. Kuat Pancar Lampu Utama Kiri");
        $sheet->getStyle("B41")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E41", ": " . $dataUji->ktlamp_kiri . " cd");
        $sheet->getStyle("E41")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B42", "d. Sudut Penyimpangan Kiri");
        $sheet->getStyle("B42")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E42", ": " . $dataUji->dev_kiri . " degree");
        $sheet->getStyle("E42")->applyFromArray($styleTengahHorizontal);

        $dataTlLampu = VDetailTl::model()->findAllByAttributes(array('id_hasil_uji' => $id_hasil_uji, 'input_tl' => 'Lampu'));
        $keteranganTlLampu[] = "";
        if (!empty($dataTlLampu)) {
            foreach ($dataTlLampu as $resultTlLampu) {
                $keteranganTlLampu[] = $resultTlLampu->kelulusan;
            }
        }
        $excelTlLampu = implode("\n", $keteranganTlLampu);
        $sheet->setCellValue("B43", "KETERANGAN TIDAK LULUS \n $excelTlLampu");
        $sheet->getStyle("B43")->getAlignment()->setWrapText(true);
        $sheet->getStyle("B43")->applyFromArray($styleTengahHorizontal);
        $sheet->getRowDimension(43)->setRowHeight(50);

        /**
         * REM
         */
        $sheet->setCellValue("B44", "Brake");
        $sheet->getStyle("B44")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E44", ": " . $data->ptgs_break);
        $sheet->getStyle("E44")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B45", "a. Gaya Pengereman Sumbu I");
        $sheet->getStyle("B45")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E45", ": " . $dataUji->selrem_sb1 . " Kg");
        $sheet->getStyle("E45")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B46", "b. Selisih Gaya Pengereman Sumbu I");
        $sheet->getStyle("B46")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E46", ": " . $dataUji->selgaya . " %");
        $sheet->getStyle("E46")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B47", "c. Gaya Pengereman Sumbu II");
        $sheet->getStyle("B47")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E47", ": " . $dataUji->selrem_sb2 . " Kg");
        $sheet->getStyle("E47")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B48", "d. Selisih Gaya Pengereman Sumbu II");
        $sheet->getStyle("B48")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E48", ": " . $dataUji->selkirikanan . " %");
        $sheet->getStyle("E48")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B49", "c. Gaya Pengereman Sumbu III");
        $sheet->getStyle("B49")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E49", ": " . $dataUji->selrem_sb3 . " Kg");
        $sheet->getStyle("E49")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B50", "d. Selisih Gaya Pengereman Sumbu III");
        $sheet->getStyle("B50")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E50", ": " . $dataUji->selgaya3 . " %");
        $sheet->getStyle("E50")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B51", "c. Gaya Pengereman Sumbu IV");
        $sheet->getStyle("B51")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E51", ": " . $dataUji->selrem_sb4 . " Kg");
        $sheet->getStyle("E51")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B52", "d. Selisih Gaya Pengereman Sumbu IV");
        $sheet->getStyle("B52")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E52", ": " . $dataUji->selgaya4 . " %");
        $sheet->getStyle("E52")->applyFromArray($styleTengahHorizontal);

        $dataTlRem = VDetailTl::model()->findAllByAttributes(array('id_hasil_uji' => $id_hasil_uji, 'input_tl' => 'Rem'));
        $keteranganTlRem[] = "";
        if (!empty($dataTlRem)) {
            foreach ($dataTlRem as $resultTlRem) {
                $keteranganTlRem[] = $resultTlRem->kelulusan;
            }
        }
        $excelTlRem = implode("", $keteranganTlRem);
        $sheet->setCellValue("B53", "KETERANGAN TIDAK LULUS \n $excelTlRem");
        $sheet->getStyle("B53")->getAlignment()->setWrapText(true);
        $sheet->getStyle("B53")->applyFromArray($styleTengahHorizontal);
        $sheet->getRowDimension(53)->setRowHeight(50);

        /**
         * HASIL UJI
         */
        $sheet->setCellValue("B54", "Hasil Uji Kendaraan");
        $sheet->getStyle("B54")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E54", ": Tidak Lulus");
        $sheet->getStyle("E54")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B55", "Penandatangan Buku Uji");
        $sheet->getStyle("B55")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E55", ": " . $data->nm_penguji);
        $sheet->getStyle("E55")->applyFromArray($styleTengahHorizontal);


        //======================================================================

        /*
         * ===================================
         * PENYERAHAN
         * ===================================
         */
        $sheet->setCellValue("A56", "PENYERAHAN");
        $sheet->getStyle("A56")->applyFromArray($styleBold9);

        $sheet->setCellValue("A57", "a.");
        $sheet->getStyle("A57")->applyFromArray($styleTengah);
        $sheet->setCellValue("A58", "c.");
        $sheet->getStyle("A58")->applyFromArray($styleTengah);
        $sheet->setCellValue("A59", "e.");
        $sheet->getStyle("A69")->applyFromArray($styleTengah);

        $sheet->setCellValue("B57", "Tgl Penyerahan");
        $sheet->getStyle("B57")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B58", "Lama Proses");
        $sheet->getStyle("B58")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B59", "Tgl Mati Uji 6 Bln Kedepan");
        $sheet->getStyle("B59")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("C57:E57");
        $sheet->mergeCells("C58:E58");
        $sheet->mergeCells("C59:E59");

        $sheet->setCellValue("C57", ": " . date("d F Y", strtotime($dataUji->jselesai)));
        $sheet->getStyle("C57")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C58", ": " . $dataUji->lama . " menit");
        $sheet->getStyle("C58")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C59", ": " . date("d F Y", strtotime($dataUji->tgl_mati_uji)));
        $sheet->getStyle("C59")->applyFromArray($styleTengahHorizontal);

        //===================================

        $sheet->setCellValue("F57", "b.");
        $sheet->getStyle("F57")->applyFromArray($styleTengah);
        $sheet->setCellValue("F58", "d.");
        $sheet->getStyle("F58")->applyFromArray($styleTengah);

        $sheet->setCellValue("G57", "Jam Penyerahan");
        $sheet->getStyle("G57")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G58", "Petugas");
        $sheet->getStyle("G58")->applyFromArray($styleTengahHorizontal);


        $sheet->mergeCells("H57:J57");
        $sheet->mergeCells("H58:J58");
        $sheet->mergeCells("F59:J59");

        $sheet->setCellValue("H57", ": " . date("h:i:s", strtotime($dataUji->jselesai)));
        $sheet->getStyle("H57")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("H58", ": ");
        $sheet->getStyle("H58")->applyFromArray($styleTengahHorizontal);

        //======================================================================
        //BORDER
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );
        $sheet->getStyle("A5:J59")->applyFromArray($styleArray);
        //======================================================================

        ob_clean();
        $tgl_sekarang = date('d-m-Y');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="riwayat_pemeriksaan[' . $data->no_uji . '].xls"');
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
