<?php
/**
 * Upload form
 * @author Athellow
 * @version 1.0
 */
namespace common\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use common\components\Model;

class UploadForm extends Model
{
    /** @var UploadedFile */
    public $file;

    /** @var string path */
    public $path;

    /** @var string 存储文件名 */
    public $filename;
    
    /** @var string 存储路径（相对） */
    public $saveFolder;

    /** @var string 存储缩略路径（相对） */
    public $saveThumbFolder;

    /** @var array 存储驱动配置 */
    protected $drive;

    /** @var array 支持的图片格式 */
    protected $imageExt = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

    /** @var array 支持的文件格式 */
    protected $docExt = ['txt', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'csv', 'pdf', 'md'];

    /** @var array 支持的视频格式 */
    protected $videoExt = ['mp4', 'ogg'];

    /** @var array 支持的音频格式 */
    protected $audioExt = ['mp3'];

    /** @var array 支持的应用格式 */
    protected $appExt = ['apk'];
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['file', 'required'],
            ['file', 'file'],
            ['file', 'validateExt'],
            ['type', 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'file' => '上传文件',
        ];
    }

    /**
     * 校验文件扩展类型
     * 
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateExt($attribute, $params)
    {
        $supportExt = $this->getSupportExt();

        if (!in_array($this->file->extension, $supportExt)) {
            $this->addError($attribute, '不支持的文件类型：'. $this->file->extension);
        }
    }

    /**
     * 获取支持类型
     * @return array
     */
    public function getSupportExt()
    {
        return array_merge($this->imageExt, $this->docExt, $this->videoExt, $this->audioExt, $this->appExt);
    }

    /**
     * 根据上传类型返回存储路径名
     * @return string
     * @throws Exception
     */
    public function getUploadPath()
    {
        $ext = $this->file->extension;

        switch ($ext) {
            case in_array($ext, $this->imageExt):
                $path = 'images';
                break;
            case in_array($ext, $this->docExt):
                $path = 'documents';
                break;
            case in_array($ext, $this->videoExt):
                $path = 'videos';
                break;
            case in_array($ext, $this->audioExt):
                $path = 'audioes';
                break;
            case in_array($ext, $this->appExt):
                $path = 'apk';
                break;
            default:
                throw new \Exception('不支持的上传类型 '. $ext);
        }
        
        return $path;
    }

    /**
     * 保存文件
     * @param string $folder 存储子目录
     * @param string $type 驱动类型
     * @return bool
     */
    public function upload($folder = '', $type = null)
    {
        if (!$this->validate()) {
            return false;
        }
        
        $savePath = $this->getUploadPath();     // 文件类型目录, 如`images`, e.g.相对路径为`/uploads/images`
        $folder = $folder ?: date('Ymd');       // 存储子目录, 如`20230601`, e.g.相对路径为`/uploads/images/20230601`

        // 存储配置
        $storage = Yii::$app->params['storage'];
        // 驱动类型 默认 local
        $type = ($type === null) ? ($storage['type'] ?? 'local') : $type;
        // 存储驱动配置
        $drive = $storage['drives'][$type] ?? [];

        if (empty($drive)) {
            $this->addError('file', '存储驱动未配置');
            return false;
        }

        $basePath = $drive['basePath'] ?? Yii::getAlias('@frontend') . '/web';  // web站点路径
        $uploadFolder = $drive['uploadFolder'] ?? '/uploads';                   // 上传文件目录, e.g.  相对路径为`/uploads`
        $uploadThumbFolder = $drive['uploadThumbFolder'] ?? '/uploads/thumbs';  // 缩略图上传目录, e.g.相对路径为`/uploads/thumbs`

        $this->drive = $drive;
        $this->saveFolder = $uploadFolder . '/' . $savePath . '/' . $folder;            // `/uploads/images/20230601`
        $this->saveThumbFolder = $uploadThumbFolder . '/' . $savePath . '/' . $folder;  // `/uploads/thumbs/images/20230601`
        if ($savePath == 'apk') {
            $this->filename = $this->file->getBaseName() . '.' . $this->file->getExtension();
        } else {
            $this->filename = md5_file($this->file->tempName) . '.' . $this->file->getExtension();
        }
        
        $fullPath = $basePath . $this->saveFolder;
        $fullThumbPath = $basePath . $this->saveThumbFolder;
        $fullFile = $fullPath . '/' . $this->filename;
        $fullThumbFile = $fullThumbPath . '/' . $this->filename;
        
        if (!is_dir($fullPath)) {
            if (!FileHelper::createDirectory($fullPath)) {
                $this->addError('file', '上传失败，无法创建文件夹`'. $fullPath .'`');
                return false;
            }
        }
        
        // if (!is_dir($fullThumbPath)) {
        //     if (!FileHelper::createDirectory($fullThumbPath)) {
        //         $this->addError('file', '上传失败，无法创建文件夹`'. $fullThumbPath .'`');
        //         return false;
        //     }
        // }
        
        if ($this->file->saveAs($fullFile)) {
            $this->path = $this->saveFolder . '/' . $this->filename;

            // try {
            //     $editor = Grafika::createEditor();
            //     /** @var ImageInterface $image */
            //     $editor->open($image, $fullFile);
            //     $editor->resizeFit($image, 200, 200);
            //     $editor->save($image, $fullThumbFile);
            //     $thumbUrl = $domain . $this->saveThumbFolder . '/' . $this->filename;
            // } catch (\Exception $e) {
            //     $thumbUrl = '';
            // }
        } else {
            $this->addError('file', '文件保存失败`'. $fullFile .'`');
            return false;
        }

        return true;
    }

}