<?php
Class fileHelper
{
    protected $aAllowedMimes = array("application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","image/png","video/3gpp","application/x-7z-compressed","application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.ms-excel","application/vnd.ms-excel","image/jpeg","image/x-ms-bmp","image/vnd.adobe.photoshop","application/zip","application/x-bzip","application/x-bzip2","application/x-rar-compressed","image/gif","image/x-icon","image/x-portable-bitmap","image/tiff","text/rtf","text/richtext","text/plain");
    protected $iMaxFileSize = 104857600; //100mb
    protected $sUploadDir;

    public function __construct()
    {
        $this->sUploadDir ="uploads/";
    }
    public function checkAllowedMime($sFileType){
        return in_array($sFileType,$this->aAllowedMimes);
    }
    public function checkAllowedFileSize($iFileSize){
        return $iFileSize < $this->iMaxFileSize ? TRUE : FALSE;
    }

    public function uploadFiles($aFiles,$iPostId){
        $iFileCount = count($aFiles['name']);
        for($i=0;$i<$iFileCount; $i++)
            $this->_saveFile($aFiles['name'][$i],$aFiles['type'][$i],$aFiles['tmp_name'][$i],$iPostId);
        return true;
    }

    private function _saveFile($sName, $sType, $sTmpName,$iPostId){
        while(file_exists($this->sUploadDir.$sName)){
            $sName = $this->_newFileName($sName);
        }
        move_uploaded_file($sTmpName,$this->sUploadDir.$sName);
        $oFile = new TblFiles();
        $oFile->file_path = $this->sUploadDir.$sName;
        $oFile->post_id = $iPostId;
        $oFile->type = $sType;
        $oFile->save();
    }
    private function _newFileName($sName){
        $arr = explode('.',$sName);
        $sName = "";
        for($i = 0; $i < count($arr)-1; $i++)
            $sName .=$arr[$i];
        return $sName.= mt_rand(0, 999999).'.'.$arr[count($arr)-1];
    }
}