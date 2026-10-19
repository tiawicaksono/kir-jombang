<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BapController extends Controller
{

    public function filters()
    {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }

    public function actionReportRiwayatPemeriksaan()
    {
        $id_hasil_uji = $_REQUEST['id_hasil_uji'];
        $data = VRiwayatHasil::model()->findByAttributes(array('id_hasil_uji' => $id_hasil_uji));

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $xls->createSheet(0);
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('Riwayat Pemeriksaan');
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        //        $sheet->getPageSetup()->setVerticalCentered(true);
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
        $sheet->setCellValue("F8", "f.");
        $sheet->getStyle("F8")->applyFromArray($styleTengah);
        $sheet->setCellValue("G6", "No Kendaraan");
        $sheet->getStyle("G6")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G7", "No Chasis");
        $sheet->getStyle("G7")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G8", "Tgl TD / TL");
        $sheet->getStyle("G8")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("H6:J6");
        $sheet->mergeCells("H7:J7");
        $sheet->mergeCells("H8:J8");

        $sheet->setCellValue("H6", ": " . $data->no_kendaraan);
        $sheet->getStyle("H6")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("H7", ": " . $data->no_chasis);
        $sheet->getStyle("H7")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("H8", ": " . date("d F Y H:i:s A", strtotime($data->jdatang)));
        $sheet->getStyle("H8")->applyFromArray($styleTengahHorizontal);

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
        // $sheet->setCellValue("A19", "g.");
        // $sheet->getStyle("A19")->applyFromArray($styleTengah);
        $sheet->setCellValue("B13", "Tgl Daftar");
        $sheet->getStyle("B13")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B14", "Tgl Mati Uji");
        $sheet->getStyle("B14")->applyFromArray($styleTengahHorizontal);
        // $sheet->mergeCells("B15:E15");
        $sheet->setCellValue("B15", "Petugas");
        $sheet->getStyle("B15")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("B16", "- Nomor KTP");
        // $sheet->getStyle("B16")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("B17", "- Nama");
        // $sheet->getStyle("B17")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("B18", "- Alamat");
        // $sheet->getStyle("B18")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("B19", "Nomor Berkas");
        // $sheet->getStyle("B19")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("C13:E13");
        $sheet->mergeCells("C14:E14");
        $sheet->mergeCells("C15:E15");
        // $sheet->mergeCells("C16:E16");
        // $sheet->mergeCells("C17:E17");
        // $sheet->mergeCells("C18:J18");
        // $sheet->mergeCells("C19:E19");

        $sheet->setCellValue("C13", ": " . date("d F Y", strtotime($data->tgl_retribusi)));
        $sheet->getStyle("C13")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C14", ": " . date("d F Y", strtotime($data->tglmati)));
        $sheet->getStyle("C14")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C15", ": " . $data->ptgs_daftar);
        // $sheet->getStyle("C16")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("C17", ": -");
        // $sheet->getStyle("C17")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("C18", ": -");
        // $sheet->getStyle("C18")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("C19", ": " . $data->no_berkas);
        // $sheet->getStyle("C19")->applyFromArray($styleTengahHorizontal);

        //===================================

        $sheet->setCellValue("F13", "b.");
        $sheet->getStyle("F13")->applyFromArray($styleTengah);
        $sheet->setCellValue("F14", "d.");
        $sheet->getStyle("F14")->applyFromArray($styleTengah);
        // $sheet->setCellValue("F15", "f.");
        // $sheet->getStyle("F15")->applyFromArray($styleTengah);
        // $sheet->setCellValue("F19", "h.");
        $sheet->getStyle("F19")->applyFromArray($styleTengah);
        $sheet->setCellValue("G13", "Diuji Untuk");
        $sheet->getStyle("G13")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G14", "Tgl Uji");
        $sheet->getStyle("G14")->applyFromArray($styleTengahHorizontal);
        // $sheet->mergeCells("G15:J15");
        // $sheet->setCellValue("G15", "Dikuasakan Badan");
        // $sheet->getStyle("G15")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("G16", "- Nama Badan");
        // $sheet->getStyle("G16")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("G17", "- Pengurus");
        // $sheet->getStyle("G17")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("G19", "Petugas");
        // $sheet->getStyle("G19")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("H13:J13");
        $sheet->mergeCells("H14:J14");
        // $sheet->mergeCells("H16:J16");
        // $sheet->mergeCells("H17:J17");
        // $sheet->mergeCells("H19:J19");

        $sheet->setCellValue("H13", ": " . $data->nm_uji);
        $sheet->getStyle("H13")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("H14", ": " . date("d F Y", strtotime($data->tgl_uji)));
        $sheet->getStyle("H14")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("H16", ": -");
        // $sheet->getStyle("H16")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("H17", ": ");
        // $sheet->getStyle("H17")->applyFromArray($styleTengahHorizontal);
        // $sheet->setCellValue("H19", ": " . $data->ptgs_daftar);
        // $sheet->getStyle("H19")->applyFromArray($styleTengahHorizontal);

        $sheet->getRowDimension(16)->setVisible(false);
        $sheet->getRowDimension(17)->setVisible(false);
        $sheet->getRowDimension(18)->setVisible(false);
        $sheet->getRowDimension(19)->setVisible(false);
        $sheet->getRowDimension(20)->setVisible(false);
        $sheet->getRowDimension(21)->setVisible(false);
        $sheet->getRowDimension(22)->setVisible(false);
        $sheet->getRowDimension(23)->setVisible(false);
        $sheet->getRowDimension(24)->setVisible(false);
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
        $sheet->setCellValue("B23", "- Buku Uji");
        $sheet->getStyle("B23")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B24", "  Total");
        $sheet->getStyle("B24")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("C21:E21");
        $sheet->mergeCells("C22:E22");
        $sheet->mergeCells("C23:E23");
        $sheet->mergeCells("C24:E24");

        $sheet->setCellValue("C21", ": Rp." . number_format($data->b_berkala, 0));
        $sheet->getStyle("C21")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C22", ": Rp." . number_format($data->b_tlt_uji, 0));
        $sheet->getStyle("C22")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C23", ": Rp." . number_format($data->b_buku, 0));
        $sheet->getStyle("C23")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C24", ": Rp." . number_format($data->b_total));
        // $sheet->setCellValue("C24", ": Rp.");
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
        $sheet->setCellValue("C27", ": " . $data->no_antrian);
        $sheet->getStyle("C27")->applyFromArray($styleTengahHorizontal);

        //===================================

        $sheet->setCellValue("F26", "b.");
        $sheet->getStyle("F26")->applyFromArray($styleTengah);
        $sheet->setCellValue("F27", "d.");
        $sheet->getStyle("F27")->applyFromArray($styleTengah);

        $sheet->setCellValue("G26", "Jam Datang");
        $sheet->getStyle("G26")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G27", "Petugas");
        $sheet->getStyle("G27")->applyFromArray($styleTengahHorizontal);


        $sheet->mergeCells("H26:J26");
        $sheet->mergeCells("H27:J27");

        $sheet->setCellValue("H26", ": " . date("h:i:s", strtotime($data->jdatang)));
        $sheet->getStyle("H26")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("H27", ": " . $data->ptgs_datang);
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
        $sheet->setCellValue("A36", "2");
        $sheet->getStyle("A36")->applyFromArray($styleTengah);
        $sheet->setCellValue("A40", "3");
        $sheet->getStyle("A40")->applyFromArray($styleTengah);
        $sheet->setCellValue("A50", "4");
        $sheet->getStyle("A50")->applyFromArray($styleTengah);
        $sheet->setCellValue("A55", "5");
        $sheet->getStyle("A55")->applyFromArray($styleTengah);

        $sheet->mergeCells("B29:D29");
        $sheet->mergeCells("B30:D30");
        $sheet->mergeCells("B31:D31");
        $sheet->mergeCells("B32:D32");
        $sheet->mergeCells("B33:D33");
        $sheet->mergeCells("B34:D34");
        $sheet->mergeCells("B35:D35");
        $sheet->mergeCells("B36:D36");
        $sheet->mergeCells("B37:D37");
        $sheet->mergeCells("B38:D38");
        $sheet->mergeCells("B39:D39");
        $sheet->mergeCells("B40:D40");
        $sheet->mergeCells("B41:D41");
        $sheet->mergeCells("B42:D42");
        $sheet->mergeCells("B43:D43");
        $sheet->mergeCells("B44:D44");
        $sheet->mergeCells("B45:D45");
        $sheet->mergeCells("B46:D46");
        $sheet->mergeCells("B47:D47");
        $sheet->mergeCells("B48:D48");
        $sheet->mergeCells("B49:D49");
        $sheet->mergeCells("B50:D50");
        $sheet->mergeCells("B51:D51");
        $sheet->mergeCells("B52:D52");
        $sheet->mergeCells("B53:D53");
        $sheet->mergeCells("B54:D54");
        $sheet->mergeCells("B55:D55");
        $sheet->mergeCells("B56:D56");
        $sheet->mergeCells("B57:D57");
        $sheet->mergeCells("B58:D58");
        $sheet->mergeCells("B59:D59");
        $sheet->mergeCells("B60:D60");
        $sheet->mergeCells("B61:D61");
        $sheet->mergeCells("E29:J29");
        $sheet->mergeCells("E30:J30");
        $sheet->mergeCells("E31:J31");
        $sheet->mergeCells("E32:J32");
        $sheet->mergeCells("E33:J33");
        $sheet->mergeCells("E34:J34");
        $sheet->mergeCells("E35:J35");
        $sheet->mergeCells("E36:J36");
        $sheet->mergeCells("E37:J37");
        $sheet->mergeCells("E38:J38");
        $sheet->mergeCells("E39:J39");
        $sheet->mergeCells("E40:J40");
        $sheet->mergeCells("E41:J41");
        $sheet->mergeCells("E42:J42");
        $sheet->mergeCells("E43:J43");
        $sheet->mergeCells("E44:J44");
        $sheet->mergeCells("E45:J45");
        $sheet->mergeCells("E46:J46");
        $sheet->mergeCells("E47:J47");
        $sheet->mergeCells("E48:J48");
        $sheet->mergeCells("E49:J49");
        $sheet->mergeCells("E50:J50");
        $sheet->mergeCells("E51:J51");
        $sheet->mergeCells("E52:J52");
        $sheet->mergeCells("E53:J53");
        $sheet->mergeCells("E54:J54");
        $sheet->mergeCells("E55:J55");
        $sheet->mergeCells("E56:J56");
        $sheet->mergeCells("E57:J57");
        $sheet->mergeCells("E58:J58");
        $sheet->mergeCells("E59:J59");
        $sheet->mergeCells("E60:J60");
        $sheet->mergeCells("E61:J61");

        $sheet->setCellValue("B29", "Prauji");
        $sheet->getStyle("B29")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E29", ": " . $data->ptgs_prauji);
        $sheet->getStyle("E29")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B30", "a. Identitas Kendaraan");
        $sheet->getStyle("B30")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E30", ": Lulus");
        $sheet->getStyle("E30")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B31", "b. Sistem Penerangan");
        $sheet->getStyle("B31")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E31", ": Lulus");
        $sheet->getStyle("E31")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B32", "c. Rumah dan Body");
        $sheet->getStyle("B32")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E32", ": Lulus");
        $sheet->getStyle("E32")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B33", "d. Roda-roda");
        $sheet->getStyle("B33")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E33", ": Lulus");
        $sheet->getStyle("E33")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B34", "e. Dimensi");
        $sheet->getStyle("B34")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E34", ": Lulus");
        $sheet->getStyle("E34")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B35", "f. Peralatan dan Perlengkapan");
        $sheet->getStyle("B35")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E35", ": Lulus");
        $sheet->getStyle("E35")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B36", "Emisi Gas Buang");
        $sheet->getStyle("B36")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E36", ": " . $data->ptgs_prauji);
        $sheet->getStyle("E36")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B37", "a. Diesel");
        $sheet->getStyle("B37")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E37", ": " . $data->ems_diesel . " %");
        $sheet->getStyle("E37")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B38", "b. Mesin HC");
        $sheet->getStyle("B38")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E38", ": " . $data->ems_mesin_hc . " ppm");
        $sheet->getStyle("E38")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B39", "c. Mesin CO");
        $sheet->getStyle("B39")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E39", ": " . $data->ems_mesin_co . " ppm");
        $sheet->getStyle("E39")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B40", "Bawah Kendaraan");
        $sheet->getStyle("B40")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E40", ": " . $data->ptgs_pitlift);
        $sheet->getStyle("E40")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B41", "a. Rangka dan Landasan");
        $sheet->getStyle("B41")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E41", ": Lulus");
        $sheet->getStyle("E41")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B42", "b. Sistem Kemudi");
        $sheet->getStyle("B42")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E42", ": Lulus");
        $sheet->getStyle("E42")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B43", "c. Sistem Suspensi");
        $sheet->getStyle("B43")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E43", ": Lulus");
        $sheet->getStyle("E43")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B44", "d. Sistem Rem");
        $sheet->getStyle("B44")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E44", ": Lulus");
        $sheet->getStyle("E44")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B45", "e. Engine");
        $sheet->getStyle("B45")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E45", ": Lulus");
        $sheet->getStyle("E45")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B46", "f. Sistem Penerus Daya");
        $sheet->getStyle("B46")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E46", ": Lulus");
        $sheet->getStyle("E46")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B47", "g. Sistem Pembuangan");
        $sheet->getStyle("B47")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E47", ": Lulus");
        $sheet->getStyle("E47")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B48", "h. Sistem Bahan Bakar");
        $sheet->getStyle("B48")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E48", ": Lulus");
        $sheet->getStyle("E48")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B49", "i. Sistem Pendinginan");
        $sheet->getStyle("B49")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E49", ": Lulus");
        $sheet->getStyle("E49")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B50", "Lampu Utama");
        $sheet->getStyle("B50")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E50", ": " . $data->ptgs_lampu);
        $sheet->getStyle("E50")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B51", "a. Kuat Pancar Lampu Utama Kanan");
        $sheet->getStyle("B51")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E51", ": " . $data->ktlamp_kanan . " cd");
        $sheet->getStyle("E51")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B52", "b. Sudut Penyimpangan Kanan");
        $sheet->getStyle("B52")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E52", ": " . $data->dev_kanan . " degree");
        $sheet->getStyle("E52")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B53", "c. Kuat Pancar Lampu Utama Kiri");
        $sheet->getStyle("B53")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E53", ": " . $data->ktlamp_kiri . " cd");
        $sheet->getStyle("E53")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B54", "d. Sudut Penyimpangan Kiri");
        $sheet->getStyle("B54")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E54", ": " . $data->dev_kiri . " degree");
        $sheet->getStyle("E54")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B55", "Brake");
        $sheet->getStyle("B55")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E55", ": " . $data->ptgs_break);
        $sheet->getStyle("E55")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B56", "a. Gaya Pengereman Sumbu I");
        $sheet->getStyle("B56")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E56", ": " . $data->selgaya1 . " %");
        $sheet->getStyle("E56")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B57", "b. Selisih Gaya Pengereman Sumbu I");
        $sheet->getStyle("B57")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E57", ": " . $data->selrem_sb1 . " Kg");
        $sheet->getStyle("E57")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B58", "c. Gaya Pengereman Sumbu II");
        $sheet->getStyle("B58")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E58", ": " . $data->selgaya2 . " %");
        $sheet->getStyle("E58")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B59", "d. Selisih Gaya Pengereman Sumbu II");
        $sheet->getStyle("B59")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E59", ": " . $data->selrem_sb2 . " Kg");
        $sheet->getStyle("E59")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B60", "c. Gaya Pengereman Sumbu III");
        $sheet->getStyle("B60")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E60", ": " . $data->selgaya3 . " %");
        $sheet->getStyle("E60")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B61", "d. Selisih Gaya Pengereman Sumbu III");
        $sheet->getStyle("B61")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E61", ": " . $data->selrem_sb3 . " Kg");
        $sheet->getStyle("E61")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B62", "c. Gaya Pengereman Sumbu IV");
        $sheet->getStyle("B62")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E62", ": " . $data->selgaya4 . " %");
        $sheet->getStyle("E62")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B63", "d. Selisih Gaya Pengereman Sumbu IV");
        $sheet->getStyle("B63")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E63", ": " . $data->selrem_sb4 . " Kg");
        $sheet->getStyle("E63")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B64", "Hasil Uji Kendaraan");
        $sheet->getStyle("B64")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E64", ": " . $data->lulus == true ? 'Lulus' : 'Tidak Lulus');
        $sheet->getStyle("E64")->applyFromArray($styleTengahHorizontal);

        $sheet->setCellValue("B65", "Penandatangan Buku Uji");
        $sheet->getStyle("B65")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("E65", ": " . $data->nm_penguji);
        $sheet->getStyle("E65")->applyFromArray($styleTengahHorizontal);

        //======================================================================

        /*
         * ===================================
         * PENYERAHAN
         * ===================================
         */

        $sheet->mergeCells("A66:J66");
        $sheet->setCellValue("A66", "PENYERAHAN");
        $sheet->getStyle("A66")->applyFromArray($styleBold9);

        $sheet->setCellValue("A67", "a.");
        $sheet->getStyle("A67")->applyFromArray($styleTengah);
        $sheet->setCellValue("A68", "c.");
        $sheet->getStyle("A68")->applyFromArray($styleTengah);

        $sheet->setCellValue("B67", "Tgl Penyerahan");
        $sheet->getStyle("B67")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("B68", "Lama Proses");
        $sheet->getStyle("B68")->applyFromArray($styleTengahHorizontal);

        $sheet->mergeCells("C67:E67");
        $sheet->mergeCells("C68:E68");

        $sheet->setCellValue("C67", ": " . date("d F Y", strtotime($data->jselesai)));
        $sheet->getStyle("C67")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("C68", ": " . $data->lama . " menit");
        $sheet->getStyle("C68")->applyFromArray($styleTengahHorizontal);

        //===================================

        $sheet->setCellValue("F67", "b.");
        $sheet->getStyle("F67")->applyFromArray($styleTengah);
        $sheet->setCellValue("F68", "d.");
        $sheet->getStyle("F68")->applyFromArray($styleTengah);

        $sheet->setCellValue("G67", "Jam Penyerahan");
        $sheet->getStyle("G67")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("G68", "Petugas");
        $sheet->getStyle("G68")->applyFromArray($styleTengahHorizontal);


        $sheet->mergeCells("H67:J67");
        $sheet->mergeCells("H68:J68");

        $sheet->setCellValue("H67", ": " . date("h:i:s", strtotime($data->jselesai)));
        $sheet->getStyle("H67")->applyFromArray($styleTengahHorizontal);
        $sheet->setCellValue("H68", ": " . $data->ptgs_print_hasil);
        $sheet->getStyle("H68")->applyFromArray($styleTengahHorizontal);

        //======================================================================
        //BORDER
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );
        $sheet->getStyle("A5:J68")->applyFromArray($styleArray);
        //======================================================================

        ob_clean();
        $tgl_sekarang = date('d-m-Y');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="riwayat_pemeriksaan[' . $data->no_kendaraan . '].xls"');
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
