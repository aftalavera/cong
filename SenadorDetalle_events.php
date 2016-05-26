<?php
//Page_BeforeInitialize @1-1C1E4A7C
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $SenadorDetalle; //Compatibility
//End Page_BeforeInitialize

//FlashChart1 Initialization @136-6F5FEA5D
    if ('SenadorDetalleFlashChart1' == CCGetParam('callbackControl')) {
        global $CCSLocales;
        $Service = new Service();
        $formatter = new TemplateFormatter();
        $formatter->SetTemplate(file_get_contents(RelativePath . "/" . "SenadorDetalleFlashChart1.xml"));
        $Service->SetFormatter($formatter);
//End FlashChart1 Initialization

//FlashChart1 DataSource @136-2B917936
        $Service->DataSource = new clsDBmssql();
        $Service->ds = & $Service->DataSource;
        list($FlashChart1->BoundColumn, $FlashChart1->TextColumn, $FlashChart1->DBFormat) = array("", "", "");
        $Service->DataSource->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $Service->DataSource->ErrorBlock);
        $Service->DataSource->cp["provincia"] = new clsSQLParameter("urlprovincia", ccsInteger, "", "", CCGetFromGet("provincia", NULL), 1, false, $Service->DataSource->ErrorBlock);
        $Service->DataSource->SQL = "EXEC spSenadoresDetalleGrafica " . $Service->DataSource->ToSQL($Service->DataSource->cp["provincia"]->GetDBValue(), $Service->DataSource->cp["provincia"]->DataType) . ";";
        $Service->DataSource->PageSize = 25;
        $Service->SetDataSourceQuery($Service->DataSource->OptimizeSQL(CCBuildSQL($Service->DataSource->SQL, $Service->DataSource->Where, $Service->DataSource->Order)));
//End FlashChart1 DataSource

//FlashChart1 Execution @136-E3C538D6
        $Service->AddDataSetValue("Title", "");
        $Service->AddHttpHeader("Expires", "Mon, 26 Jul 1997 05:00:00 GMT");
        $Service->AddHttpHeader("Last-Modified", gmdate("D, d M Y H:i:s") . " GMT");
        $Service->AddHttpHeader("Cache-Control", "no-cache, must-revalidate");
        $Service->AddHttpHeader("Pragma", "no-cache");
        $Service->AddHttpHeader("Content-type", "text/xml");
        $Service->DisplayHeaders();
        echo $Service->Execute();
//End FlashChart1 Execution

//FlashChart1 Tail @136-27890EF8
        exit;
    }
//End FlashChart1 Tail

//Close Page_BeforeInitialize @1-23E6A029
    return $Page_BeforeInitialize;
}
//End Close Page_BeforeInitialize


?>
