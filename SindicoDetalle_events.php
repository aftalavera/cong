<?php
//Page_BeforeInitialize @1-E032E149
function Page_BeforeInitialize(& $sender)
{
    $Page_BeforeInitialize = true;
    $Component = & $sender;
    $Container = & CCGetParentContainer($sender);
    global $SindicoDetalle; //Compatibility
//End Page_BeforeInitialize

//FlashChart1 Initialization @136-236E797B
    if ('SindicoDetalleFlashChart1' == CCGetParam('callbackControl')) {
        global $CCSLocales;
        $Service = new Service();
        $formatter = new TemplateFormatter();
        $formatter->SetTemplate(file_get_contents(RelativePath . "/" . "SindicoDetalleFlashChart1.xml"));
        $Service->SetFormatter($formatter);
//End FlashChart1 Initialization

//FlashChart1 DataSource @136-8650506E
        $Service->DataSource = new clsDBmssql();
        $Service->ds = & $Service->DataSource;
        list($FlashChart1->BoundColumn, $FlashChart1->TextColumn, $FlashChart1->DBFormat) = array("", "", "");
        $Service->DataSource->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $Service->DataSource->ErrorBlock);
        $Service->DataSource->cp["municipio"] = new clsSQLParameter("urlmunicipio", ccsInteger, "", "", CCGetFromGet("municipio", NULL), 1, false, $Service->DataSource->ErrorBlock);
        $Service->DataSource->SQL = "EXEC spSindicosDetalleGrafica " . $Service->DataSource->ToSQL($Service->DataSource->cp["municipio"]->GetDBValue(), $Service->DataSource->cp["municipio"]->DataType) . ";";
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
