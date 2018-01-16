<?php
namespace app\common\components;

use yii\base\Component;
use yii\base\Exception;
use Yii;
use yii\log\Logger;

/**
 * 类
 *
 * 功能1：
 * 功能2：
 *
 * @author luoning<lniftt@163.com>
 */
class Fdfs extends Component
{
    public $fdfs, $tracker, $storage;
    public $server;
    public $baseUrl = "http://127.0.0.1/";

    public function init()
    {


    }

    public function reset() {
        $this->fdfs = null;
        $this->storage = null;
        $this->tracker = null;
        $this->server = null;
    }

    /**
     * @param $localfile
     * @param $ext_name
     * @param string $groupName
     * @return array|bool
     */
    public function upload($localfile, $ext_name, $groupName = 'group1')
    {
        try {
            $this->fdfs = new \FastDFS();
            // get a connected tracker server
            $this->tracker = $this->fdfs->tracker_get_connection();
            if (!$this->tracker) {
                throw new Exception('cannot connect to tracker server:[' .
                    $this->fdfs->get_last_error_no() . '] ' .
                    $this->fdfs->get_last_error_info());
            }
            // get the storage server info and connect to it
            $this->storage = $this->fdfs->tracker_query_storage_store($groupName, $this->tracker);
            $this->server = $this->fdfs->connect_server(
                $this->storage['ip_addr'], $this->storage['port']);
            if ($this->server === false) {
                throw new Exception('cannot connect to storage server' .
                    $this->storage['ip_addr'] . ':' .
                    $this->storage['port'] . ' :[' .
                    $this->fdfs->get_last_error_info());
            }
            $this->storage['sock'] = $this->server['sock'];
            $info = $this->fdfs->storage_upload_by_filename($localfile, $ext_name,
                [], $groupName, $this->tracker, $this->storage);
            if (is_array($info)) {
                $this->fdfs->tracker_close_all_connections();
                $this->reset();
                return [
                    'url' => $this->baseUrl . $info['group_name'] . '/' . $info['filename'],
                    'path' => $this->baseUrl . $info['group_name'] . '/' . $info['filename'],
                ];
            }
            $this->fdfs->tracker_close_all_connections();
            $this->reset();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            $this->fdfs->tracker_close_all_connections();
            $this->reset();
        }
        return false;
    }

    public function download_to_file($groupName, $remote_filename, $dst_localfile)
    {
        try {
            $this->fdfs = new \FastDFS();
            // get a connected tracker server
            $this->tracker = $this->fdfs->tracker_get_connection();
            if (!$this->tracker) {
                throw new Exception('cannot connect to tracker server:[' .
                    $this->fdfs->get_last_error_no() . '] ' .
                    $this->fdfs->get_last_error_info());
            }
            // get the storage server info and connect to it
            $this->storage = $this->fdfs->tracker_query_storage_store($groupName, $this->tracker);
            $this->server = $this->fdfs->connect_server(
                $this->storage['ip_addr'], $this->storage['port']);
            if ($this->server === false) {
                throw new Exception('cannot connect to storage server' .
                    $this->storage['ip_addr'] . ':' .
                    $this->storage['port'] . ' :[' .
                    $this->fdfs->get_last_error_info());
            }
            $this->storage['sock'] = $this->server['sock'];
            $result = $this->fdfs->storage_download_file_to_file($groupName,
                $remote_filename, $dst_localfile);
            $this->fdfs->tracker_close_all_connections();
            $this->reset();
        } catch(Exception $e) {
            Yii::getLogger()->log($e->getMessage(), Logger::LEVEL_ERROR);
            $this->fdfs->tracker_close_all_connections();
            $this->reset();
            return false;
        }
        return $result;
    }

    public function download_to_buff($group_name, $remote_filename)
    {
        $content = $this->fdfs->storage_download_file_to_buff(
            $group_name, $remote_filename);

        return $content;
    }

    public function delete($group_name, $remote_filename)
    {
        return $this->fdfs->storage_delete_file($group_name, $remote_filename);
    }

    public function exists($group_name, $remote_filename)
    {
        return $this->fdfs->storage_file_exist($group_name, $remote_filename);
    }

    public function get_file_info($group_name, $remote_filename)
    {
        return $this->fdfs->get_file_info($group_name, $remote_filename);
    }
}