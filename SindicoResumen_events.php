<?php
//Page_BeforeInitialize @1-512D5FF4
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $SindicoResumen; //Compatibility
//End Page_BeforeInitialize

//FlashChart1 Initialization @104-FFD14489
    if ('SindicoResumenFlashChart1' == CCGetParam('callbackControl')) {
        global $CCSLocales;
        $Service = new Service();
        $formatter = new TemplateFormatter();
        $formatter->SetTemplate(file_get_contents(RelativePath . "/" . "SindicoResumenFlashChart1.xml"));
        $Service->SetFormatter($formatter);
//End FlashChart1 Initialization

//FlashChart1 DataSource @104-C8C6F105
        $Service->DataSource = new clsDBmssql();
        $Service->ds = & $Service->DataSource;
        list($FlashChart1->BoundColumn, $FlashChart1->TextColumn, $FlashChart1->DBFormat) = array("", "", "");
        $Service->DataSource->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $Service->DataSource->ErrorBlock);
        $Service->DataSource->SQL = "EXEC spSindicosScore " . ";";
        $Service->DataSource->PageSize = 25;
        $Service->SetDataSourceQuery($Service->DataSource->OptimizeSQL(CCBuildSQL($Service->DataSource->SQL, $Service->DataSource->Where, $Service->DataSource->Order)));
//End FlashChart1 DataSource

//FlashChart1 Execution @104-E3C538D6
        $Service->AddDataSetValue("Title", "");
        $Service->AddHttpHeader("Expires", "Mon, 26 Jul 1997 05:00:00 GMT");
        $Service->AddHttpHeader("Last-Modified", gmdate("D, d M Y H:i:s") . " GMT");
        $Service->AddHttpHeader("Cache-Control", "no-cache, must-revalidate");
        $Service->AddHttpHeader("Pragma", "no-cache");
        $Service->AddHttpHeader("Content-type", "text/xml");
        $Service->DisplayHeaders();
        echo $Service->Execute();
//End FlashChart1 Execution

//FlashChart1 Tail @104-27890EF8
        exit;
    }
//End FlashChart1 Tail

//FlashChart2 Initialization @237-61EAE993
    if ('SindicoResumenFlashChart2' == CCGetParam('callbackControl')) {
        global $CCSLocales;
        $Service = new Service();
        $formatter = new TemplateFormatter();
        $formatter->SetTemplate(file_get_contents(RelativePath . "/" . "SindicoResumenFlashChart2.xml"));
        $Service->SetFormatter($formatter);
//End FlashChart2 Initialization

//FlashChart2 DataSource @237-5491465F
        $Service->DataSource = new clsDBmssql();
        $Service->ds = & $Service->DataSource;
        list($FlashChart2->BoundColumn, $FlashChart2->TextColumn, $FlashChart2->DBFormat) = array("", "", "");
        $Service->DataSource->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $Service->DataSource->ErrorBlock);
        $Service->DataSource->SQL = "EXEC spSindicosScore " . ";";
        $Service->DataSource->PageSize = 25;
        $Service->SetDataSourceQuery($Service->DataSource->OptimizeSQL(CCBuildSQL($Service->DataSource->SQL, $Service->DataSource->Where, $Service->DataSource->Order)));
//End FlashChart2 DataSource

//FlashChart2 Execution @237-E3C538D6
        $Service->AddDataSetValue("Title", "");
        $Service->AddHttpHeader("Expires", "Mon, 26 Jul 1997 05:00:00 GMT");
        $Service->AddHttpHeader("Last-Modified", gmdate("D, d M Y H:i:s") . " GMT");
        $Service->AddHttpHeader("Cache-Control", "no-cache, must-revalidate");
        $Service->AddHttpHeader("Pragma", "no-cache");
        $Service->AddHttpHeader("Content-type", "text/xml");
        $Service->DisplayHeaders();
        echo $Service->Execute();
//End FlashChart2 Execution

//FlashChart2 Tail @237-27890EF8
        exit;
    }
//End FlashChart2 Tail

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize


?>
