<?php

class ValidasiController extends Controller
{

    public function filters()
    {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }

    public function actionIndex()
    {
        $this->pageTitle = 'VALIDASI';
        $this->render('list_validasi');
    }

    public function actionValidasilistgrid()
    {
        $ok = Yii::app()->baseUrl . "/images/icon_approve.png";
        $reject = Yii::app()->baseUrl . "/images/icon_reject.png";
        $validasi = $_POST['chooseValidasi'];
        $selectCategory = $_POST['selectCategory'];
        $textCategory = strtoupper($_POST['textCategory']);
        $selectDate = strtoupper($_POST['selectDate']);
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id_retribusi';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;
        if (!empty($textCategory)) {
            if ($selectCategory == 'numerator') {
                $criteria->addCondition("$selectCategory = $textCategory");
            } else {
                $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('" . $textCategory . "'),' ',''))");
            }
        }
        if ($validasi != 'all') {
            $criteria->addCondition("validasi = $validasi");
        }
        $criteria->addCondition("tgl_retribusi = TO_DATE('" . $selectDate . "', 'DD-Mon-YY')");
        // $criteria->addCondition("tgl_retribusi = 'now' ::text::date");
        // $criteria->addCondition("tgl_retribusi = TO_DATE('26/10/16', 'DD/MM/YY')");
        $result = VValidasi::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $p) {
            $tgl_mati = TblRetribusi::model()->findByPk($p->id_retribusi)->tglmati;
            // $numerator_hari = sprintf('%03d', $p->numerator_hari);
            // $bln = date('n');
            // $bln_romawi = Yii::app()->params['bulanRomawi'][$bln - 1];
            $bk_masuk = '';
            if ($p->id_bk_masuk != 1) {
                $bk_masuk = "(" . $p->bk_masuk . ")";
            }
            $dataJson[] = array(
                "delete" => $p->id_retribusi,
                "kwitansi_skrd" => $p->id_retribusi,
                "kwitansi" => $p->id_retribusi,
                "skrd" => $p->id_retribusi,
                "ACTIONS" => $p->id_retribusi,
                "id_retribusi" => $p->id_retribusi,
                "idret_tglmati" => $p->id_retribusi . "_" . $tgl_mati . "_" . $p->id_jns_kend,
                "penerima" => strtoupper($p->penerima),
                "numerator" => $p->numerator,
                "numerator_hari" => $p->numerator_hari,
                "no_uji" => $p->no_uji,
                "no_kendaraan" => $p->no_kendaraan,
                "uji" => $p->nm_uji,
                "nama_pemilik" => $p->nama_pemilik,
                "b_retribusi_lebih" => number_format($p->b_retribusi_lebih, 0, ',', '.') . " <br/><b>(" . $p->tlt_retribusi . ")</b>",
                "b_berkala" => number_format($p->b_berkala, 0, ',', '.'),
                "b_rekom" => number_format($p->b_rekom, 0, ',', '.'),
                "b_buku" => number_format($p->b_buku, 0, ',', '.') . " <br/><b>" . $bk_masuk . "</b>",
                "b_tlt_uji" => number_format($p->b_tlt_uji, 0, ',', '.') . " <br/><b>(" . $p->lm_tlt . " bln)</b>",
                "b_plat_uji" => number_format($p->b_plat_uji, 0, ',', '.'),
                "total" => "<font color='red'><b>" . number_format($p->total, 0, ',', '.') . "</b></font>",
                "buku" => ($p->id_bk_masuk != 1) ? "<img src='$ok'>" : "<img src='$reject'>",
            );
        }
        header('Content-Type: application/json');
        echo CJSON::encode(
            array(
                'total' => VValidasi::model()->count($criteria),
                'rows' => $dataJson,
            )
        );
        Yii::app()->end();
    }

    public function actionDeleteRetribusi()
    {
        $id = $_POST['id'];
        $sql = "DELETE FROM tbl_retribusi WHERE id_retribusi = $id";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function actionProsesValidChecked()
    {
        $petugas = Yii::app()->session['username'];
        $idArray = $_POST['idArray'];
        $kondisi = $_POST['kondisi'];
        foreach ($idArray as $key => $arrayId) :
            $sqlUpdRetribusi = "UPDATE tbl_retribusi SET validasi = $kondisi, petugas_validasi = '$petugas' WHERE id_retribusi = $arrayId ";
            Yii::app()->db->createCommand($sqlUpdRetribusi)->execute();
        endforeach;
    }

    public function actionProsesValid()
    {
        $petugas = Yii::app()->session['username'];
        $idRetribusi = $_POST['idRetribusi'];
        $kondisi = $_POST['kondisi'];
        $sqlUpdRetribusi = "UPDATE tbl_retribusi SET validasi = $kondisi, petugas_validasi = '$petugas' WHERE id_retribusi = $idRetribusi";
        Yii::app()->db->createCommand($sqlUpdRetribusi)->execute();
    }

    public function actionRekapValidasi($tgl)
    {

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $xls->getActiveSheet();
        $xls->setActiveSheetIndex(0);
        $tglIndonesia = date("d", strtotime($tgl)) . " " . strtoupper(Yii::app()->params['bulanArrayInd'][date("n", strtotime($tgl)) - 1]) . " " . date("Y", strtotime($tgl));
        //======================================================================
        //HEADER
        $sheet->mergeCells("A1:P1");
        $sheet->setCellValue("A1", "DINAS PERHUBUNGAN");
        $sheet->getStyle("A1")->getFont()->setSize(16);
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A1")->getFont()->setBold(true);

        $sheet->mergeCells("A2:P2");
        $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR - KABUPATEN SAMPANG");
        $sheet->getStyle("A2")->getFont()->setSize(16);
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A2")->getFont()->setBold(true);

        $sheet->mergeCells("A3:P3");
        $sheet->setCellValue("A3", "DAFTAR PENERIMAAN UANG PENGUJIAN KENDARAAN BERMOTOR");
        $sheet->getStyle("A3")->getFont()->setSize(12);
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A3")->getFont()->setBold(true);

        $sheet->mergeCells("A4:P4");
        $sheet->setCellValue("A4", $tglIndonesia);
        $sheet->getStyle("A4")->getFont()->setSize(12);
        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A4")->getFont()->setBold(true);

        $sheet->mergeCells("A6:A7");
        $sheet->setCellValue("A6", "NO");
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("A")->setWidth(5);

        $sheet->mergeCells("B6:B7");
        $sheet->setCellValue("B6", "NUMERATOR");
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("B")->setWidth(13);

        $sheet->mergeCells("C6:C7");
        $sheet->setCellValue("C6", "JENIS UJI");
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("C")->setAutoSize(true);

        $sheet->mergeCells("D6:D7");
        $sheet->setCellValue("D6", "NAMA PEMILIK");
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("D")->setAutoSize(true);

        $sheet->getRowDimension(6)->setRowHeight(30);
        $sheet->getRowDimension(7)->setRowHeight(30);

        $sheet->mergeCells("E6:I6");
        $sheet->setCellValue("E6", "URAIAN KENDARAAN");
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("E7", "NO. KENDARAAN");
        $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("E")->setAutoSize(true);
        $sheet->setCellValue("F7", "NO. UJI");
        $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("F")->setAutoSize(true);
        $sheet->setCellValue("G7", "JENIS KENDARAAN");
        $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("G")->setAutoSize(true);
        $sheet->setCellValue("H7", "SIFAT");
        $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("H")->setAutoSize(true);
        $sheet->setCellValue("I7", "BAHAN BAKAR");
        $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("I7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("I")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("I")->setAutoSize(true);

        $sheet->mergeCells("J6:K6");
        $sheet->setCellValue("J6", "RETRIBUSI");
        $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("J6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->setCellValue("J7", "RETRIBUSI");
        $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("J7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("J")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("J")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("J")->setWidth(12);

        $sheet->setCellValue("K7", "RETRIBUSI >= 1Th");
        $sheet->getStyle("K7")->getAlignment()->setWrapText(true);
        $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("K7")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("K")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("K")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("K")->setWidth(12);

        $sheet->mergeCells("L6:L7");
        $sheet->setCellValue("L6", "REKOM");
        $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("L6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("L")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("L")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("L")->setWidth(12);

        $sheet->mergeCells("M6:M7");
        $sheet->setCellValue("M6", "KARTU UJI");
        $sheet->getStyle("M6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("M6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("M")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("M")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("M")->setWidth(12);

        $sheet->mergeCells("N6:N7");
        $sheet->setCellValue("N6", "DENDA");
        $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("N6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("N")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("N")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("N")->setWidth(12);

        $sheet->mergeCells("O6:O7");
        $sheet->setCellValue("O6", "TANDA SAMPING");
        $sheet->getStyle("O6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("O6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("O6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("O")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("O")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("O")->setWidth(12);
        //        
        $sheet->mergeCells("P6:P7");
        $sheet->setCellValue("P6", "TOTAL");
        $sheet->getStyle("P6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("P6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("P")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle("P")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("P")->setWidth(12);

        $sheet->getStyle("A6:P7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================
        $criteria = new CDbCriteria();
        $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
        $criteria->addCondition('validasi = true');
        $result = VValidasi::model()->findAll($criteria);
        //======================================================================
        //BODY
        $no = 1;
        $baris = 8;
        foreach ($result as $data) :
            $sheet->setCellValue("A" . $baris, $no);
            $sheet->setCellValue("B" . $baris, $data->numerator);
            $sheet->setCellValue("C" . $baris, $data->nm_uji);
            $sheet->setCellValue("D" . $baris, $data->nama_pemilik);
            $sheet->setCellValue("E" . $baris, $data->no_kendaraan);
            $sheet->setCellValue("F" . $baris, $data->no_uji);
            $sheet->setCellValue("G" . $baris, $data->nm_komersil);
            $sheet->setCellValue("H" . $baris, $data->umum === true ? "UMUM" : "TIDAK UMUM");
            $sheet->setCellValue("I" . $baris, $data->bahan_bakar);
            $sheet->setCellValue("J" . $baris, floatval($data->b_berkala));
            $sheet->setCellValue("K" . $baris, floatval($data->b_retribusi_lebih));
            $sheet->setCellValue("L" . $baris, floatval($data->b_rekom));
            $sheet->setCellValue("M" . $baris, floatval($data->b_buku));
            $sheet->setCellValue("N" . $baris, floatval($data->b_tlt_uji));
            $sheet->setCellValue("O" . $baris, floatval($data->b_plat_uji));
            $sheet->setCellValue("P" . $baris, "=SUM(J" . $baris . ":O" . $baris . ")");
            $sheet->getRowDimension($baris)->setRowHeight(20);
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
        $sheet->getStyle("A" . $baris . ":P" . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        $sheet->getStyle("A6:P" . $baris)->applyFromArray($styleArray);
        $sheet->getStyle("G8:P" . $baris)->getNumberFormat()->setFormatCode('#,##0');
        //======================================================================
        //FOOTER
        $sheet->mergeCells("A" . $baris . ":I" . $baris);
        $sheet->setCellValue("A" . $baris, "TOTAL");
        $sheet->getStyle("A" . $baris)->getFont()->setBold(true);
        $sheet->getStyle("A" . $baris)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("J" . $baris, '=SUM(J8:J' . $baris_border . ')');
        $sheet->getStyle("J" . $baris)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
        $sheet->getStyle("J" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("K" . $baris, '=SUM(K8:K' . $baris_border . ')');
        $sheet->getStyle("K" . $baris)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
        $sheet->getStyle("K" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("L" . $baris, '=SUM(L8:L' . $baris_border . ')');
        $sheet->getStyle("L" . $baris)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
        $sheet->getStyle("L" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("M" . $baris, '=SUM(M8:M' . $baris_border . ')');
        $sheet->getStyle("M" . $baris)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
        $sheet->getStyle("M" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("N" . $baris, '=SUM(N8:N' . $baris_border . ')');
        $sheet->getStyle("N" . $baris)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
        $sheet->getStyle("N" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("O" . $baris, '=SUM(O8:O' . $baris_border . ')');
        $sheet->getStyle("O" . $baris)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
        $sheet->getStyle("O" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->setCellValue("P" . $baris, '=SUM(P8:P' . $baris_border . ')');
        $sheet->getStyle("P" . $baris)->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT));
        $sheet->getStyle("P" . $baris)->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="RETRIBUSI_' . $tglIndonesia . '.xls"');
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

    public function actionCetakKuitansiSkrd($id)
    {
        $this->layout = '//';
        $data_retribusi = VValidasi::model()->findByAttributes(array('id_retribusi' => $id));
        $this->render('cetak_retribusi_skrd', array('id' => $id, 'data_retribusi' => $data_retribusi));
    }

    public function actionCetakRetribusi($id)
    {
        $this->layout = '//';
        $data_retribusi = VValidasi::model()->findByAttributes(array('id_retribusi' => $id));
        $this->render('cetak_retribusi', array('id' => $id, 'data_retribusi' => $data_retribusi));
    }

    public function actionCetakSkrd($id)
    {
        $this->layout = '//';
        $data_retribusi = VValidasi::model()->findByAttributes(array('id_retribusi' => $id));
        $this->render('cetak_skrd', array('id' => $id, 'data_retribusi' => $data_retribusi));
    }

    public function actionCetakCheckedRetribusi()
    {
        $this->layout = '//';
        $arrayId = $_REQUEST['idArray'];
        $idArray = explode(',', $arrayId);
        $this->render('cetak_checked_retribusi', array(
            'idArray' => $idArray
        ));
    }

    public function actionGetListSelect()
    {
        $pilih = $_POST['pilih'];
        $option = '';
        switch ($pilih) {
            case 'jenis_kendaraan':
                $tbl_jns_kend = TblJnsKend::model()->findAll();
                foreach ($tbl_jns_kend as $jns_kend) :
                    $option .= "<option value='$jns_kend->id_jns_kend'>$jns_kend->jns_kend</pilih>";
                endforeach;
                break;
            case 'jenis_uji':
                $tbl_uji = TblUji::model()->getEditRetribusi();
                foreach ($tbl_uji as $uji) :
                    $option .= "<option value='$uji->id_uji'>$uji->nm_uji</pilih>";
                endforeach;
                break;
            case 'buku':
                $tbl_bk_uji = TblBkMasuk::model()->findAll();
                foreach ($tbl_bk_uji as $bk_uji) :
                    $option .= "<option value='$bk_uji->id_bk_masuk'>$bk_uji->bk_masuk</pilih>";
                endforeach;
                break;
        }

        echo $option;
    }

    public function actionUpdateRetribusi()
    {
        $ex_idret_tglmati = explode('_', $_POST['dlg_idret_tglmati']);
        $id_retribusi = $ex_idret_tglmati[0];
        $tgl_mati = $ex_idret_tglmati[1];
        $jns_kend = $_POST['dlg_jns_kend'];
        $pilih_kategori = $_POST['pilih_kategori'];
        $kategori = 0;
        $textJbb = 0;
        $id_kendaraan = 0;
        $wilayah_asal = 'SMPNG';
        if ($pilih_kategori == 'tgluji') {
            $tgl_mati = date("m/d/Y", strtotime($_POST['ganti_tgl_uji']));
        } elseif ($pilih_kategori == 'denda') {
            $tgl_mati = $_POST['ganti_tgl_mati'];
        } elseif ($pilih_kategori == 'replace') {
            $textCategory = $_POST['ganti_replace'];
            $criteria = new CDbCriteria();
            $criteria->addCondition("( (replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','')) OR (replace(LOWER(no_kendaraan),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','')) )");
            $dtKend = TblKendaraan::model()->find($criteria);
            $id_kendaraan = $dtKend->id_kendaraan;
        } elseif ($pilih_kategori == 'jbb' and ($jns_kend != 4 or $jns_kend != 5)) {
            $textJbb = $_POST['ganti_jbb'];
        } elseif ($pilih_kategori == 'wilayah_asal') {
            $wilayah_asal = $_POST['wilayah_asal'];
        } else {
            $kategori = $_POST['kategori'];
        }
        $updateRetribusi = "Select edit_retribusi(" . $id_retribusi . ",'" . $tgl_mati . "','" . $pilih_kategori . "',$kategori, $textJbb, $id_kendaraan, '" . $wilayah_asal . "')";
        Yii::app()->db->createCommand($updateRetribusi)->query();
    }

    public function actionGetListCalculator()
    {
        $idArray = $_POST['idArray'];

        $jmlTotal = 0;
        foreach ($idArray as $key => $arrayId) :
            $dtRetribusi = VValidasi::model()->findByAttributes(array('id_retribusi' => $arrayId));
            $no_uji = $dtRetribusi->no_uji;
            $numerator = $dtRetribusi->numerator;
            $total = number_format($dtRetribusi->total, 0, ',', '.');
            $jmlTotal += $dtRetribusi->total;
            $dataJson[] = array(
                "no_uji" => $no_uji,
                "numerator" => $numerator,
                "total" => $total,
            );
        endforeach;
        header('Content-Type: application/json');
        echo CJSON::encode(
            array(
                'total' => count($idArray),
                'rows' => $dataJson,
                'totalcalculator' => number_format($jmlTotal, 0, ',', '.'),
            )
        );
    }

    public function actionCetakCheckedSkrd()
    {
        $this->layout = '//';
        $arrayId = $_REQUEST['idArray'];
        $idArray = explode(',', $arrayId);
        $this->render('cetak_checked_skrd', array(
            'idArray' => $idArray
        ));
    }

    public function actionRekap($tgl)
    {
        $tglIndonesia = date("d", strtotime($tgl)) . " " . strtoupper(Yii::app()->params['bulanArrayInd'][date("n", strtotime($tgl)) - 1]) . " " . date("Y", strtotime($tgl));

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        //======================================================================
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );
        //======================================================================
        $sheet = $xls->getActiveSheet();
        $xls->setActiveSheetIndex(0);
        //======================================================================
        //HEADER
        $sheet->mergeCells("A1:H1");
        $sheet->setCellValue("A1", "DINAS PERHUBUNGAN");
        $sheet->getStyle("A1")->getFont()->setSize(16);
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A1")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A1")->getFont()->setBold(true);

        $sheet->mergeCells("A2:H2");
        $sheet->setCellValue("A2", "PENGUJIAN KENDARAAN BERMOTOR - KABUPATEN SAMPANG");
        $sheet->getStyle("A2")->getFont()->setSize(16);
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A2")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A2")->getFont()->setBold(true);

        $sheet->mergeCells("A3:H3");
        $sheet->setCellValue("A3", "DAFTAR PENERIMAAN UANG PENGUJIAN KENDARAAN BERMOTOR");
        $sheet->getStyle("A3")->getFont()->setSize(12);
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A3")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A3")->getFont()->setBold(true);

        $sheet->mergeCells("A4:H4");
        $sheet->setCellValue("A4", $tglIndonesia);
        $sheet->getStyle("A4")->getFont()->setSize(12);
        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A4")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A4")->getFont()->setBold(true);

        $sheet->mergeCells("A6:A7");
        $sheet->setCellValue("A6", "NO");
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("A")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("A")->setWidth(5);

        $sheet->mergeCells("B6:B7");
        $sheet->setCellValue("B6", "NO REKENING");
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("B")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("B")->setWidth(20);

        $sheet->mergeCells("C6:C7");
        $sheet->setCellValue("C6", "JENIS KEND / KEGIATAN");
        $sheet->getStyle("C6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("C")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("C")->setWidth(20);

        $sheet->mergeCells("D6:D7");
        $sheet->setCellValue("D6", "URAIAN");
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("D6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT));
        $sheet->getStyle("D")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("D")->setWidth(20);

        $sheet->mergeCells("E6:E7");
        $sheet->setCellValue("E6", "JUMLAH KEND");
        $sheet->getStyle("E6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("E")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));

        $sheet->mergeCells("F6:F7");
        $sheet->setCellValue("F6", "BIAYA SAT KEGIATAN");
        $sheet->getStyle("F6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("F")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("F")->setWidth(10);

        $sheet->mergeCells("G6:G7");
        $sheet->setCellValue("G6", "JUMLAH BIAYA (Rp)");
        $sheet->getStyle("G6")->getAlignment()->setWrapText(true);
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("G")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("G")->setWidth(10);

        $sheet->mergeCells("H6:H7");
        $sheet->setCellValue("H6", "KET.");
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H6")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER));
        $sheet->getStyle("H")->getAlignment()->applyFromArray(array('vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER));
        $sheet->getColumnDimension("H")->setWidth(10);

        $sheet->getRowDimension(6)->setRowHeight(30);
        $sheet->getRowDimension(7)->setRowHeight(30);

        $sheet->getStyle("A6:H7")->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //END HEADER
        //======================================================================
        $kategori = ["MPU", "BUS", "PICK UP", "TRUCK", "GANDENGAN"];
        $uraian = array(
            "Uji & Ganti Kartu",
            "Uji & Tidak Ganti Kartu",
            "Numpang Uji",
            "Retribusi >= 1th",
            "Kartu Rusak",
            "Kartu Hilang",
            "Ganti Kartu",
            "Rekom"
        );
        //======================================================================
        //BODY
        $no = 1;
        $baris = 8;
        $total_result = 0;
        $total_biaya = 0;
        foreach ($kategori as $data) :
            $mergeKolom = $baris + 7;
            $sheet->mergeCells("A" . $baris . ":" . "A" . $mergeKolom);
            $sheet->mergeCells("B" . $baris . ":" . "B" . $mergeKolom);
            $sheet->mergeCells("C" . $baris . ":" . "C" . $mergeKolom);
            $sheet->setCellValue("A" . $baris, $no);
            $sheet->setCellValue("B" . $baris, "4.1.02.01.06");
            $sheet->setCellValue("C" . $baris, $data);
            $row_jumlah = $baris;
            foreach ($uraian as $key => $value) {
                /**
                 * BIAYA
                 */
                $biaya[4] = 25000;
                $biaya[5] = 200000;
                $biaya[6] = 25000;
                $biaya[7] = 50000;
                if ($data == "MPU") {
                    //uji & ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addNotInCondition("id_bk_masuk", array(1));
                    $result[0] = VValidasi::model()->count($criteria);
                    //uji & tidak ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addInCondition("id_bk_masuk", array(1));
                    $result[1] = VValidasi::model()->count($criteria);
                    //numpang uji
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(2));
                    $result[2] = VValidasi::model()->count($criteria);
                    //Retribusi >= 1th
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->select = 'SUM(tlt_retribusi) AS tlt_retribusi';
                    $result[3] = VValidasi::model()->find($criteria)->tlt_retribusi;
                    //Kartu Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_bk_masuk", array(4));
                    $result[4] = VValidasi::model()->count($criteria);
                    //Kartu Hilang
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_bk_masuk", array(3));
                    $result[5] = VValidasi::model()->count($criteria);
                    //Beli Kartu Hilang dan Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(10, 11));
                    $criteria->addInCondition("id_bk_masuk", array(3, 4));
                    $result[6] = VValidasi::model()->count($criteria);
                    //Rekom
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(3, 5, 7));
                    $result[7] = VValidasi::model()->count($criteria);

                    $biaya[0] = 110000;
                    $biaya[1] = 85000;
                    $biaya[2] = 110000;
                    $biaya[3] = 60000;
                } elseif ($data == "BUS") {
                    //uji & ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addNotInCondition("id_bk_masuk", array(1));
                    $result[0] = VValidasi::model()->count($criteria);
                    //uji & tidak ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addInCondition("id_bk_masuk", array(1));
                    $result[1] = VValidasi::model()->count($criteria);
                    //numpang uji
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(2));
                    $result[2] = VValidasi::model()->count($criteria);
                    //Retribusi >= 1th
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->select = 'SUM(tlt_retribusi) AS tlt_retribusi';
                    $result[3] = VValidasi::model()->find($criteria)->tlt_retribusi;
                    //Kartu Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_bk_masuk", array(4));
                    $result[4] = VValidasi::model()->count($criteria);
                    //Kartu Hilang
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_bk_masuk", array(3));
                    $result[5] = VValidasi::model()->count($criteria);
                    //Beli Kartu Hilang dan Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(10, 11));
                    $criteria->addInCondition("id_bk_masuk", array(3, 4));
                    $result[6] = VValidasi::model()->count($criteria);
                    //Rekom
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(1, 2));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(3, 5, 7));
                    $result[7] = VValidasi::model()->count($criteria);

                    $biaya[0] = 130000;
                    $biaya[1] = 105000;
                    $biaya[2] = 130000;
                    $biaya[3] = 80000;
                } elseif ($data == "PICK UP") {
                    //uji & ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addNotInCondition("id_bk_masuk", array(1));
                    $result[0] = VValidasi::model()->count($criteria);
                    //uji & tidak ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addInCondition("id_bk_masuk", array(1));
                    $result[1] = VValidasi::model()->count($criteria);
                    //numpang uji
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(2));
                    $result[2] = VValidasi::model()->count($criteria);
                    //Retribusi >= 1th
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->select = 'SUM(tlt_retribusi) AS tlt_retribusi';
                    $result[3] = VValidasi::model()->find($criteria)->tlt_retribusi;
                    //Kartu Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_bk_masuk", array(4));
                    $result[4] = VValidasi::model()->count($criteria);
                    //Kartu Hilang
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_bk_masuk", array(3));
                    $result[5] = VValidasi::model()->count($criteria);
                    //Beli Kartu Hilang dan Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(10, 11));
                    $criteria->addInCondition("id_bk_masuk", array(3, 4));
                    $result[6] = VValidasi::model()->count($criteria);
                    //Rekom
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0));
                    $criteria->addCondition("kemjbb::integer <= 3500");
                    $criteria->addInCondition("id_uji", array(3, 5, 7));
                    $result[7] = VValidasi::model()->count($criteria);

                    $biaya[0] = 110000;
                    $biaya[1] = 85000;
                    $biaya[2] = 110000;
                    $biaya[3] = 60000;
                } elseif ($data == "TRUCK") {
                    //uji & ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addNotInCondition("id_bk_masuk", array(1));
                    $result[0] = VValidasi::model()->count($criteria);
                    //uji & tidak ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addInCondition("id_bk_masuk", array(1));
                    $result[1] = VValidasi::model()->count($criteria);
                    //numpang uji
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(2));
                    $result[2] = VValidasi::model()->count($criteria);
                    //Retribusi >= 1th
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->select = 'SUM(tlt_retribusi) AS tlt_retribusi';
                    $result[3] = VValidasi::model()->find($criteria)->tlt_retribusi;
                    //Kartu Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_bk_masuk", array(4));
                    $result[4] = VValidasi::model()->count($criteria);
                    //Kartu Hilang
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_bk_masuk", array(3));
                    $result[5] = VValidasi::model()->count($criteria);
                    //Beli Kartu Hilang dan Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(10, 11));
                    $criteria->addInCondition("id_bk_masuk", array(3, 4));
                    $result[6] = VValidasi::model()->count($criteria);
                    //Rekom
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(0, 3));
                    $criteria->addCondition("kemjbb::integer > 3500");
                    $criteria->addInCondition("id_uji", array(3, 5, 7));
                    $result[7] = VValidasi::model()->count($criteria);

                    $biaya[0] = 130000;
                    $biaya[1] = 105000;
                    $biaya[2] = 130000;
                    $biaya[3] = 80000;
                } elseif ($data == "GANDENGAN") {
                    //uji & ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addNotInCondition("id_bk_masuk", array(1));
                    $result[0] = VValidasi::model()->count($criteria);
                    //uji & tidak ganti kartu
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_uji", array(1, 4, 8));
                    $criteria->addInCondition("id_bk_masuk", array(1));
                    $result[1] = VValidasi::model()->count($criteria);
                    //numpang uji
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_uji", array(2));
                    $result[2] = VValidasi::model()->count($criteria);
                    //Retribusi >= 1th
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->select = 'SUM(tlt_retribusi) AS tlt_retribusi';
                    $result[3] = VValidasi::model()->find($criteria)->tlt_retribusi;
                    //Kartu Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_bk_masuk", array(4));
                    $result[4] = VValidasi::model()->count($criteria);
                    //Kartu Hilang
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_bk_masuk", array(3));
                    $result[5] = VValidasi::model()->count($criteria);
                    //Beli Kartu Hilang dan Rusak
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_uji", array(10, 11));
                    $criteria->addInCondition("id_bk_masuk", array(3, 4));
                    $result[6] = VValidasi::model()->count($criteria);
                    //Rekom
                    $criteria = new CDbCriteria();
                    $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
                    $criteria->addInCondition("id_jns_kend", array(4, 5));
                    $criteria->addInCondition("id_uji", array(3, 5, 7));
                    $result[7] = VValidasi::model()->count($criteria);

                    $biaya[0] = 170000;
                    $biaya[1] = 145000;
                    $biaya[2] = 170000;
                    $biaya[3] = 120000;
                }

                if ($key == 0 || $key == 1 || $key == 2) {
                    $total_result += $result[$key];
                }
                $kali_biaya = $result[$key] * $biaya[$key];
                $total_biaya += $kali_biaya;

                $sheet->setCellValue("D" . $baris, $value);
                $sheet->setCellValue("E" . $baris, $result[$key] == 0 ? '' : $result[$key]);
                $sheet->setCellValue("F" . $baris, $biaya[$key]);
                $sheet->setCellValue("G" . $baris, $kali_biaya);
                $sheet->setCellValue("H" . $baris, "");
                $sheet->getStyle("F$baris:G$baris")->getNumberFormat()->setFormatCode('#,##0');
                $baris++;
            }
            $sheet->mergeCells("A" . $baris . ":" . "D" . $baris);
            $sheet->setCellValue("A" . $baris, "JUMLAH");
            $mergeKolom5 = $mergeKolom - 5;
            $sheet->setCellValue("E" . $baris, "=SUM(E" . $row_jumlah . ":E" . $mergeKolom5 . ")");
            $sheet->setCellValue("G" . $baris, "=SUM(G" . $row_jumlah . ":G" . $mergeKolom . ")");
            $sheet->getStyle("G$baris")->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getStyle("A" . $baris . ":H" . $baris)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');

            // $sheet->getStyle("G8:H" . $baris)->getNumberFormat()->setFormatCode('#,##0');
            $sheet->getRowDimension($baris)->setRowHeight(20);
            $baris++;
            $no++;
        endforeach;
        $baris_total = $baris + 1;
        $baris_denda = $baris_total + 2;
        $baris_total_keseluruhan = $baris_denda + 2;
        $sheet->mergeCells("A" . $baris_total . ":" . "D" . $baris_total);
        $sheet->setCellValue("A" . $baris_total, "JUMLAH SELURUHNYA");
        $sheet->setCellValue("E" . $baris_total, $total_result);
        $sheet->setCellValue("G" . $baris_total, $total_biaya);
        $sheet->getStyle("G$baris_total")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getRowDimension($baris_total)->setRowHeight(20);
        $sheet->getStyle("A" . $baris_total . ":H" . $baris_total)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        //HITUNG DENDA
        $criteria = new CDbCriteria();
        $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
        $criteria->select = "sum(b_tlt_uji) as b_tlt_uji";
        $nilai_denda = VValidasi::model()->find($criteria);
        $sheet->setCellValue("A" . $baris_denda, "6");
        $sheet->setCellValue("B" . $baris_denda, "4.1.02.01.06");
        $sheet->mergeCells("C" . $baris_denda . ":" . "D" . $baris_denda);
        $sheet->getStyle("C" . $baris_denda)->getAlignment()->setWrapText(true);
        $sheet->setCellValue("C" . $baris_denda, "Pendapatan Denda Retribusi Pengujian Kendaraan Bermotor");
        $sheet->setCellValue("G" . $baris_denda, $nilai_denda->b_tlt_uji);
        $sheet->getRowDimension($baris_denda)->setRowHeight(40);
        $sheet->getStyle("G$baris_denda")->getNumberFormat()->setFormatCode('#,##0');
        //HITUNG TOTAL KESELURUHAN
        $sheet->setCellValue("A" . $baris_total_keseluruhan, "7");
        $sheet->mergeCells("B" . $baris_total_keseluruhan . ":" . "F" . $baris_total_keseluruhan);
        $sheet->setCellValue("B" . $baris_total_keseluruhan, "TOTAL KESELURUHAN");
        $sheet->setCellValue("G" . $baris_total_keseluruhan, $total_biaya + $nilai_denda->b_tlt_uji);
        $sheet->getRowDimension($baris_total_keseluruhan)->setRowHeight(40);
        $sheet->getStyle("G$baris_total_keseluruhan")->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle("A" . $baris_total_keseluruhan . ":H" . $baris_total_keseluruhan)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('B5D0B3');
        $sheet->getStyle("A6:H" . $baris_total_keseluruhan)->applyFromArray($styleArray);
        //END BODY
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="RETRIBUSI_' . $tglIndonesia . '.xls"');
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
