<?php
defined('_MRKEN_CMS') or die('Restricted access');

set_time_limit(0);

class imgur
{
    private $_client_id = IMGUR_CLIENT_ID;
    private $_album = array('id' => IMGUR_ALBUM_ID, 'deletehash' => IMGUR_ALBUM_DELETEHASH);

    public $uploaded = false;
    public $deleted = false;
    public $error;
    public $data;
    function __construct()
    {
    }

    public function upload($file, $type = 'url')
    {
        if ($type !== 'url' && $file['size'] > 4194304) {
            $this->error = 'Kích thước tập tin tối đa là 4096 KB (4MB)';
            return false;
        }
        if ($type == 'base64') {
            $file = $this->_fileBase64($file);
        } elseif ($type == 'file') {
            $file = $this->_fileBinary($file);
        } else {
            $type = 'url';
        }

        $pvars = array(
            'type'  => $type,
            'image'  => $file,
            'album' => $this->_album['deletehash'] ?: $this->_album['id']
        );
        $this->_upload($pvars);
    }

    private function _fileBase64($file)
    {
        $data = $this->_fileBinary($file);
        return base64_encode($data);
    }

    private function _fileBinary($file)
    {
        $handle  = fopen($file['tmp_name'], 'rb');
        $data    = fread($handle, filesize($file['tmp_name']));
        fclose($handle);
        return $data;
    }

    public function delete($hash)
    {
        if (empty($hash)) {
            return false;
        }

        $this->deleted = false;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image/' . $hash);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $this->_client_id));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $out = curl_exec($curl);
        curl_close ($curl);
        $response = json_decode($out, true);
        if ($response['status'] == 200 && $response['success'] == true) {
            $this->deleted = true;
        }
    }

    private function _upload($pvars)
    {
        $this->uploaded = false;
        $this->data = array();
        $curl    = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $this->_client_id));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $out = curl_exec($curl);
        if (curl_errno($curl)) {
            $this->error = curl_error($curl);
        } else {
            $response = json_decode($out, true);
            if ($response['status'] == 200 && $response['success'] == true) {
                $this->uploaded = true;
                $this->data = $response['data'];
            } else {
                if (is_array($response['data']['error'])) {
                    $this->error = $response['data']['error']['message'];
                } else {
                    $this->error = $response['data']['error'];
                }
            }
        }
        curl_close($curl);
    }
}
