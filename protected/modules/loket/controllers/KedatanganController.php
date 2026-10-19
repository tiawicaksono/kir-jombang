<?php

class KedatanganController extends Controller {

    public function filters() {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }

    public function actionIndex() {
        $this->pageTitle = 'KEDATANGAN';
        $this->render('kedatangan');
    }

    public function actionProsesDatang() {
        $id = explode('-', $_POST['id']);
        $id_daftar = $id[0];
        $id_uji = $id[1];
        $id_kendaraan = $id[2];
        $id_retribusi = $id[3];
        $petugas = Yii::app()->session['username'];

        $criteria = new CDbCriteria();
        $criteria->addCondition("id_kendaraan = $id_kendaraan");
        $criteria->order = 'id_hasil_uji DESC';
        $dataHasilUji = TblHasilUji::model()->find($criteria);
        
        $insert = "Select insert_hasil(" . $id_daftar . "," . $id_uji . "," . $id_kendaraan . ",'Auto','0')";
        Yii::app()->db->createCommand($insert)->execute();
        $proses = "UPDATE tbl_proses set ptgs_datang='" . $petugas . "',id_daftar=" . $id_daftar . " where id_retribusi=" . $id_retribusi;
        Yii::app()->db->createCommand($proses)->query();
        
    }

    public function actionGetListDataDatang() {
//        $selectCategory = $_POST['selectCategory'];
        $textCategory = strtoupper($_POST['textCategory']);
        $selectDate = strtoupper($_POST['selectDate']);
        $datang = $_POST['chooseProses'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id_daftar';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;
        $criteria->addCondition("datang = $datang");
        $criteria->addCondition("tgl_uji = TO_DATE('" . $selectDate . "', 'DD-Mon-YY')");
        if (!empty($textCategory)) {
            $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('" . $textCategory . "'),' ',''))");
        }
        $result = VDatang::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $p) {
            $id = $p->id_daftar . "-" . $p->id_uji . "-" . $p->id_kendaraan . "-" . $p->id_retribusi;
            $dataJson[] = array(
                "id_campuran" => $id,
                "id" => $id,
                "numerator" => $p->numerator,
                "no_uji" => $p->no_uji,
                "no_kendaraan" => $p->no_kendaraan,
                "nama_pemilik" => $p->nama_pemilik,
                "jns_kend" => $p->jns_kend,
                "nm_uji" => $p->nm_uji,
            );
        }

        header('Content-Type: application/json');
        echo CJSON::encode(
                array(
                    'total' => VDatang::model()->count($criteria),
                    'rows' => $dataJson,
                )
        );
        Yii::app()->end();
    }

    public function actionReportKedatanganExcel() {
        $selectDate = strtoupper($_GET['tgl']);

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $xls->getActiveSheet();
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(false);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getPageSetup()->setVerticalCentered(true);
        $sheet->getPageSetup()->setScale(90);
        //======================================================================
        $styleTengah = array(
            'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        $styleTengahHorizontal = array(
            'alignment' => array(
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        ));
        //======================================================================
        // DATA KENDARAAN YANG DATANG UJI 
        //HEADER
        $xls->createSheet(0);
        $xls->setActiveSheetIndex(0);
        $sheet = $xls->getActiveSheet();
        $sheet->setTitle('DATANG');

        $sheet->mergeCells("A1:E1");
        $sheet->setCellValue("A1", "LAPORAN KENDARAAN DATANG UJI");
        $sheet->getStyle("A1")->getFont()->setSize(20);
        $sheet->getStyle("A1")->applyFromArray($styleTengah);

        $sheet->mergeCells("A2:E2");
        $sheet->setCellValue("A2", "UPTD PKB WIYUNG DISHUB SURABAYA");
        $sheet->getStyle("A2")->getFont()->setSize(20);
        $sheet->getStyle("A2")->applyFromArray($styleTengah);

        $sheet->mergeCells("A3:E3");
        $sheet->setCellValue("A3", date("d F Y", strtotime($selectDate)));
        $sheet->getStyle("A3")->getFont()->setSize(14);
        $sheet->getStyle("A3")->applyFromArray($styleTengah);

        $sheet->setCellValue("A5", "NO");
        $sheet->getStyle("A5")->applyFromArray($styleTengah);
        $sheet->getStyle("A")->applyFromArray($styleTengah);
        $sheet->getColumnDimension('A')->setAutoSize(true);

        $sheet->setCellValue("B5", "NO UJI");
        $sheet->getStyle("B5")->applyFromArray($styleTengah);
        $sheet->getStyle("B")->applyFromArray($styleTengahHorizontal);
        $sheet->getStyle("B")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('B')->setWidth(14);

        $sheet->setCellValue("C5", "NO KENDARAAN");
        $sheet->getStyle("C5")->applyFromArray($styleTengah);
        $sheet->getStyle("C")->applyFromArray($styleTengahHorizontal);
        $sheet->getStyle("C5")->getAlignment()->setWrapText(true);
        $sheet->getStyle("C")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('C')->setWidth(12);

        $sheet->setCellValue("D5", "NAMA PEMILIK");
        $sheet->getStyle("D5")->applyFromArray($styleTengah);
        $sheet->getStyle("D")->applyFromArray($styleTengahHorizontal);
        $sheet->getStyle("D")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('D')->setWidth(30);

        $sheet->setCellValue("E5", "JENIS KENDARAAN");
        $sheet->getStyle("E5")->applyFromArray($styleTengah);
        $sheet->getStyle("E")->applyFromArray($styleTengah);
        $sheet->getStyle("E5")->getAlignment()->setWrapText(true);
        $sheet->getStyle("E")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('E')->setWidth(15);

        $sheet->getStyle('A5:E5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================

        $criteria = new CDbCriteria();
        $criteria->addCondition("datang = true");
        $criteria->addCondition("tgl_uji = TO_DATE('" . $selectDate . "', 'DD-Mon-YY')");
        $result = VDatang::model()->findAll($criteria);
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
//            $sheet->getRowDimension($baris)->setRowHeight(20);
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
        $sheet->getStyle("A5:E" . $baris_border)->applyFromArray($styleArray);
        //======================================================================
        //FOOTER
        $kepala = $baris + 1;
        $sheet->mergeCells("E" . $kepala . ":G" . $kepala);
        $sheet->setCellValue("E" . $kepala, "KEPALA UPTD PKB WIYUNG");
        $sheet->getStyle("E" . $kepala)->applyFromArray($styleTengah);

        $nama = $kepala + 5;
        $sheet->mergeCells("E" . $nama . ":G" . $nama);
        $sheet->setCellValue("E" . $nama, "Abdul Manab, SH.");
        $sheet->getStyle("E" . $nama)->applyFromArray($styleTengah);

        $penata = $nama + 1;
        $sheet->mergeCells("E" . $penata . ":G" . $penata);
        $sheet->setCellValue("E" . $penata, "Penata");
        $sheet->getStyle("E" . $penata)->applyFromArray($styleTengah);

        $nip = $penata + 1;
        $sheet->mergeCells("E" . $nip . ":G" . $nip);
        $sheet->setCellValue("E" . $nip, "NIP. 19630402 198910 1 003");
        $sheet->getStyle("E" . $nip)->applyFromArray($styleTengah);

//        $sheet->getHeaderFooter()->setOddFooter('&R&F Page &P / &N');
//        $sheet->getHeaderFooter()->setEvenFooter('&R&F Page &P / &N');
        $sheet->getHeaderFooter()->setOddFooter('&R Page &P / &N');
        $sheet->getHeaderFooter()->setEvenFooter('&R Page &P / &N');
        //END FOOTER
        //======================================================================
        //======================================================================
        // DATA KENDARAAN YANG TIDAK DATANG UJI 
        //HEADER
        $xls->createSheet(1);
        $xls->setActiveSheetIndex(1);
        $sheetTidakDatang = $xls->getActiveSheet();
        $sheetTidakDatang->setTitle('TIDAK DATANG');

        $sheetTidakDatang->mergeCells("A1:E1");
        $sheetTidakDatang->setCellValue("A1", "LAPORAN KENDARAAN TIDAK DATANG UJI");
        $sheetTidakDatang->getStyle("A1")->getFont()->setSize(20);
        $sheetTidakDatang->getStyle("A1")->applyFromArray($styleTengah);

        $sheetTidakDatang->mergeCells("A2:E2");
        $sheetTidakDatang->setCellValue("A2", "UPTD PKB WIYUNG DISHUB SURABAYA");
        $sheetTidakDatang->getStyle("A2")->getFont()->setSize(20);
        $sheetTidakDatang->getStyle("A2")->applyFromArray($styleTengah);

        $sheetTidakDatang->mergeCells("A3:E3");
        $sheetTidakDatang->setCellValue("A3", date("d F Y", strtotime($selectDate)));
        $sheetTidakDatang->getStyle("A3")->getFont()->setSize(14);
        $sheetTidakDatang->getStyle("A3")->applyFromArray($styleTengah);

        $sheetTidakDatang->setCellValue("A5", "NO");
        $sheetTidakDatang->getStyle("A5")->applyFromArray($styleTengah);
        $sheetTidakDatang->getStyle("A")->applyFromArray($styleTengah);
        $sheetTidakDatang->getColumnDimension('A')->setAutoSize(true);

        $sheetTidakDatang->setCellValue("B5", "NO UJI");
        $sheetTidakDatang->getStyle("B5")->applyFromArray($styleTengah);
        $sheetTidakDatang->getStyle("B")->applyFromArray($styleTengahHorizontal);
        $sheetTidakDatang->getStyle("B")->getAlignment()->setWrapText(true);
        $sheetTidakDatang->getColumnDimension('B')->setWidth(14);

        $sheetTidakDatang->setCellValue("C5", "NO KENDARAAN");
        $sheetTidakDatang->getStyle("C5")->applyFromArray($styleTengah);
        $sheetTidakDatang->getStyle("C")->applyFromArray($styleTengahHorizontal);
        $sheetTidakDatang->getStyle("C5")->getAlignment()->setWrapText(true);
        $sheetTidakDatang->getStyle("C")->getAlignment()->setWrapText(true);
        $sheetTidakDatang->getColumnDimension('C')->setWidth(12);

        $sheetTidakDatang->setCellValue("D5", "NAMA PEMILIK");
        $sheetTidakDatang->getStyle("D5")->applyFromArray($styleTengah);
        $sheetTidakDatang->getStyle("D")->applyFromArray($styleTengahHorizontal);
        $sheetTidakDatang->getStyle("D")->getAlignment()->setWrapText(true);
        $sheetTidakDatang->getColumnDimension('D')->setWidth(30);

        $sheetTidakDatang->setCellValue("E5", "JENIS KENDARAAN");
        $sheetTidakDatang->getStyle("E5")->applyFromArray($styleTengah);
        $sheetTidakDatang->getStyle("E")->applyFromArray($styleTengah);
        $sheetTidakDatang->getStyle("E5")->getAlignment()->setWrapText(true);
        $sheetTidakDatang->getStyle("E")->getAlignment()->setWrapText(true);
        $sheetTidakDatang->getColumnDimension('E')->setWidth(15);

        $sheetTidakDatang->getStyle('A5:E5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================

        $criteria = new CDbCriteria();
        $criteria->addCondition("datang = false");
        $criteria->addCondition("tgl_uji = TO_DATE('" . $selectDate . "', 'DD-Mon-YY')");
        $result = VDatang::model()->findAll($criteria);
        //======================================================================
        //BODY
        $no = 1;
        $baris = 6;
        foreach ($result as $data):
            $sheetTidakDatang->setCellValue("A" . $baris, $no);
            $sheetTidakDatang->setCellValue("B" . $baris, $data->no_uji);
            $sheetTidakDatang->setCellValue("C" . $baris, $data->no_kendaraan);
            $sheetTidakDatang->setCellValue("D" . $baris, $data->nama_pemilik);
            $sheetTidakDatang->setCellValue("E" . $baris, $data->jns_kend);
//            $sheetTidakDatang->getRowDimension($baris)->setRowHeight(20);
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
        $sheetTidakDatang->getStyle("A5:E" . $baris_border)->applyFromArray($styleArray);
        //======================================================================
        //FOOTER
        $kepala = $baris + 1;
        $sheetTidakDatang->mergeCells("E" . $kepala . ":G" . $kepala);
        $sheetTidakDatang->setCellValue("E" . $kepala, "KEPALA UPTD PKB WIYUNG");
        $sheetTidakDatang->getStyle("E" . $kepala)->applyFromArray($styleTengah);

        $nama = $kepala + 5;
        $sheetTidakDatang->mergeCells("E" . $nama . ":G" . $nama);
        $sheetTidakDatang->setCellValue("E" . $nama, "Abdul Manab, SH.");
        $sheetTidakDatang->getStyle("E" . $nama)->applyFromArray($styleTengah);

        $penata = $nama + 1;
        $sheetTidakDatang->mergeCells("E" . $penata . ":G" . $penata);
        $sheetTidakDatang->setCellValue("E" . $penata, "Penata");
        $sheetTidakDatang->getStyle("E" . $penata)->applyFromArray($styleTengah);

        $nip = $penata + 1;
        $sheetTidakDatang->mergeCells("E" . $nip . ":G" . $nip);
        $sheetTidakDatang->setCellValue("E" . $nip, "NIP. 19630402 198910 1 003");
        $sheetTidakDatang->getStyle("E" . $nip)->applyFromArray($styleTengah);

//        $sheetTidakDatang->getHeaderFooter()->setOddFooter('&R&F Page &P / &N');
//        $sheetTidakDatang->getHeaderFooter()->setEvenFooter('&R&F Page &P / &N');
        $sheetTidakDatang->getHeaderFooter()->setOddFooter('&R Page &P / &N');
        $sheetTidakDatang->getHeaderFooter()->setEvenFooter('&R Page &P / &N');
        //END FOOTER
        //======================================================================
        ob_clean();
        $tgl_sekarang = date('d-m-Y');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Kedatangan[' . $tgl_sekarang . '].xlsx"');
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

    public function actionProsesDatangChecked() {
        $idArray = $_POST['idArray'];
        $petugas = Yii::app()->session['username'];
        foreach ($idArray as $key => $dataArray):
            $id = explode('-', $dataArray);
            $id_daftar = $id[0];
            $id_uji = $id[1];
            $id_kendaraan = $id[2];
            $id_retribusi = $id[3];
            
            $insert = "Select insert_hasil(" . $id_daftar . "," . $id_uji . "," . $id_kendaraan . ",'Auto','0')";
            Yii::app()->db->createCommand($insert)->execute();
            $proses = "UPDATE tbl_proses set ptgs_datang='" . $petugas . "',id_daftar=" . $id_daftar . " where id_retribusi=" . $id_retribusi;
            Yii::app()->db->createCommand($proses)->execute();
        endforeach;
    }

    public function actionProsesBelumDatang() {
        $id = explode('-', $_POST['id']);
        $id_daftar = $id[0];
        $id_uji = $id[1];
        $id_kendaraan = $id[2];
        $id_retribusi = $id[3];
        $daftar = TblDaftar::model()->findByPk($id_daftar);
        $sql = "UPDATE tbl_daftar SET datang='false', no_antrian=0 WHERE id_daftar=$id_daftar";
        Yii::app()->db->createCommand($sql)->execute();        
        
        $delete = "DELETE FROM tbl_hasil_uji WHERE id_daftar=$id_daftar";
        Yii::app()->db->createCommand($delete)->execute();
    }

    public function actionProsesBelumDatangChecked() {
        $idArray = $_POST['idChecked'];

        foreach ($idArray as $key => $arrayId):
            $id = explode('-', $arrayId);
            $id_daftar = $id[0];
            $id_uji = $id[1];
            $id_kendaraan = $id[2];
            $id_retribusi = $id[3];
            $daftar = TblDaftar::model()->findByPk($id_daftar);
        
            $sql = "UPDATE tbl_daftar SET datang='false' WHERE id_daftar=$id_daftar";
            Yii::app()->db->createCommand($sql)->execute();
            
            $delete = "DELETE FROM tbl_hasil_uji WHERE id_daftar=$id_daftar";
            Yii::app()->db->createCommand($delete)->execute();
        endforeach;
    }

    /*==================================
     * CAPTURE KEDATANGAN     * 
     ===================================*/
    public function actionKedatangan() {
        $this->pageTitle = 'KEDATANGAN';
        $this->render('kedatangan');
    }
    
    public function actionGetListKedatangan() {
        $selectCategory = $_POST['selectCategory'];
        $textCategory = strtoupper($_POST['textCategory']);
        $datang = $_POST['chooseProses'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 500;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'nomor_antrian';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;
        if($datang != 'all'){
            $criteria->addCondition("datang = $datang");
        }
        if (!empty($textCategory)) {
            if ($selectCategory == 'no_antrian' || $selectCategory == 'nama_pemilik') {
                $criteria->addCondition("(replace(LOWER($selectCategory),' ','') like replace(LOWER('%" . $textCategory . "%'),' ',''))");
            } else {
                $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('%" . $textCategory . "%'),' ',''))");
            }
        }
        $criteria->select='id_daftar,id_uji,id_kendaraan,id_retribusi,id_jns_kend,no_antrian,no_uji,no_kendaraan,nama_pemilik,jns_kend,nm_uji,buku';
        $result = VDatang::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $p) {
            $id = $p->id_daftar . "_" . $p->id_uji . "_" . $p->id_kendaraan . "_" . $p->id_retribusi . "_" . $p->id_jns_kend;
            $dataJson[] = array(
                "id" => $id,
                "id_daftar" => $p->id_daftar,
                "nomor_antrian" => $p->no_antrian,
                "no_uji" => $p->no_uji,
                "no_kendaraan" => $p->no_kendaraan,
                "nama_pemilik" => $p->nama_pemilik,
                "jns_kend" => $p->jns_kend,
                "nm_uji" => $p->nm_uji,
                "buku" => $p->buku
            );
        }

        header('Content-Type: application/json');
        echo CJSON::encode(
                array(
                    'total' => VDatang::model()->count($criteria),
                    'rows' => $dataJson,
                )
        );
        Yii::app()->end();
    }
    
    public function actionProsesKedatangan() {
        $id = explode('_', $_POST['id']);
        $id_daftar = $id[0];
        $id_uji = $id[1];
        $id_kendaraan = $id[2];
        $id_retribusi = $id[3];
        $id_jns_kend = $id[4];
        $petugas = Yii::app()->session['username'];
        
        $insert = "Select insert_hasil(" . $id_daftar . "," . $id_uji . "," . $id_kendaraan . ",'Auto','0')";
        Yii::app()->db->createCommand($insert)->query();
        $proses = "UPDATE tbl_proses set ptgs_datang='" . $petugas . "',id_daftar=" . $id_daftar . " where id_retribusi=" . $id_retribusi;
        Yii::app()->db->createCommand($proses)->query();
    }
}
