<?php

class KendaraanKecamatanController extends Controller
{

    public function filters()
    {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }

    public function actionIndex()
    {
        $this->pageTitle = 'Uji Kendaraan';
        $this->render('index');
    }

    public function actionSelectKecamatan()
    {
        $kecamatan = $_POST['kecamatan'];
        $criteria = new CDbCriteria();
        $criteria->addCondition("id_kecamatan = '$kecamatan'");
        $criteria->order = 'nama asc';
        $kelurahan = MKelurahan::model()->findAll($criteria);
        $option = "<option value=''>ALL</pilih>";
        foreach ($kelurahan as $data) :
            $option .= "<option value='$data->id_kelurahan'>$data->nama</pilih>";
        endforeach;

        echo $option;
    }

    public function actionRekap()
    {
        $kecamatan = $_REQUEST['kecamatan'];
        $kelurahan = $_REQUEST['kelurahan'];
        $namaKecamatan = "SEMUA";
        $namaKelurahan = "SEMUA";
        if (!empty($kecamatan)) {
            $namaKecamatan = MKecamatan::model()->findByAttributes(array('id_kecamatan' => $kecamatan))->nama;
        }

        if (!empty($kelurahan)) {
            $namaKelurahan = MKelurahan::model()->findByAttributes(array('id_kelurahan' => $kelurahan))->nama;
        }

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $xls->getActiveSheet();
        $xls->setActiveSheetIndex(0);
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(true);
        $sheet->getPageSetup()->setScale(90);
        $sheet->getRowDimension(5)->setRowHeight(40);
        //======================================================================
        //HEADER
        $sheet->mergeCells("A1:J1");
        $sheet->setCellValue("A1", "DINAS PERHUBUNGAN KABUPATEN SAMPANG");
        $sheet->getStyle("A1")->getFont()->setSize(16);
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->mergeCells("A2:J2");
        $sheet->setCellValue("A2", "REKAPITULASI DATA KENDARAAN PER KECAMATAN & PER KELURAHAN");
        $sheet->getStyle("A2")->getFont()->setSize(16);
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->mergeCells("A3:J3");
        $sheet->setCellValue("A3", "KECAMATAN : " . $namaKecamatan . " / KELURAHAN : " . $namaKelurahan);
        $sheet->getStyle("A3")->getFont()->setSize(12);
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("A5", "No.");
        $sheet->getStyle("A5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("A")->setAutoSize(true);

        $sheet->getStyle("B5")->getAlignment()->setWrapText(true);
        $sheet->setCellValue("B5", "Nomor Kendaraan");
        $sheet->getStyle("B5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("B")->setAutoSize(true);

        $sheet->getStyle("C5")->getAlignment()->setWrapText(true);
        $sheet->setCellValue("C5", "Nomor Uji");
        $sheet->getStyle("C5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("C")->setAutoSize(true);

        $sheet->getStyle("D")->getAlignment()->setWrapText(true);
        $sheet->setCellValue("D5", "Nama Pemilik");
        $sheet->getStyle("D5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("D")->setWidth(25);

        $sheet->setCellValue("E5", "Alamat");
        $sheet->getStyle("E5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension("E")->setWidth(30);

        $sheet->setCellValue("F5", "Komersil / Jenis");
        $sheet->getStyle("F5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("F")->setWidth(18);

        $sheet->setCellValue("G5", "Merk");
        $sheet->getStyle("G5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("G")->setAutoSize(true);

        $sheet->setCellValue("H5", "Sifat");
        $sheet->getStyle("H5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("H")->setAutoSize(true);

        $sheet->setCellValue("I5", "Bahan Bakar");
        $sheet->getStyle("I5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("I5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("I")->setAutoSize(true);

        $sheet->setCellValue("J5", "Tanggal Mati Uji");
        $sheet->getStyle("J5")->getAlignment()->setWrapText(true);
        $sheet->getStyle("J5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("J5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("J")->setAutoSize(true);

        $sheet->setCellValue("K5", "NO. HP");
        $sheet->getStyle("K5")->getAlignment()->setWrapText(true);
        $sheet->getStyle("K5")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("K5")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("K")->setAutoSize(true);

        $sheet->getStyle('A5:K5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================
        $criteria = new CDbCriteria();
        $criteria->select = 'no_uji,no_kendaraan,nama_pemilik,alamat,kecamatan,kelurahan,nm_komersil,jenis,merk,sifat,bahan_bakar,tgl_mati_uji,no_telp';
        if (!empty($kecamatan)) {
            if (!empty($kelurahan)) {
                $criteria->addCondition("kecamatan = '$namaKecamatan'");
                $criteria->addCondition("kelurahan = '$namaKelurahan'");
            } else {
                $criteria->addCondition("kecamatan = '$namaKecamatan'");
            }
        } else {
            $criteria->addCondition("kota ilike 'KABUPATEN SAMPANG'");
        }
        $result = VKendaraan::model()->findAll($criteria);
        //======================================================================
        //BODY
        $no = 1;
        $baris = 6;
        foreach ($result as $data) :
            $tgl_mati_uji = date("d", strtotime($data->tgl_mati_uji));
            $bulan_mati_uji = date("n", strtotime($data->tgl_mati_uji));
            $thn_mati_uji = date("Y", strtotime($data->tgl_mati_uji));
            $bln_mati_uji = Yii::app()->params['bulanArrayInd'][$bulan_mati_uji - 1];
            $jenis = $data->nm_komersil . PHP_EOL . $data->jenis;
            $sheet->setCellValue("A" . $baris, $no);
            $sheet->setCellValue("B" . $baris, $data->no_kendaraan);
            $sheet->setCellValue("C" . $baris, $data->no_uji);
            $sheet->setCellValue("D" . $baris, $data->nama_pemilik);
            $sheet->setCellValue("E" . $baris, $data->alamat);
            $sheet->setCellValue("F" . $baris, $jenis);
            $sheet->getStyle("F$baris")->getAlignment()->setWrapText(true);
            $sheet->setCellValue("G" . $baris, $data->merk);
            $sheet->setCellValue("H" . $baris, $data->sifat);
            $sheet->setCellValue("I" . $baris, $data->bahan_bakar);
            $sheet->setCellValue("J" . $baris, $tgl_mati_uji . " " . $bln_mati_uji . " " . $thn_mati_uji);
            if (strtotime(date('Y-m-d')) > strtotime($data->tgl_mati_uji)) {
                $sheet->getStyle('A' . $baris . ':J' . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('DEA8A8');
            }
            $sheet->setCellValue("K" . $baris, '`' . $data->no_telp);
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
        $baris = intval($baris) - 1;
        // $sheet->getStyle('K6:M'.$baris)->getNumberFormat()->setFormatCode('Custom');
        $sheet->getStyle("A5:K" . $baris)->applyFromArray($styleArray);
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data Kendaraan Per Kecamatan.xls"');
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
