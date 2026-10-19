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
        $this->pageTitle = 'BUKU UJI';
        $this->render('list_buku_uji');
    }

    public function actionBukulistgrid()
    {
        $cetak = $_POST['chooseProses'];
        //        $selectCategory = $_POST['selectCategory'];
        $textCategory = strtoupper($_POST['textCategory']);
        $selectDate = strtoupper($_POST['selectDate']);
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'numerator';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;
        if (!empty($textCategory)) {
            //            if ($selectCategory == 'numerator') {
            //                $criteria->addCondition("$selectCategory = '$textCategory'");
            //            } else {
            $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('" . $textCategory . "'),' ',''))");
            //            }
        }
        if ($cetak != 'all') {
            $criteria->addCondition("cetak = $cetak");
        }
        $criteria->addCondition("tgl_retribusi = TO_DATE('" . $selectDate . "', 'DD-Mon-YY')");
        //        $criteria->addCondition("tgl_retribusi = 'now' ::text::date");
        $result = VGantibuku::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $p) {
            $tgl_cetak = (!empty($p->tgl_cetak)) ? date('d-M-Y', strtotime($p->tgl_cetak)) : date('d-M-Y');
            $tgl_cetak_table = (!empty($p->tgl_cetak)) ? date('d-M-Y', strtotime($p->tgl_cetak)) : '';
            $no_seri = $p->no_seri == '' ? Yii::app()->session['ses_no_seri'] : $p->no_seri;
            //            $numerator_hari = sprintf('%03d', $p->numerator_hari);
            //            $bln = date('n');
            //            $bln_romawi = Yii::app()->params['bulanRomawi'][$bln - 1];
            $dataJson[] = array(
                "id_retribusi" => $p->id_retribusi . "|" . $no_seri . "|" . $tgl_cetak,
                "id_kendaraan" => $p->id_kendaraan,
                "numerator_hari" => $p->numerator_hari,
                "no_uji" => $p->no_uji,
                "no_kendaraan" => $p->no_kendaraan,
                "nama_pemilik" => $p->nama_pemilik,
                "no_seri" => $p->no_seri,
                "tgl_cetak" => $tgl_cetak_table,
                "petugas" => strtoupper($p->petugas)
            );
        }
        header('Content-Type: application/json');
        echo CJSON::encode(
            array(
                'total' => VGantibuku::model()->count($criteria),
                'rows' => $dataJson,
            )
        );
        Yii::app()->end();
    }

    public function actionBukuPerKendaraanlistgrid()
    {
        //        $cetak = $_POST['chooseProses'];
        //        $selectCategory = $_POST['selectCategory'];
        $textCategory = strtoupper($_POST['textCategory']);
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'tgl_retribusi';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;
        //        if (!empty($textCategory)) {
        //            if ($selectCategory == 'numerator') {
        //                $criteria->addCondition("$selectCategory = '$textCategory'");
        //            } else {
        $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $textCategory . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('" . $textCategory . "'),' ',''))");
        //            }
        //        }
        //        if ($cetak != 'all') {
        //            $criteria->addCondition("cetak = $cetak");
        //        }
        //        $criteria->addCondition("tgl_retribusi = TO_DATE('" . $selectDate . "', 'DD-Mon-YY')");
        //        $criteria->addCondition("tgl_retribusi = 'now' ::text::date");
        $result = VGantibuku::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $p) {
            $tgl_cetak = (!empty($p->tgl_cetak)) ? date('d-M-Y', strtotime($p->tgl_cetak)) : date('d-M-Y');
            $tgl_cetak_table = (!empty($p->tgl_cetak)) ? date('d-M-Y', strtotime($p->tgl_cetak)) : '';
            $tgl_retribusi = (!empty($p->tgl_retribusi)) ? date('d-M-Y', strtotime($p->tgl_retribusi)) : date('d-M-Y');
            $no_seri = $p->no_seri == '' ? Yii::app()->session['ses_no_seri'] : $p->no_seri;
            $dataJson[] = array(
                "id_retribusi" => $p->id_retribusi . "|" . $no_seri . "|" . $tgl_cetak,
                "id_kendaraan" => $p->id_kendaraan,
                "no_uji" => $p->no_uji,
                "no_kendaraan" => $p->no_kendaraan,
                "nama_pemilik" => $p->nama_pemilik,
                "no_seri" => $p->no_seri,
                "tgl_retribusi" => $tgl_retribusi,
                "tgl_cetak" => $tgl_cetak_table,
                "petugas" => strtoupper($p->petugas)
            );
        }
        header('Content-Type: application/json');
        echo CJSON::encode(
            array(
                'total' => VGantibuku::model()->count($criteria),
                'rows' => $dataJson,
            )
        );
        Yii::app()->end();
    }

    public function actionRiwayatbukulistgrid()
    {
        $id_kendaraan = $_POST['idKendaraan'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'tgl_uji';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;
        $criteria->addCondition("id_kendaraan = $id_kendaraan");
        $result = VDetBuku::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $p) {
            $dataJson[] = array(
                "id_hasil_uji" => $p->id_hasil_uji . "|" . $p->nrp . "|" . $p->id_kendaraan,
                "tgl_uji" => (!empty($p->tgl_uji)) ? date('d M y', strtotime($p->tgl_uji)) : '',
                "berlaku" => (!empty($p->berlaku)) ? date('d M y', strtotime($p->berlaku)) : '',
                "no_uji" => $p->no_uji,
                "no_kendaraan" => $p->no_kendaraan,
                "tempat" => $p->tempat,
                "catatan" => $p->catatan,
                "nama_penguji" => $p->nama_penguji,
                "nrp" => $p->nrp
            );
        }
        header('Content-Type: application/json');
        echo CJSON::encode(
            array(
                'total' => VDetBuku::model()->count($criteria),
                'rows' => $dataJson,
            )
        );
        Yii::app()->end();
    }

    public function actionSaveBukuUji()
    {
        $id_retribusi = $_POST['dlg_text_id_retribusi'];
        $no_seris = strtoupper($_POST['dlg_text_no_seri']);
        $var_no_seri = str_replace("'", " ", $no_seris);
        $var_nomor_seri = preg_replace("/([[:alpha:]])([[:digit:]])/", "\\1 \\2", $var_no_seri);
        $no_seri = preg_replace("/([[:digit:]])([[:alpha:]])/", "\\1 \\2", $var_nomor_seri);
        $tgl_cetak = date("m/d/Y", strtotime($_POST['ganti_tgl_cetak']));
        $petugas = Yii::app()->session['username'];
        Yii::app()->session->add('ses_no_seri', $no_seri);
        $data_buku = TblBuku::model()->findByAttributes(array('id_retribusi' => $id_retribusi));
        $countBuku = count($data_buku);
        if ($countBuku == 0) {
            $buku = new TblBuku();
            $buku->petugas = $petugas;
            $buku->id_retribusi = $id_retribusi;
            $buku->no_seri = $no_seri;
            $buku->cetak = true;
            $buku->tgl_cetak = $tgl_cetak;
            if ($buku->save()) {
                $id_buku = $buku->id_buku;
            }
        } else {
            $id_buku = $data_buku->id_buku;
            $sql = "UPDATE tbl_buku SET cetak = 'true', petugas='$petugas', no_seri='$no_seri', tgl_cetak='$tgl_cetak' WHERE id_buku = $id_buku";
            Yii::app()->db->createCommand($sql)->execute();
        }

        $result['id_buku'] = $id_buku;
        echo json_encode($result);
    }

    public function actionCetakBukuUji($id)
    {
        $this->layout = '//';
        $this->render('cetak_buku_uji', array(
            'id_buku' => $id
        ));
    }

    public function actionSaveCetakl()
    {
        $id = $_POST['id'];
        $statuspenerbitan = $_POST['posisi'];
        $user_id = $_POST['penguji'];
        $dtHasilUji = VPrintHasil::model()->findByAttributes(array('id_hasil_uji' => $id));
        //PENGUJI
        $tblPenguji = MasterEmployee::model()->findByAttributes(array('user_id' => $user_id));
        $nrp = $tblPenguji->identity_number;
        $nm_penguji = $tblPenguji->full_name;
        $jabatan = $tblPenguji->pangkat;
        $id_petugasuji_new = $tblPenguji->user_id;
        $id_direktur_new = MasterEmployee::model()->findByAttributes(array('job_name' => 'Direktur'))->user_id;
        $id_kepaladinas_new = MasterEmployee::model()->findByAttributes(array('job_name' => 'Kepala Dinas'))->user_id;

        $kode_wilayah_asal = 'SMPNG';
        $data_area = MasterArea::model()->findByAttributes(array('area_code' => $kode_wilayah_asal));
        $area_from_id = $data_area->area_id;
        $area_from_name = $data_area->area_name;
        /*
         * JBKI
         */
        $jbki = '0';
        $nouji = $dtHasilUji->no_uji;
        $nama = $dtHasilUji->nama_pemilik;
        $noidentitaspemilik = $dtHasilUji->no_identitas;
        if (empty($dtHasilUji->no_identitas)) {
            $noidentitaspemilik = NULL;
        }
        $alamat = ucwords(strtolower($dtHasilUji->alamat));
        $nosertifikatreg = $dtHasilUji->no_regis;
        $tglsertifikatreg = date('m/d/Y', strtotime($dtHasilUji->tgl_regis));
        $tglsertifikatreg_new = date('dmY', strtotime($dtHasilUji->tgl_regis));
        $noregistrasikendaraan = $dtHasilUji->no_kendaraan;
        $norangka = $dtHasilUji->no_chasis;
        $nomesin = $dtHasilUji->no_mesin;
        $merek = $dtHasilUji->merk;
        $tipe = $dtHasilUji->tipe;
        $jenis = $dtHasilUji->karoseri_jenis;
        $sub_jenis = $dtHasilUji->nm_komersil;
        $varian = $dtHasilUji->tipe;
        $sub_varian = MasterMerkTipeSub::model()->findByPk($dtHasilUji->vehicle_varian_id)->vehicle_varian_name;
        $thpembuatan = $dtHasilUji->tahun;
        $bahanbakar = $dtHasilUji->bahan_bakar;
        $isisilinder = $dtHasilUji->isi_silinder;
        $dayamotorpenggerak = $dtHasilUji->daya_motor;
        $jbb = $dtHasilUji->kemjbb;
        $jbkb = $dtHasilUji->kemjbkb;
        $jbi = $dtHasilUji->jbi;
        $jbki = $jbki;
        $mst = $dtHasilUji->mst;
        $beratkosong = $dtHasilUji->berat_kosong;
        $konfigurasisumburoda = $dtHasilUji->konsumbu;
        $bsumbu1 = $dtHasilUji->bsumbu1;
        $bsumbu2 = $dtHasilUji->bsumbu2;
        $bsumbu3 = $dtHasilUji->bsumbu3;
        $bsumbu4 = $dtHasilUji->bsumbu4;
        $bsumbu5 = $dtHasilUji->bsumbu5;
        if ($konfigurasisumburoda == '1.1' || $konfigurasisumburoda == '2.2' || $konfigurasisumburoda == '1.2') {
            $jumlahsumbu = 2;
        } elseif ($konfigurasisumburoda == '1.1.1' || $konfigurasisumburoda == '2.2.2' || $konfigurasisumburoda == '1.1.2' || $konfigurasisumburoda == '1.2.2') {
            $jumlahsumbu = 3;
        } else {
            $jumlahsumbu = 4;
        }
        $alatuji_gayapengereman1kanan = $dtHasilUji->gaya_rem_kanan1;
        $alatuji_gayapengereman2kanan = $dtHasilUji->gaya_rem_kanan2;
        $alatuji_gayapengereman3kanan = $dtHasilUji->gaya_rem_kanan3;
        $alatuji_gayapengereman4kanan = $dtHasilUji->gaya_rem_kanan4;
        $alatuji_gayapengereman1kiri = $dtHasilUji->gaya_rem_kiri1;
        $alatuji_gayapengereman2kiri = $dtHasilUji->gaya_rem_kiri2;
        $alatuji_gayapengereman3kiri = $dtHasilUji->gaya_rem_kiri3;
        $alatuji_gayapengereman4kiri = $dtHasilUji->gaya_rem_kiri4;
        $total_gaya_pengereman_kanan = $alatuji_gayapengereman1kanan + $alatuji_gayapengereman2kanan + $alatuji_gayapengereman3kanan + $alatuji_gayapengereman4kanan;
        $total_gaya_pengereman_kiri = $alatuji_gayapengereman1kiri + $alatuji_gayapengereman2kiri + $alatuji_gayapengereman3kiri + $alatuji_gayapengereman4kiri;
        $ukuranban = $dtHasilUji->psumbu1;
        $panjangkendaraan = $dtHasilUji->ukuran_panjang;
        $lebarkendaraan = $dtHasilUji->ukuran_lebar;
        $tinggikendaraan = $dtHasilUji->ukuran_tinggi;
        $panjangbakatautangki = $dtHasilUji->dimpanjang;
        $lebarbakatautangki = $dtHasilUji->dimlebar;
        $tinggibakatautangki = $dtHasilUji->dimtinggi;
        $julurdepan = $dtHasilUji->foh;
        $julurbelakang = $dtHasilUji->roh;
        $jaraksumbu1_2 = $dtHasilUji->jsumbu1;
        $jaraksumbu2_3 = $dtHasilUji->jsumbu2;
        $jaraksumbu3_4 = $dtHasilUji->jsumbu3;
        $wheel_base = $jaraksumbu1_2 + $jaraksumbu2_3 + $jaraksumbu3_4;
        $dayaangkutorang = $dtHasilUji->karoseri_duduk;
        $dayaangkutbarang = $dtHasilUji->kembarang;
        $kelasjalanterendah = $dtHasilUji->kls_jln;
        $kodewilayah = 'SMPNG';
        $kodewilayahasal = $kode_wilayah_asal;
        $kelasjalanterendah = $dtHasilUji->kls_jln;
        $kelasjalan_id = $dtHasilUji->kelasjalan_id;
        $fuel_id = $dtHasilUji->fuel_id;
        $vehicle_varian_id = $dtHasilUji->vehicle_varian_id;
        $vehicle_varian_type_id = $dtHasilUji->vehicle_varian_type_id;
        $vehicle_brand_id = $dtHasilUji->vehicle_brand_id;
        $vehicle_type_id = $dtHasilUji->vehicle_type_id;
        $vehicle_type_sub_id = $dtHasilUji->vehicle_type_sub_id;
        $huv_nomordankondisirangka = 1;
        $huv_nomordantipemotorpenggerak = 1;
        $huv_kondisitangkicorongdanpipabahanbakar = 1;
        $huv_kondisiconverterkit = 1;
        $huv_kondisidanposisipipapembuangan = 1;
        $huv_ukurandankondisiban = 1;
        $huv_kondisisistemsuspensi = 1;
        $huv_kondisisistemremutama = 1;
        $huv_kondisipenutuplampudanalatpantulcahaya = 1;
        $huv_kondisipanelinstrumentdashboard = 1;
        $huv_kondisikacaspion = 1;
        $huv_kondisispakbor = 1;
        $huv_bentukbumper = 1;
        $huv_keberadaandankondisiperlengkapan = 1;
        $huv_rancanganteknis = 1;
        $huv_keberadaandankondisifasilitastanggapdaruratuntukmobilbus = 1;
        $huv_kondisibadankacaengseltempatdudukmbarangbakmuatantertutup = 1;
        $hum_kondisipenerusdaya = 1;
        $hum_sudutbebaskemudi = 1;
        $hum_kondisiremparkir = 1;
        $hum_fungsilampudanalatpantulcahaya = 1;
        $hum_fungsipenghapuskaca = 1;
        $hum_tingkatkegelapankaca = 1;
        $hum_fungsiklakson = 1;
        $hum_kondisidanfungsisabukkeselamatan = 1;
        $hum_ukurankendaraan = 1;
        $hum_ukurantempatdudukdanbagiandalamkendaraanuntukmobilbus = 1;
        $alatuji_emisiasapbahanbakarsolar = $dtHasilUji->ems_diesel;
        $alatuji_emisicobahanbakarbensin = $dtHasilUji->ems_mesin_co;
        $alatuji_emisihcbahanbakarbensin = $dtHasilUji->ems_mesin_hc;
        $alatuji_remutamatotalgayapengereman = $total_gaya_pengereman_kanan + $total_gaya_pengereman_kiri;
        $alatuji_remutamaselisihgayapengeremanrodakirikanan1 = $dtHasilUji->selgaya1;
        $alatuji_remutamaselisihgayapengeremanrodakirikanan2 = $dtHasilUji->selgaya2;
        $alatuji_remutamaselisihgayapengeremanrodakirikanan3 = $dtHasilUji->selgaya3;
        $alatuji_remutamaselisihgayapengeremanrodakirikanan4 = $dtHasilUji->selgaya4;
        $alatuji_remparkirkaki = $dtHasilUji->gaya_rem_parkir_kaki;
        $alatuji_remparkirtangan =  $dtHasilUji->gaya_rem_parkir_tangan;
        $efisiensi_remparkir_tangan =  $dtHasilUji->efisiensi_gaya_rem_parkir_tangan;
        $efisiensi_remparkir_kaki =  $dtHasilUji->efisiensi_gaya_rem_parkir_kaki;
        $alatuji_gayapengeremanparkirkanan = $dtHasilUji->gaya_rem_parkir_kanan;
        $alatuji_gayapengeremanparkirkiri = $dtHasilUji->gaya_rem_parkir_kiri;
        $alatuji_remparkirtotalgayapengereman = ($alatuji_remparkirkaki + $alatuji_remparkirtangan);
        $alatuji_kincuprodadepan = rand(1, 5);
        $alatuji_tingkatkebisingan = rand(83, 118);
        $alatuji_lampuutamakekuatanpancarlampukanan = $dtHasilUji->ktlamp_kanan;
        $alatuji_lampuutamakekuatanpancarlampukiri = $dtHasilUji->ktlamp_kiri;
        $alatuji_lampuutamapenyimpanganlampukanan = number_format($dtHasilUji->dev_kanan, 2, '.', '.');
        $alatuji_lampuutamapenyimpanganlampukiri = number_format($dtHasilUji->dev_kiri, 2, '.', '.');
        $alatuji_penunjukkecepatan = 40;
        $alatuji_kedalamanalurban = rand(1, 15);
        $alatuji_alatpemantulcahayatambahan_kuning = rand(75, 130);
        $alatuji_alatpemantulcahayatambahan_putih = rand(95, 200);
        $alatuji_alatpemantulcahayatambahan_merah = rand(30, 60);
        $masaberlakuuji = date('dmY', strtotime($dtHasilUji->tgl_mati_uji));
        $tgluji = date('dmY', strtotime($dtHasilUji->tgl_uji));
        $arrDtPengujian = new CDbCriteria();
        $arrDtPengujian->addCondition("tgluji = '$tgluji'");
        $arrDtPengujian->addCondition("nouji = '$dtHasilUji->no_uji'");
        $arrDtPengujian->addCondition("statuspenerbitan = '$statuspenerbitan'");
        $cekDtPengujian = Datapengujian::model()->find($arrDtPengujian);
        $statuslulusuji = TRUE;
        if (!empty($cekDtPengujian)) {
            $sql = "DELETE FROM datapengujian WHERE tgluji='$tgluji' and nouji = '$dtHasilUji->no_uji'";
            Yii::app()->db->createCommand($sql)->execute();
        }

        $sql = "INSERT INTO datapengujian (
                statuspenerbitan,
                nouji,
                nama,
                alamat,
                noidentitaspemilik,
                nosertifikatreg,
                tglsertifikatreg,
                nosuratkehilangan,
                noregistrasikendaraan,
                tgl_registrasikendaraan,
                norangka,
                nomesin,
                merek,
                tipe,
                jenis,
                subjenis_kendaraan,
                varian_kendaraan,
                sub_varian_kendaraan,
                thpembuatan,
                bahanbakar,
                isisilinder,
                dayamotorpenggerak,
                jbb,
                jbkb,
                jbi,
                jbki,
                mst,
                beratkosong,
                konfigurasisumburoda,
                ukuranban,
                panjangkendaraan,
                lebarkendaraan,
                tinggikendaraan,
                panjangbakatautangki,
                lebarbakatautangki,
                tinggibakatautangki,
                jumlah_sumbu,
                julurdepan,
                julurbelakang,
                wheel_base,
                jaraksumbu1_2,
                jaraksumbu2_3,
                jaraksumbu3_4,
                jaraksumbu4_5,
                jaraksumbu5_6,
                jaraksumbu6_7,
                jaraksumbu7_8,
                jaraksumbu8_9,
                jaraksumbu9_10,
                jaraksumbu10_11,
                jaraksumbu11_12,
                dayaangkutorang,
                dayaangkutbarang,
                kelasjalanterendah,
                masaberlakuuji,
                tgluji,
                statuslulusuji,
                kodewilayah,
                kodewilayahasal,
                area_from_id,
                area_from_name,
                vehicle_brand_id,
                vehicle_type_id,
                vehicle_sub_id,
                vehicle_varian_type_id,
                vehicle_varian_id,
                fuel_id,
                kelasjalan_id,
                idpetugasuji,
                idkepaladinas,
                iddirektur,
                fotodepansmall,
                fotobelakangsmall,
                fotokanansmall,
                fotokirismall,
                huv_nomordankondisirangka,
                huv_nomordantipemotorpenggerak,
                huv_kondisitangkicorongdanpipabahanbakar,
                huv_kondisiconverterkit,
                huv_kondisidanposisipipapembuangan,
                huv_ukurandankondisiban,
                huv_kondisisistemsuspensi,
                huv_kondisisistemremutama,
                huv_kondisipenutuplampudanalatpantulcahaya,
                huv_kondisipanelinstrumentdashboard,
                huv_kondisikacaspion,
                huv_kondisispakbor,
                huv_bentukbumper,
                huv_keberadaandankondisiperlengkapan,
                huv_rancanganteknis,
                huv_keberadaandankondisifasilitastanggapdaruratuntukmobilbus,
                huv_kondisibadankacaengseltempatdudukmbarangbakmuatantertutup,
                hum_kondisipenerusdaya,
                hum_sudutbebaskemudi,
                hum_kondisiremparkir,
                hum_fungsilampudanalatpantulcahaya,
                hum_fungsipenghapuskaca,
                hum_tingkatkegelapankaca,
                hum_fungsiklakson,
                hum_kondisidanfungsisabukkeselamatan,
                hum_ukurankendaraan,
                hum_ukurantempatdudukdanbagiandalamkendaraanuntukmobilbus,
                berat_sumbu1,
                berat_sumbu2,
                berat_sumbu3,
                berat_sumbu4,
                berat_sumbu5,
                berat_sumbu6,
                berat_sumbu7,
                berat_sumbu8,
                berat_sumbu9,
                berat_sumbu10,
                berat_sumbu11,
                berat_sumbu12,
                alatuji_emisiasapbahanbakarsolar,
                alatuji_emisicobahanbakarbensin,
                alatuji_emisihcbahanbakarbensin,
                alatuji_gayaremparkirtangan,
                alatuji_gayaremparkirkaki,
                alatuji_gayapengereman1kanan,
                alatuji_gayapengereman2kanan,
                alatuji_gayapengereman3kanan,
                alatuji_gayapengereman4kanan,
                alatuji_gayapengereman5kanan,
                alatuji_gayapengereman6kanan,
                alatuji_gayapengereman7kanan,
                alatuji_gayapengereman8kanan,
                alatuji_gayapengereman9kanan,
                alatuji_gayapengereman10kanan,
                alatuji_gayapengereman11kanan,
                alatuji_gayapengereman12kanan,
                alatuji_gayapengereman1kiri,
                alatuji_gayapengereman2kiri,
                alatuji_gayapengereman3kiri,
                alatuji_gayapengereman4kiri,
                alatuji_gayapengereman5kiri,
                alatuji_gayapengereman6kiri,
                alatuji_gayapengereman7kiri,
                alatuji_gayapengereman8kiri,
                alatuji_gayapengereman9kiri,
                alatuji_gayapengereman10kiri,
                alatuji_gayapengereman11kiri,
                alatuji_gayapengereman12kiri,
                alatuji_gayapengeremanparkirkanan,
                alatuji_gayapengeremanparkirkiri,
                alatuji_remutamatotalgayapengereman,
                alatuji_remparkirtotalgayapengereman,
                alatuji_kincuprodadepan,
                alatuji_tingkatkebisingan,
                alatuji_lampuutamakekuatanpancarlampukanan,
                alatuji_lampuutamakekuatanpancarlampukiri,
                alatuji_lampuutamapenyimpanganlampukanan,
                alatuji_lampuutamapenyimpanganlampukiri,
                alatuji_penunjukkecepatan,
                alatuji_kedalamanalurban,
                alatuji_alatpemantulcahayatambahan_kuning,
                alatuji_alatpemantulcahayatambahan_putih,
                alatuji_alatpemantulcahayatambahan_merah
        ) VALUES (
                '$statuspenerbitan',
                '$nouji',
                '$nama',
                '$alamat',
                '$noidentitaspemilik',
                '$nosertifikatreg',
                '$tglsertifikatreg_new',
                '-',
                '$noregistrasikendaraan',
                '$tglsertifikatreg',
                '$norangka',
                '$nomesin',
                '$merek',
                '$tipe',
                '$jenis',
                '$sub_jenis',
                '$varian',
                '$sub_varian',
                '$thpembuatan',
                '$bahanbakar',
                '$isisilinder',
                '$dayamotorpenggerak',
                '$jbb',
                '$jbkb',
                '$jbi',
                '$jbki',
                '$mst',
                '$beratkosong',
                '$konfigurasisumburoda',
                '$ukuranban',
                '$panjangkendaraan',
                '$lebarkendaraan',
                '$tinggikendaraan',
                '$panjangbakatautangki',
                '$lebarbakatautangki',
                '$tinggibakatautangki',
                '$jumlahsumbu',
                '$julurdepan',
                '$julurbelakang',
                '$wheel_base',
                '$jaraksumbu1_2',
                '$jaraksumbu2_3',
                '$jaraksumbu3_4',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '$dayaangkutorang',
                '$dayaangkutbarang',
                '$kelasjalanterendah',
                '$masaberlakuuji',
                '$tgluji',
                '$statuslulusuji',
                '$kodewilayah',
                '$kodewilayahasal',
                $area_from_id,
                '$area_from_name',
                $vehicle_brand_id,
                $vehicle_type_id,
                $vehicle_type_sub_id,
                $vehicle_varian_type_id,
                $vehicle_varian_id,
                $fuel_id,
                $kelasjalan_id,
                $id_petugasuji_new,
                $id_kepaladinas_new,
                $id_direktur_new,
                decode('$dtHasilUji->img_depan','base64'),
                decode('$dtHasilUji->img_belakang','base64'),
                decode('$dtHasilUji->img_kanan','base64'),
                decode('$dtHasilUji->img_kiri','base64'),
                '$huv_nomordankondisirangka',
                '$huv_nomordantipemotorpenggerak',
                '$huv_kondisitangkicorongdanpipabahanbakar',
                '$huv_kondisiconverterkit',
                '$huv_kondisidanposisipipapembuangan',
                '$huv_ukurandankondisiban',
                '$huv_kondisisistemsuspensi',
                '$huv_kondisisistemremutama',
                '$huv_kondisipenutuplampudanalatpantulcahaya',
                '$huv_kondisipanelinstrumentdashboard',
                '$huv_kondisikacaspion',
                '$huv_kondisispakbor',
                '$huv_bentukbumper',
                '$huv_keberadaandankondisiperlengkapan',
                '$huv_rancanganteknis',
                '$huv_keberadaandankondisifasilitastanggapdaruratuntukmobilbus',
                '$huv_kondisibadankacaengseltempatdudukmbarangbakmuatantertutup',
                '$hum_kondisipenerusdaya',
                '$hum_sudutbebaskemudi',
                '$hum_kondisiremparkir',
                '$hum_fungsilampudanalatpantulcahaya',
                '$hum_fungsipenghapuskaca',
                '$hum_tingkatkegelapankaca',
                '$hum_fungsiklakson',
                '$hum_kondisidanfungsisabukkeselamatan',
                '$hum_ukurankendaraan',
                '$hum_ukurantempatdudukdanbagiandalamkendaraanuntukmobilbus',
                '$bsumbu1',
                '$bsumbu2',
                '$bsumbu3',
                '$bsumbu4',
                '$bsumbu5',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '$alatuji_emisiasapbahanbakarsolar',
                '$alatuji_emisicobahanbakarbensin',
                '$alatuji_emisihcbahanbakarbensin',
                '$alatuji_remparkirtangan',
                '$alatuji_remparkirkaki',
                '$alatuji_gayapengereman1kanan',
                '$alatuji_gayapengereman2kanan',
                '$alatuji_gayapengereman3kanan',
                '$alatuji_gayapengereman4kanan',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '$alatuji_gayapengereman1kiri',
                '$alatuji_gayapengereman2kiri',
                '$alatuji_gayapengereman3kiri',
                '$alatuji_gayapengereman4kiri',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '0',
                '$alatuji_gayapengeremanparkirkanan',
                '$alatuji_gayapengeremanparkirkiri',
                '$alatuji_remutamatotalgayapengereman',
                '$alatuji_remparkirtotalgayapengereman',
                '$alatuji_kincuprodadepan',
                '$alatuji_tingkatkebisingan',
                '$alatuji_lampuutamakekuatanpancarlampukanan',
                '$alatuji_lampuutamakekuatanpancarlampukiri',
                '$alatuji_lampuutamapenyimpanganlampukanan',
                '$alatuji_lampuutamapenyimpanganlampukiri',
                '$alatuji_penunjukkecepatan',
                '$alatuji_kedalamanalurban',
                '$alatuji_alatpemantulcahayatambahan_kuning',
                '$alatuji_alatpemantulcahayatambahan_putih',
                '$alatuji_alatpemantulcahayatambahan_merah')";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function actionCetakl($id, $posisi)
    {
        $this->layout = '//';
        $this->render('cetak_l', array('id' => $id, 'posisi' => $posisi));
    }

    public function actionDetailBukuUji()
    {
        $idRetribusi = $_POST['id'];
        $idKendaraan = TblRetribusi::model()->findByPk($idRetribusi)->id_kendaraan;
        $data_kend = VKendaraan::model()->findByAttributes(array('id_kendaraan' => $idKendaraan));
        $tblBuku = TblBuku::model()->findByAttributes(array('id_retribusi' => $idRetribusi));
        if (empty($tblBuku)) {
            $tgl_cetak = date("d", strtotime($tblBuku->tgl_cetak));
            $bulan_cetak = date("n", strtotime($tblBuku->tgl_cetak));
            $thn_cetak = date("Y", strtotime($tblBuku->tgl_cetak));
        } else {
            $tgl_cetak = date("d");
            $bulan_cetak = date("n");
            $thn_cetak = date("Y");
        }
        $bln_cetak = Yii::app()->params['bulanArrayInd'][$bulan_cetak - 1];

        $result['tanggal_cetak'] = $tgl_cetak . " " . $bln_cetak . " " . $thn_cetak;
        $result['no_uji'] = $data_kend->no_uji;
        $result['no_kendaraan'] = $data_kend->no_kendaraan;
        $result['nama_pemilik'] = $data_kend->nama_pemilik;
        $result['alamat'] = $data_kend->alamat;
        $result['identitas'] = $data_kend->identitas . " - " . $data_kend->no_identitas;
        $result['merk'] = '&nbsp;' . $data_kend->merk;
        $result['tipe'] = '&nbsp;' . $data_kend->tipe;
        $result['nm_komersil'] = '&nbsp;' . $data_kend->nm_komersil;
        $result['isi_silinder'] = '&nbsp;' . $data_kend->isi_silinder;
        $result['daya_motor'] = '&nbsp;' . $data_kend->daya_motor;
        $result['bahan_bakar'] = '&nbsp;' . $data_kend->bahan_bakar;
        $result['tahun'] = '&nbsp;' . $data_kend->tahun;
        $result['status_penggunaan'] = '&nbsp;' . ($data_kend->umum == 'true') ? 'UMUM' : 'TIDAK UMUM';
        $result['no_chasis'] = '&nbsp;' . $data_kend->no_chasis;
        $result['no_mesin'] = '&nbsp;' . $data_kend->no_mesin;
        $result['no_regis'] = '&nbsp;' . $data_kend->no_regis;
        if (date("d/m/Y", strtotime($data_kend->tgl_regis)) != '01/01/1970') {
            $tgl_sertifikasi = date("d", strtotime($data_kend->tgl_regis));
            $bulan_sertifikasi = date("n", strtotime($data_kend->tgl_regis));
            $thn_sertifikasi = date("Y", strtotime($data_kend->tgl_regis));
            $bln_sertifikasi = Yii::app()->params['bulanArrayInd'][$bulan_sertifikasi - 1];
            $date_sertification =  $tgl_sertifikasi . " " . $bln_sertifikasi . " " . $thn_sertifikasi;
        } else {
            $date_sertification = " ";
        }
        //        $tgl_sertifikasi = date("d", strtotime($data_kend->tgl_regis));
        //        $bulan_sertifikasi = date("n", strtotime($data_kend->tgl_regis));
        //        $thn_sertifikasi = date("Y", strtotime($data_kend->tgl_regis));
        //        $bln_sertifikasi = Yii::app()->params['bulanArrayInd'][$bulan_sertifikasi - 1];
        $result['tgl_sertifikasi'] = '&nbsp;' . $date_sertification;
        $result['petugas'] = date('d M Y H:i') . " , " . Yii::app()->session['username'];
        $result['ukuran_panjang'] = '&nbsp;' . $data_kend->ukuran_panjang;
        $result['ukuran_lebar'] = '&nbsp;' . $data_kend->ukuran_lebar;
        $result['ukuran_tinggi'] = '&nbsp;' . $data_kend->ukuran_tinggi;
        $result['bagian_belakang'] = '&nbsp;' . $data_kend->bagian_belakang;
        $result['bagian_depan'] = '&nbsp;' . $data_kend->bagian_depan;
        $result['jsumbu1'] = '&nbsp;' . $data_kend->jsumbu1;
        if ($data_kend->ukp != 0) {
            $result['ukq'] = '&nbsp;' . $data_kend->ukq . " / " . $data_kend->ukp;
        } else {
            $result['ukq'] = '&nbsp;' . $data_kend->ukq;
        }
        $result['dimpanjang'] = '&nbsp;' . $data_kend->dimpanjang;
        $result['dimlebar'] = '&nbsp;' . $data_kend->dimlebar;
        $result['dimtinggi'] = '&nbsp;' . $data_kend->dimtinggi;
        $result['karoseri_bahan'] = '&nbsp;' . $data_kend->karoseri_bahan;
        $result['psumbu1'] = '&nbsp;' . $data_kend->psumbu1;
        $result['psumbu2'] = '&nbsp;' . $data_kend->psumbu2;
        $result['kemjbb'] = '&nbsp;' . $data_kend->kemjbb;
        $result['bsumbu1'] = '&nbsp;' . $data_kend->bsumbu1;
        $result['bsumbu2'] = '&nbsp;' . $data_kend->bsumbu2;
        $result['bsumbu3'] = '&nbsp;' . $data_kend->bsumbu3;
        $result['bsumbu4'] = '&nbsp;' . $data_kend->bsumbu4;
        $jumlah_sumbu = $data_kend->bsumbu1 + $data_kend->bsumbu2 + $data_kend->bsumbu3 + $data_kend->bsumbu4;
        $jbi = intval($jumlah_sumbu) + intval($data_kend->kemorang) + intval($data_kend->kembarang);
        $result['jumlah_sumbu'] = '&nbsp;' . $jumlah_sumbu;
        $result['karoseri_duduk'] = '&nbsp;' . $data_kend->karoseri_duduk . " ( " . $data_kend->kemorang . " )";
        $result['kembarang'] = '&nbsp;' . $data_kend->kembarang;
        $result['jbi'] = '&nbsp;' . $jbi;
        $result['mst'] = '&nbsp;' . $data_kend->mst;
        echo json_encode($result);
    }

    public function actionRekapBukuUji($tgl)
    {
        $tglIndonesia = date("d", strtotime($tgl)) . " " . strtoupper(Yii::app()->params['bulanArrayInd'][date("n", strtotime($tgl)) - 1]) . " " . date("Y", strtotime($tgl));

        $xls = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $xls->getActiveSheet();
        $xls->setActiveSheetIndex(0);
        //======================================================================
        $styleBorder = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                )
            )
        );
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
        //======================================================================
        //HEADER
        $sheet->mergeCells("A1:G1");
        $sheet->setCellValue("A1", "LAPORAN PEMAKAIAN BUKU UJI");
        $sheet->getStyle("A1")->getFont()->setSize(20);
        $sheet->getStyle("A1")->applyFromArray($styleTengah);

        $sheet->mergeCells("A2:G2");
        $sheet->setCellValue("A2", "PKB KABUPATEN KOTAWARINGIN TIMUR");
        $sheet->getStyle("A2")->getFont()->setSize(20);
        $sheet->getStyle("A2")->applyFromArray($styleTengah);

        $sheet->mergeCells("A3:G3");
        $sheet->setCellValue("A3", $tglIndonesia);
        $sheet->setCellValue("A3", $tglIndonesia);
        $sheet->getStyle("A3")->getFont()->setSize(14);
        $sheet->getStyle("A3")->applyFromArray($styleTengah);

        $sheet->setCellValue("A5", "NO");
        $sheet->getStyle("A5")->applyFromArray($styleTengah);
        $sheet->getStyle("A")->applyFromArray($styleTengah);
        $sheet->getColumnDimension("A")->setAutoSize(true);

        $sheet->setCellValue("B5", "NO SERI");
        $sheet->getStyle("B5")->applyFromArray($styleTengah);
        $sheet->getStyle("B")->applyFromArray($styleTengahHorizontal);
        $sheet->getColumnDimension("B")->setWidth(15);

        $sheet->setCellValue("C5", "NAMA PEMILIK");
        $sheet->getStyle("C5")->applyFromArray($styleTengah);
        $sheet->getStyle("C")->applyFromArray($styleTengahHorizontal);
        $sheet->getStyle("C")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension("C")->setWidth(30);

        $sheet->setCellValue("D5", "NO KEND");
        $sheet->getStyle("D5")->applyFromArray($styleTengah);
        $sheet->getStyle("D")->applyFromArray($styleTengahHorizontal);
        $sheet->getStyle("D5")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension("D")->setWidth(14);

        $sheet->setCellValue("E5", "NO UJI");
        $sheet->getStyle("E5")->applyFromArray($styleTengah);
        $sheet->getStyle("E")->applyFromArray($styleTengahHorizontal);
        $sheet->getStyle("E5")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension("E")->setWidth(14);

        $sheet->setCellValue("F5", "LAYANAN");
        $sheet->getStyle("F5")->applyFromArray($styleTengah);
        $sheet->getStyle("F")->applyFromArray($styleTengahHorizontal);
        $sheet->getStyle("F5")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension("F")->setWidth(18);

        $sheet->setCellValue("G5", "JENIS KENDARAAN");
        $sheet->getStyle("G5")->applyFromArray($styleTengah);
        $sheet->getStyle("G")->applyFromArray($styleTengahHorizontal);
        $sheet->getStyle("G5")->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension("G")->setWidth(18);

        $sheet->getStyle('A5:G5')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('b3c6cf');
        $sheet->getRowDimension(5)->setRowHeight(29);
        //END HEADER
        //======================================================================

        $criteria = new CDbCriteria();
        $criteria->addCondition("tgl_retribusi = TO_DATE('" . $tgl . "', 'DD-Mon-YY')");
        //        $criteria->addCondition("tgl_retribusi = 'now' ::text::date");
        $criteria->addCondition("cetak = TRUE");
        $result = VGantibuku::model()->findAll($criteria);
        //======================================================================
        //BODY
        $no = 1;
        $baris = 6;
        foreach ($result as $data) :
            $sheet->setCellValue("A" . $baris, $no);
            $sheet->setCellValue("B" . $baris, $data->no_seri);
            $sheet->setCellValue("C" . $baris, $data->nama_pemilik);
            $sheet->setCellValue("D" . $baris, $data->no_kendaraan);
            $sheet->setCellValue("E" . $baris, $data->no_uji);
            $sheet->setCellValue("F" . $baris, $data->nm_uji);
            $sheet->setCellValue("G" . $baris, $data->jenis);
            $sheet->getRowDimension($baris)->setRowHeight(29);
            $baris++;
            $no++;
        endforeach;
        //END BODY
        //======================================================================
        $baris_border = $baris - 1;
        $sheet->getStyle("A5:G" . $baris_border)->applyFromArray($styleBorder);
        //======================================================================
        ob_clean();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="PEMAKAIAN_BUKU_UJI_' . $tglIndonesia . '.xlsx"');
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
