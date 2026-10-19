<?php
class DefaultController extends Controller
{
    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionSaveCaptures()
    {
        // Preserve the existing response key consumed by the capture screen.
        $data = array('img_belakang' => CameraSnapshot::capture('CAMERA_URL'));
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
