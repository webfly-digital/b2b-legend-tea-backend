<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die;
}
$arComponentDescription = array("NAME" => GetMessage("BXMAKER.AUTHUSERPHONE.COMPONENT.CALL.DESCRIPTION.NAME"), "DESCRIPTION" => GetMessage("BXMAKER.AUTHUSERPHONE.COMPONENT.CALL.DESCRIPTION.TEXT"), "ICON" => "", "PATH" => array("ID" => 'bxmaker', "NAME" => GetMessage("BXMAKER.AUTHUSERPHONE.COMPONENT.CALL.DESCRIPTION.DEVELOPER"), "CHILD" => array("ID" => "bxmaker_authuserphone", "NAME" => GetMessage("BXMAKER.AUTHUSERPHONE.COMPONENT.CALL.DESCRIPTION.GROUP"))));