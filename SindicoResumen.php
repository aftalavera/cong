<?php
//Include Common Files @1-11752F3C
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "SindicoResumen.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Include Page implementation @2-3DD2EFDC
include_once(RelativePath . "/Header.php");
//End Include Page implementation

//Include Page implementation @76-58DBA1E3
include_once(RelativePath . "/Footer.php");
//End Include Page implementation

class clsGridGrid1 { //Grid1 class @77-E857A572

//Variables @77-6E51DF5A

    // Public variables
    public $ComponentType = "Grid";
    public $ComponentName;
    public $Visible;
    public $Errors;
    public $ErrorBlock;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $ForceIteration = false;
    public $HasRecord = false;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $RowNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";
    public $Attributes;

    // Grid Controls
    public $StaticControls;
    public $RowControls;
//End Variables

//Class_Initialize Event @77-894BDD49
    function clsGridGrid1($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "Grid1";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid Grid1";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsGrid1DataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->siglas = new clsControl(ccsLabel, "siglas", "siglas", ccsText, "", CCGetRequestParam("siglas", ccsGet, NULL), $this);
        $this->logo = new clsControl(ccsImage, "logo", "logo", ccsText, "", CCGetRequestParam("logo", ccsGet, NULL), $this);
        $this->votos = new clsControl(ccsLabel, "votos", "votos", ccsInteger, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("votos", ccsGet, NULL), $this);
        $this->porciento = new clsControl(ccsLabel, "porciento", "porciento", ccsSingle, array(False, 2, Null, "", False, "", "%", 100, True, ""), CCGetRequestParam("porciento", ccsGet, NULL), $this);
        $this->actual = new clsControl(ccsLabel, "actual", "actual", ccsInteger, "", CCGetRequestParam("actual", ccsGet, NULL), $this);
        $this->previo = new clsControl(ccsLabel, "previo", "previo", ccsInteger, "", CCGetRequestParam("previo", ccsGet, NULL), $this);
        $this->cambio = new clsControl(ccsLabel, "cambio", "cambio", ccsInteger, "", CCGetRequestParam("cambio", ccsGet, NULL), $this);
    }
//End Class_Initialize Event

//Initialize Method @77-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @77-F5C4C4B6
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlRETURN_VALUE"] = CCGetFromGet("RETURN_VALUE", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["siglas"] = $this->siglas->Visible;
            $this->ControlsVisible["logo"] = $this->logo->Visible;
            $this->ControlsVisible["votos"] = $this->votos->Visible;
            $this->ControlsVisible["porciento"] = $this->porciento->Visible;
            $this->ControlsVisible["actual"] = $this->actual->Visible;
            $this->ControlsVisible["previo"] = $this->previo->Visible;
            $this->ControlsVisible["cambio"] = $this->cambio->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->siglas->SetValue($this->DataSource->siglas->GetValue());
                $this->logo->SetValue($this->DataSource->logo->GetValue());
                $this->votos->SetValue($this->DataSource->votos->GetValue());
                $this->porciento->SetValue($this->DataSource->porciento->GetValue());
                $this->actual->SetValue($this->DataSource->actual->GetValue());
                $this->previo->SetValue($this->DataSource->previo->GetValue());
                $this->cambio->SetValue($this->DataSource->cambio->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->siglas->Show();
                $this->logo->Show();
                $this->votos->Show();
                $this->porciento->Show();
                $this->actual->Show();
                $this->previo->Show();
                $this->cambio->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @77-8A764040
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->siglas->Errors->ToString());
        $errors = ComposeStrings($errors, $this->logo->Errors->ToString());
        $errors = ComposeStrings($errors, $this->votos->Errors->ToString());
        $errors = ComposeStrings($errors, $this->porciento->Errors->ToString());
        $errors = ComposeStrings($errors, $this->actual->Errors->ToString());
        $errors = ComposeStrings($errors, $this->previo->Errors->ToString());
        $errors = ComposeStrings($errors, $this->cambio->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End Grid1 Class @77-FCB6E20C

class clsGrid1DataSource extends clsDBmssql {  //Grid1DataSource Class @77-56F64D58

//DataSource Variables @77-D0D9FA10
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;


    // Datasource fields
    public $siglas;
    public $logo;
    public $votos;
    public $porciento;
    public $actual;
    public $previo;
    public $cambio;
//End DataSource Variables

//DataSourceClass_Initialize Event @77-901B9EE7
    function clsGrid1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid Grid1";
        $this->Initialize();
        $this->siglas = new clsField("siglas", ccsText, "");
        
        $this->logo = new clsField("logo", ccsText, "");
        
        $this->votos = new clsField("votos", ccsInteger, "");
        
        $this->porciento = new clsField("porciento", ccsSingle, "");
        
        $this->actual = new clsField("actual", ccsInteger, "");
        
        $this->previo = new clsField("previo", ccsInteger, "");
        
        $this->cambio = new clsField("cambio", ccsInteger, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @77-BF7F5B01
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @77-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
    }
//End Prepare Method

//Open Method @77-0D9BD7BE
    function Open()
    {
        $this->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "EXEC spSindicosResumenGeneral " . ";";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query($this->SQL);
        $this->RecordsCount = "CCS not counted";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        if ($this->Errors->count()) return false;
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @77-E2D529F7
    function SetValues()
    {
        $this->siglas->SetDBValue($this->f("siglas"));
        $this->logo->SetDBValue($this->f("logo"));
        $this->votos->SetDBValue(trim($this->f("votos")));
        $this->porciento->SetDBValue(trim($this->f("porciento")));
        $this->actual->SetDBValue(trim($this->f("actual")));
        $this->previo->SetDBValue(trim($this->f("previo")));
        $this->cambio->SetDBValue(trim($this->f("cambio")));
    }
//End SetValues Method

} //End Grid1DataSource Class @77-FCB6E20C

class clsGridGrid2 { //Grid2 class @86-C37AF6B1

//Variables @86-6E51DF5A

    // Public variables
    public $ComponentType = "Grid";
    public $ComponentName;
    public $Visible;
    public $Errors;
    public $ErrorBlock;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $ForceIteration = false;
    public $HasRecord = false;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $RowNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";
    public $Attributes;

    // Grid Controls
    public $StaticControls;
    public $RowControls;
//End Variables

//Class_Initialize Event @86-3B550605
    function clsGridGrid2($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "Grid2";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid Grid2";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsGrid2DataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->votos = new clsControl(ccsLabel, "votos", "votos", ccsSingle, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("votos", ccsGet, NULL), $this);
        $this->inscritos = new clsControl(ccsLabel, "inscritos", "inscritos", ccsSingle, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("inscritos", ccsGet, NULL), $this);
        $this->participacion = new clsControl(ccsLabel, "participacion", "participacion", ccsSingle, array(False, 2, Null, "", False, "", "%", 100, True, ""), CCGetRequestParam("participacion", ccsGet, NULL), $this);
        $this->totalinscritos = new clsControl(ccsLabel, "totalinscritos", "totalinscritos", ccsSingle, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("totalinscritos", ccsGet, NULL), $this);
    }
//End Class_Initialize Event

//Initialize Method @86-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @86-4669F173
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlRETURN_VALUE"] = CCGetFromGet("RETURN_VALUE", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["votos"] = $this->votos->Visible;
            $this->ControlsVisible["inscritos"] = $this->inscritos->Visible;
            $this->ControlsVisible["participacion"] = $this->participacion->Visible;
            $this->ControlsVisible["totalinscritos"] = $this->totalinscritos->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->votos->SetValue($this->DataSource->votos->GetValue());
                $this->inscritos->SetValue($this->DataSource->inscritos->GetValue());
                $this->participacion->SetValue($this->DataSource->participacion->GetValue());
                $this->totalinscritos->SetValue($this->DataSource->totalinscritos->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->votos->Show();
                $this->inscritos->Show();
                $this->participacion->Show();
                $this->totalinscritos->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @86-97DCB100
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->votos->Errors->ToString());
        $errors = ComposeStrings($errors, $this->inscritos->Errors->ToString());
        $errors = ComposeStrings($errors, $this->participacion->Errors->ToString());
        $errors = ComposeStrings($errors, $this->totalinscritos->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End Grid2 Class @86-FCB6E20C

class clsGrid2DataSource extends clsDBmssql {  //Grid2DataSource Class @86-F3FE2634

//DataSource Variables @86-FF91458C
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;


    // Datasource fields
    public $votos;
    public $inscritos;
    public $participacion;
    public $totalinscritos;
//End DataSource Variables

//DataSourceClass_Initialize Event @86-3E6948F8
    function clsGrid2DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid Grid2";
        $this->Initialize();
        $this->votos = new clsField("votos", ccsSingle, "");
        
        $this->inscritos = new clsField("inscritos", ccsSingle, "");
        
        $this->participacion = new clsField("participacion", ccsSingle, "");
        
        $this->totalinscritos = new clsField("totalinscritos", ccsSingle, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @86-BF7F5B01
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @86-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
    }
//End Prepare Method

//Open Method @86-0A82A0EA
    function Open()
    {
        $this->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "EXEC spMunicipalNacionalPart " . ";";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query($this->SQL);
        $this->RecordsCount = "CCS not counted";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        if ($this->Errors->count()) return false;
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @86-910235BC
    function SetValues()
    {
        $this->votos->SetDBValue(trim($this->f("votos")));
        $this->inscritos->SetDBValue(trim($this->f("inscritos")));
        $this->participacion->SetDBValue(trim($this->f("participacion")));
        $this->totalinscritos->SetDBValue(trim($this->f("totalinscritos")));
    }
//End SetValues Method

} //End Grid2DataSource Class @86-FCB6E20C

class clsGridGrid3 { //Grid3 class @92-DA61C7F0

//Variables @92-6E51DF5A

    // Public variables
    public $ComponentType = "Grid";
    public $ComponentName;
    public $Visible;
    public $Errors;
    public $ErrorBlock;
    public $ds;
    public $DataSource;
    public $PageSize;
    public $IsEmpty;
    public $ForceIteration = false;
    public $HasRecord = false;
    public $SorterName = "";
    public $SorterDirection = "";
    public $PageNumber;
    public $RowNumber;
    public $ControlsVisible = array();

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";
    public $Attributes;

    // Grid Controls
    public $StaticControls;
    public $RowControls;
//End Variables

//Class_Initialize Event @92-78B47AE6
    function clsGridGrid3($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "Grid3";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid Grid3";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsGrid3DataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 10;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;

        $this->colegios = new clsControl(ccsLabel, "colegios", "colegios", ccsSingle, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("colegios", ccsGet, NULL), $this);
        $this->contados = new clsControl(ccsLabel, "contados", "contados", ccsSingle, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("contados", ccsGet, NULL), $this);
        $this->porcientoContados = new clsControl(ccsLabel, "porcientoContados", "porcientoContados", ccsSingle, array(False, 2, Null, "", False, "", "%", 100, True, ""), CCGetRequestParam("porcientoContados", ccsGet, NULL), $this);
    }
//End Class_Initialize Event

//Initialize Method @92-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @92-96201C98
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlRETURN_VALUE"] = CCGetFromGet("RETURN_VALUE", NULL);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);


        $this->DataSource->Prepare();
        $this->DataSource->Open();
        $this->HasRecord = $this->DataSource->has_next_record();
        $this->IsEmpty = ! $this->HasRecord;
        $this->Attributes->Show();

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        if(!$this->Visible) return;

        $GridBlock = "Grid " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $GridBlock;


        if (!$this->IsEmpty) {
            $this->ControlsVisible["colegios"] = $this->colegios->Visible;
            $this->ControlsVisible["contados"] = $this->contados->Visible;
            $this->ControlsVisible["porcientoContados"] = $this->porcientoContados->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->colegios->SetValue($this->DataSource->colegios->GetValue());
                $this->contados->SetValue($this->DataSource->contados->GetValue());
                $this->porcientoContados->SetValue($this->DataSource->porcientoContados->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->colegios->Show();
                $this->contados->Show();
                $this->porcientoContados->Show();
                $Tpl->block_path = $ParentPath . "/" . $GridBlock;
                $Tpl->parse("Row", true);
            }
        }
        else { // Show NoRecords block if no records are found
            $this->Attributes->Show();
            $Tpl->parse("NoRecords", false);
        }

        $errors = $this->GetErrors();
        if(strlen($errors))
        {
            $Tpl->replaceblock("", $errors);
            $Tpl->block_path = $ParentPath;
            return;
        }
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @92-AC3B8BA6
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->colegios->Errors->ToString());
        $errors = ComposeStrings($errors, $this->contados->Errors->ToString());
        $errors = ComposeStrings($errors, $this->porcientoContados->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End Grid3 Class @92-FCB6E20C

class clsGrid3DataSource extends clsDBmssql {  //Grid3DataSource Class @92-90F9FF10

//DataSource Variables @92-AF30BA74
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;


    // Datasource fields
    public $colegios;
    public $contados;
    public $porcientoContados;
//End DataSource Variables

//DataSourceClass_Initialize Event @92-BB6340DC
    function clsGrid3DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid Grid3";
        $this->Initialize();
        $this->colegios = new clsField("colegios", ccsSingle, "");
        
        $this->contados = new clsField("contados", ccsSingle, "");
        
        $this->porcientoContados = new clsField("porcientoContados", ccsSingle, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @92-BF7F5B01
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @92-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
    }
//End Prepare Method

//Open Method @92-AB4D85FE
    function Open()
    {
        $this->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "EXEC spMunicipalNacionalTally " . ";";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query($this->SQL);
        $this->RecordsCount = "CCS not counted";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        if ($this->Errors->count()) return false;
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @92-1F9E9418
    function SetValues()
    {
        $this->colegios->SetDBValue(trim($this->f("colegios")));
        $this->contados->SetDBValue(trim($this->f("contados")));
        $this->porcientoContados->SetDBValue(trim($this->f("porcientoContados")));
    }
//End SetValues Method

} //End Grid3DataSource Class @92-FCB6E20C

class clsRecordSelectForm { //SelectForm Class @97-0E16A801

//Variables @97-9E315808

    // Public variables
    public $ComponentType = "Record";
    public $ComponentName;
    public $Parent;
    public $HTMLFormAction;
    public $PressedButton;
    public $Errors;
    public $ErrorBlock;
    public $FormSubmitted;
    public $FormEnctype;
    public $Visible;
    public $IsEmpty;

    public $CCSEvents = "";
    public $CCSEventResult;

    public $RelativePath = "";

    public $InsertAllowed = false;
    public $UpdateAllowed = false;
    public $DeleteAllowed = false;
    public $ReadAllowed   = false;
    public $EditMode      = false;
    public $ds;
    public $DataSource;
    public $ValidatingControls;
    public $Controls;
    public $Attributes;

    // Class variables
//End Variables

//Class_Initialize Event @97-30685FFA
    function clsRecordSelectForm($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record SelectForm/Error";
        $this->DataSource = new clsSelectFormDataSource($this);
        $this->ds = & $this->DataSource;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "SelectForm";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->municipio = new clsControl(ccsListBox, "municipio", "municipio", ccsText, "", CCGetRequestParam("municipio", $Method, NULL), $this);
            $this->municipio->DSType = dsTable;
            $this->municipio->DataSource = new clsDBmssql();
            $this->municipio->ds = & $this->municipio->DataSource;
            $this->municipio->DataSource->SQL = "SELECT * \n" .
"FROM Municipios {SQL_Where} {SQL_OrderBy}";
            $this->municipio->DataSource->Order = "nombre";
            list($this->municipio->BoundColumn, $this->municipio->TextColumn, $this->municipio->DBFormat) = array("cod_muni", "nombre", "");
            $this->municipio->DataSource->Order = "nombre";
            $this->Button_Insert = new clsButton("Button_Insert", $Method, $this);
            $this->Button_Update = new clsButton("Button_Update", $Method, $this);
            $this->Button_Delete = new clsButton("Button_Delete", $Method, $this);
        }
    }
//End Class_Initialize Event

//Initialize Method @97-5D060BAC
    function Initialize()
    {

        if(!$this->Visible)
            return;

    }
//End Initialize Method

//Validate Method @97-C5E74171
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->municipio->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->municipio->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @97-C5E6A107
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->municipio->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @97-ED598703
function SetPrimaryKeys($keyArray)
{
    $this->PrimaryKeys = $keyArray;
}
function GetPrimaryKeys()
{
    return $this->PrimaryKeys;
}
function GetPrimaryKey($keyName)
{
    return $this->PrimaryKeys[$keyName];
}
//End MasterDetail

//Operation Method @97-7D64F8E9
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = true;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "Button_Insert";
            if($this->Button_Insert->Pressed) {
                $this->PressedButton = "Button_Insert";
            } else if($this->Button_Update->Pressed) {
                $this->PressedButton = "Button_Update";
            } else if($this->Button_Delete->Pressed) {
                $this->PressedButton = "Button_Delete";
            }
        }
        $Redirect = "SindicoDetalle.php" . "?" . CCGetQueryString("All", array("ccsForm"));
        if($this->PressedButton == "Button_Delete") {
            if(!CCGetEvent($this->Button_Delete->CCSEvents, "OnClick", $this->Button_Delete)) {
                $Redirect = "";
            }
        } else if($this->Validate()) {
            if($this->PressedButton == "Button_Insert") {
                if(!CCGetEvent($this->Button_Insert->CCSEvents, "OnClick", $this->Button_Insert)) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Button_Update") {
                if(!CCGetEvent($this->Button_Update->CCSEvents, "OnClick", $this->Button_Update)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
        if ($Redirect)
            $this->DataSource->close();
    }
//End Operation Method

//Show Method @97-72B40607
    function Show()
    {
        global $CCSUseAmp;
        global $Tpl;
        global $FileName;
        global $CCSLocales;
        $Error = "";

        if(!$this->Visible)
            return;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeSelect", $this);

        $this->municipio->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if($this->EditMode) {
            if($this->DataSource->Errors->Count()){
                $this->Errors->AddErrors($this->DataSource->Errors);
                $this->DataSource->Errors->clear();
            }
            $this->DataSource->Open();
            if($this->DataSource->Errors->Count() == 0 && $this->DataSource->next_record()) {
                $this->DataSource->SetValues();
            } else {
                $this->EditMode = false;
            }
        }
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->municipio->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        if($this->FormSubmitted || CCGetFromGet("ccsForm")) {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        } else {
            $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("All", ""), "ccsForm", $CCSForm);
        }
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Button_Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Button_Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Button_Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->municipio->Show();
        $this->Button_Insert->Show();
        $this->Button_Update->Show();
        $this->Button_Delete->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End SelectForm Class @97-FCB6E20C

class clsSelectFormDataSource extends clsDBmssql {  //SelectFormDataSource Class @97-22532594

//DataSource Variables @97-A9DC8DB9
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $wp;
    public $AllParametersSet;


    // Datasource fields
    public $municipio;
//End DataSource Variables

//DataSourceClass_Initialize Event @97-EDB3FB33
    function clsSelectFormDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record SelectForm/Error";
        $this->Initialize();
        $this->municipio = new clsField("municipio", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//Prepare Method @97-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
    }
//End Prepare Method

//Open Method @97-62864C6A
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT nombre \n\n" .
        "FROM Provincias {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @97-BAF0975B
    function SetValues()
    {
    }
//End SetValues Method

} //End SelectFormDataSource Class @97-FCB6E20C

//Initialize Page @1-F2C3BAF7
// Variables
$FileName = "";
$Redirect = "";
$Tpl = "";
$TemplateFileName = "";
$BlockToParse = "";
$ComponentName = "";
$Attributes = "";

// Events;
$CCSEvents = "";
$CCSEventResult = "";

$FileName = FileName;
$Redirect = "";
$TemplateFileName = "SindicoResumen.html";
$BlockToParse = "main";
$TemplateEncoding = "UTF-8";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "utf-8";
//End Initialize Page

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-1FB8B310
$DBmssql = new clsDBmssql();
$MainPage->Connections["mssql"] = & $DBmssql;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$Header = new clsHeader("", "Header", $MainPage);
$Header->Initialize();
$Footer = new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
$Grid1 = new clsGridGrid1("", $MainPage);
$Grid2 = new clsGridGrid2("", $MainPage);
$Grid3 = new clsGridGrid3("", $MainPage);
$SelectForm = new clsRecordSelectForm("", $MainPage);
$MainPage->Header = & $Header;
$MainPage->Footer = & $Footer;
$MainPage->Grid1 = & $Grid1;
$MainPage->Grid2 = & $Grid2;
$MainPage->Grid3 = & $Grid3;
$MainPage->SelectForm = & $SelectForm;
$Grid1->Initialize();
$Grid2->Initialize();
$Grid3->Initialize();
$SelectForm->Initialize();

$CCSEventResult = CCGetEvent($CCSEvents, "AfterInitialize", $MainPage);

if ($Charset) {
    header("Content-Type: " . $ContentType . "; charset=" . $Charset);
} else {
    header("Content-Type: " . $ContentType);
}
//End Initialize Objects

//Initialize HTML Template @1-51EC165D
$CCSEventResult = CCGetEvent($CCSEvents, "OnInitializeView", $MainPage);
$Tpl = new clsTemplate($FileEncoding, $TemplateEncoding);
$Tpl->LoadTemplate(PathToCurrentPage . $TemplateFileName, $BlockToParse, "UTF-8");
$Tpl->block_path = "/$BlockToParse";
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeShow", $MainPage);
$Attributes->SetValue("pathToRoot", "");
$Attributes->Show();
//End Initialize HTML Template

//Execute Components @1-B33D964E
$Header->Operations();
$Footer->Operations();
$SelectForm->Operation();
//End Execute Components

//Go to destination page @1-B71A0702
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBmssql->close();
    header("Location: " . $Redirect);
    $Header->Class_Terminate();
    unset($Header);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($Grid1);
    unset($Grid2);
    unset($Grid3);
    unset($SelectForm);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-49D66AB6
$Header->Show();
$Footer->Show();
$Grid1->Show();
$Grid2->Show();
$Grid3->Show();
$SelectForm->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-38003A31
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBmssql->close();
$Header->Class_Terminate();
unset($Header);
$Footer->Class_Terminate();
unset($Footer);
unset($Grid1);
unset($Grid2);
unset($Grid3);
unset($SelectForm);
unset($Tpl);
//End Unload Page


?>
