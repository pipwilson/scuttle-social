<?php
   /***************************************************************/
   /* XmlParser - part of the PhpDelicious library
   
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
   
      Last Updated:  20th January 2008                            */
   /***************************************************************/
   
   class XmlParser {
      protected $aXmlResult;
      
      public function Parse($sXml) {
         $this->aXmlResult = array();
         $oParser = xml_parser_create();
         xml_set_object($oParser, $this);
         xml_set_element_handler($oParser, "StartTag", "CloseTag");   
         xml_set_character_data_handler($oParser, "TagContent");
         if (!xml_parse($oParser, $sXml)) {
            return false;    
         }            
         xml_parser_free($oParser); 
         return $this->aXmlResult[0];
      }
   
      protected function StartTag($oParser, $sName, $sattributes) {
         $sTag = array("name" => $sName, "attributes" => $sattributes);
         array_push($this->aXmlResult, $sTag);
      }
  
      protected function TagContent($oParser, $sTagData) {
         if (trim($sTagData)) {
            if (isset($this->aXmlResult[count($this->aXmlResult) - 1]['content'])) {
               $this->aXmlResult[count($this->aXmlResult) - 1]['content'] .= $sTagData;
            } else {
               $this->aXmlResult[count($this->aXmlResult) - 1]['content'] = $sTagData;
            }
         }
      }
  
      protected function CloseTag($parser, $name) {
         $this->aXmlResult[count($this->aXmlResult) - 2]['items'][] = $this->aXmlResult[count($this->aXmlResult) - 1];
         array_pop($this->aXmlResult);
      }
   }
?>