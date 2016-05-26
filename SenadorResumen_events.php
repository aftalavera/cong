<?php

//DEL  // -------------------------
//DEL  	CCGetQueryString("QueryString","provincia");
//DEL  // -------------------------

//Page_BeforeInitialize @1-AD01F4C1
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $SenadorResumen; //Compatibility
//End Page_BeforeInitialize

//FlashChart1 Initialization @104-B3E0D7AF
    if ('SenadorResumenFlashChart1' == CCGetParam('callbackControl')) {
        global $CCSLocales;
        $Service = new Service();
        $formatter = new TemplateFormatter();
        $formatter->SetTemplate(file_get_contents(RelativePath . "/" . "SenadorResumenFlashChart1.xml"));
        $Service->SetFormatter($formatter);
//End FlashChart1 Initialization

//FlashChart1 DataSource @104-3603A4A8
        $Service->DataSource = new clsDBmssql();
        $Service->ds = & $Service->DataSource;
        list($FlashChart1->BoundColumn, $FlashChart1->TextColumn, $FlashChart1->DBFormat) = array("", "", "");
        $Service->DataSource->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $Service->DataSource->ErrorBlock);
        $Service->DataSource->SQL = "election.dbo.spSenadoresScore " . ";";
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

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize


?>
