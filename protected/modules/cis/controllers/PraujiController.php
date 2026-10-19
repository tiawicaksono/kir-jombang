<?php

class PraujiController extends Controller {

    public $layout = '//layouts/main_top';

    public function filters() {
        return array(
            'Rights', // perform access control for CRUD operations
        );
    }

    public function actionListGrid() {
        $kategori = $_POST['textCategory'];
        $search = $_POST['textSearch'];
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'numerator';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->order = "$sort $order";
        $criteria->limit = $rows;
        $criteria->offset = $offset;
        if (!empty($search)) {
            $criteria->addCondition("(replace(LOWER(no_uji),' ','') like replace(LOWER('%" . $search . "%'),' ','') OR replace(LOWER(no_kendaraan),' ','') like replace(LOWER('%" . $search . "%'),' ',''))");
        }
        $result = VCisPrauji::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $p) {
            $dataJson[] = array(
                "id_kendaraan" => $p->id_kendaraan,
                "id_hasil_uji" => $p->id_hasil_uji,
                "no_antrian" => $p->no_antrian,
                "no_uji" => $p->no_uji,
                "no_kendaraan" => $p->no_kendaraan,
                "no_chasis" => $p->no_chasis,
                "no_mesin" => $p->no_mesin,
                "merk" => $p->merk,
                "tipe" => $p->tipe,
                "tahun" => $p->tahun,
                "bahan_bakar" => $p->bahan_bakar,
                "nm_komersil" => $p->nm_komersil,
                "karoseri_bahan" => $p->karoseri_bahan,
                "karoseri_jenis" => $p->karoseri_jenis,
                "karoseri_jenis" => $p->karoseri_jenis,
                "karoseri_jenis" => $p->karoseri_jenis,
                "nm_uji" => $p->nm_uji,
                "numerator" => $p->numerator,
                "numerator_hari" => $p->numerator_hari
            );
        }
        header('Content-Type: application/json');
        echo CJSON::encode(
                array(
                    'total' => VCisPrauji::model()->count($criteria),
                    'rows' => $dataJson,
                )
        );
        Yii::app()->end();
    }

    public function actionProses() {
        $idhasil = $_POST['idhasil'];
        $variabel = $_POST['variabel'];
        $posisi = $_POST['posisi'];
        $username = $_POST['username'];
        $query = "select update_cis_prauji('$variabel',$idhasil,'$username');";
        Yii::app()->db->createCommand($query)->execute();
    }

    public function actionLoadImage() {
        $id_kendaraan = $_POST['idkendaraan'];
        $query = "select img1,img2 from tbl_hasil_uji where id_kendaraan=$id_kendaraan order by jdatang desc limit 1 offset 1";
        $result = Yii::app()->db->createCommand($query)->queryRow();

        $data['image1'] = $result['img1'];
        $data['image2'] = $result['img2'];
        echo json_encode($data);
    }

    public function actionLainListGrid() {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 20;
        $offset = ($page - 1) * $rows;

        $criteria = new CDbCriteria();
        $criteria->limit = $rows;
        $criteria->offset = $offset;
        $criteria->addCondition("kd_lulus like ('LAIN%')");
        $result = TblKelulusan::model()->findAll($criteria);
        $dataJson = array();

        foreach ($result as $p) {
            $dataJson[] = array(
                "kd_lulus" => $p->kd_lulus,
                "kelulusan" => $p->kelulusan,
            );
        }
        header('Content-Type: application/json');
        echo CJSON::encode(
                array(
                    'total' => TblKelulusan::model()->count($criteria),
                    'rows' => $dataJson,
                )
        );
        Yii::app()->end();
    }

    public function actionSaveCapture() {
        $idhasil = $_POST['id_hasil_uji'];

        $img_depan = CameraSnapshot::capture('CAMERA_FRONT_URL');
        $img_belakang = CameraSnapshot::capture('CAMERA_REAR_URL');
        $img_kanan = CameraSnapshot::capture('CAMERA_RIGHT_URL');
        $img_kiri = CameraSnapshot::capture('CAMERA_LEFT_URL');

        $proses = "UPDATE tbl_hasil_uji set img_depan='" . $img_depan . "',img_belakang='" . $img_belakang . "',img_kiri='" . $img_kiri . "',img_kanan='" . $img_kanan . "' where id_hasil_uji=" . $idhasil;
        Yii::app()->db->createCommand($proses)->execute();

        $data['img_depan'] = $img_depan;
        $data['img_belakang'] = $img_belakang;
        $data['img_kanan'] = $img_kanan;
        $data['img_kiri'] = $img_kiri;
        echo json_encode($data);
    }

}
