<?php
   /***************************************************************/
   /* Cache - part of the PhpDelicious library
   
      Software License Agreement (BSD License)
   
      Copyright (C) 2005-2008, Edward Eliot.
      All rights reserved.
      
      Redistribution and use in source and binary forms, with or without
      modification, are permitted provided that the following conditions are met:

         * Redistributions of source code must retain the above copyright
           notice, this list of conditions and the following disclaimer.
         * Redistributions in binary form must reproduce the above copyright
           notice, this list of conditions and the following disclaimer in the
           documentation and/or other materials provided with the distribution.
         * Neither the name of Edward Eliot nor the names of its contributors 
           may be used to endorse or promote products derived from this software 
           without specific prior written permission of Edward Eliot.

      THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER AND CONTRIBUTORS "AS IS" AND ANY
      EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
      WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
      DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY
      DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
      (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
      LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
      ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
      (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
      SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
   
      Last Updated:  7th April 2008                               */
   /***************************************************************/
   
   class Cache {
      protected $sFile;
      protected $sFileLock;
      protected $iCacheTime;
      
      protected $sShortKey;
      protected static $aCache = array();
      
      public function __construct($sKey, $iCacheTime, $sPrefix='', $sCachePath = CACHE_PATH) {
         $this->sShortKey = $sPrefix.md5($sKey);
         $this->sFile = "$sCachePath$this->sShortKey.txt";
         $this->sFileLock = "$this->sFile.lock";
         $this->iCacheTime = $iCacheTime;
      }
      
      public function Check() {
         if (array_key_exists($this->sShortKey, self::$aCache) || file_exists($this->sFileLock)) {
           return true;
         }
         return (file_exists($this->sFile) && ($this->iCacheTime == -1 || time() - filemtime($this->sFile) <= $this->iCacheTime));
      }
      
      public function Exists() {
         return (array_key_exists($this->sShortKey, self::$aCache)) || (file_exists($this->sFile) || file_exists($this->sFileLock));
      }
      
      public function Set($vContents) {
         if (!file_exists($this->sFileLock)) {
            if (file_exists($this->sFile)) {
               copy($this->sFile, $this->sFileLock);
            }
            $oFile = fopen($this->sFile, 'w');
            fwrite($oFile, serialize($vContents));
            fclose($oFile);
            if (file_exists($this->sFileLock)) {
               unlink($this->sFileLock);
            }
            self::$aCache[$this->sShortKey] = $vContents;
            return true;
         }     
         return false;
      }
      
      public function Get() {
         if (array_key_exists($this->sShortKey, self::$aCache)) {
            return self::$aCache[$this->sShortKey];
         } else if (file_exists($this->sFileLock)) {
            self::$aCache[$this->sShortKey] = unserialize(file_get_contents($this->sFileLock));
            return self::$aCache[$this->sShortKey];
         } else {
            self::$aCache[$this->sShortKey] = unserialize(file_get_contents($this->sFile));
            return self::$aCache[$this->sShortKey];
         }
      }
      
      public function ReValidate() {
         touch($this->sFile);
      }
   }
?>