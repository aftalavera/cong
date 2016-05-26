<?php
//Include Common Files @1-FCE8A8AB
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "SindicoDetalle.php");
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

class clsGridGrid3 { //Grid3 class @97-DA61C7F0

//Variables @97-6E51DF5A

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

//Class_Initialize Event @97-55BB86DC
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

        $this->siglas = new clsControl(ccsLabel, "siglas", "siglas", ccsText, "", CCGetRequestParam("siglas", ccsGet, NULL), $this);
        $this->logo = new clsControl(ccsImage, "logo", "logo", ccsText, "", CCGetRequestParam("logo", ccsGet, NULL), $this);
        $this->votos = new clsControl(ccsLabel, "votos", "votos", ccsInteger, array(False, 0, Null, Null, False, "", "", 1, True, ""), CCGetRequestParam("votos", ccsGet, NULL), $this);
        $this->porciento = new clsControl(ccsLabel, "porciento", "porciento", ccsSingle, array(False, 2, Null, "", False, "", "%", 100, True, ""), CCGetRequestParam("porciento", ccsGet, NULL), $this);
        $this->partido = new clsControl(ccsLabel, "partido", "partido", ccsText, "", CCGetRequestParam("partido", ccsGet, NULL), $this);
        $this->Label1 = new clsControl(ccsLabel, "Label1", "Label1", ccsText, "", CCGetRequestParam("Label1", ccsGet, NULL), $this);
    }
//End Class_Initialize Event

//Initialize Method @97-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @97-D11F7F8B
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlRETURN_VALUE"] = CCGetFromGet("RETURN_VALUE", NULL);
        $this->DataSource->Parameters["urlmunicipio"] = CCGetFromGet("municipio", NULL);

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
            $this->ControlsVisible["partido"] = $this->partido->Visible;
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
                $this->partido->SetValue($this->DataSource->partido->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->siglas->Show();
                $this->logo->Show();
                $this->votos->Show();
                $this->porciento->Show();
                $this->partido->Show();
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
        $this->Label1->SetValue($this->DataSource->Label1->GetValue());
        $this->Label1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @97-C7C59C7C
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->siglas->Errors->ToString());
        $errors = ComposeStrings($errors, $this->logo->Errors->ToString());
        $errors = ComposeStrings($errors, $this->votos->Errors->ToString());
        $errors = ComposeStrings($errors, $this->porciento->Errors->ToString());
        $errors = ComposeStrings($errors, $this->partido->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End Grid3 Class @97-FCB6E20C

class clsGrid3DataSource extends clsDBmssql {  //Grid3DataSource Class @97-90F9FF10

//DataSource Variables @97-3BE32803
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
    public $partido;
    public $Label1;
//End DataSource Variables

//DataSourceClass_Initialize Event @97-4A66E148
    function clsGrid3DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid Grid3";
        $this->Initialize();
        $this->siglas = new clsField("siglas", ccsText, "");
        
        $this->logo = new clsField("logo", ccsText, "");
        
        $this->votos = new clsField("votos", ccsInteger, "");
        
        $this->porciento = new clsField("porciento", ccsSingle, "");
        
        $this->partido = new clsField("partido", ccsText, "");
        
        $this->Label1 = new clsField("Label1", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @97-BF7F5B01
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @97-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
    }
//End Prepare Method

//Open Method @97-A4B48259
    function Open()
    {
        $this->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $this->ErrorBlock);
        $this->cp["municipio"] = new clsSQLParameter("urlmunicipio", ccsInteger, "", "", CCGetFromGet("municipio", NULL), 1, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "EXEC spSindicos " . $this->ToSQL($this->cp["municipio"]->GetDBValue(), $this->cp["municipio"]->DataType) . ";";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query($this->SQL);
        $this->RecordsCount = "CCS not counted";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        if ($this->Errors->count()) return false;
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @97-CE2F7786
    function SetValues()
    {
        $this->siglas->SetDBValue($this->f("siglas"));
        $this->logo->SetDBValue($this->f("logo"));
        $this->votos->SetDBValue(trim($this->f("votos")));
        $this->porciento->SetDBValue(trim($this->f("porciento")));
        $this->partido->SetDBValue($this->f("partido"));
        $this->Label1->SetDBValue($this->f("nombre"));
    }
//End SetValues Method

} //End Grid3DataSource Class @97-FCB6E20C

class clsGridGrid5 { //Grid5 class @92-8C3B6076

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

//Class_Initialize Event @92-A02B2628
    function clsGridGrid5($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "Grid5";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid Grid5";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clsGrid5DataSource($this);
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

//Show Method @92-F38DC302
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlRETURN_VALUE"] = CCGetFromGet("RETURN_VALUE", NULL);
        $this->DataSource->Parameters["urlmunicipio"] = CCGetFromGet("municipio", NULL);

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

} //End Grid5 Class @92-FCB6E20C

class clsGrid5DataSource extends clsDBmssql {  //Grid5DataSource Class @92-01982F89

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

//DataSourceClass_Initialize Event @92-4CED1E94
    function clsGrid5DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid Grid5";
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

//Open Method @92-171DA56B
    function Open()
    {
        $this->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $this->ErrorBlock);
        $this->cp["municipio"] = new clsSQLParameter("urlmunicipio", ccsInteger, "", "", CCGetFromGet("municipio", NULL), 1, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "EXEC spSindicosTally " . $this->ToSQL($this->cp["municipio"]->GetDBValue(), $this->cp["municipio"]->DataType) . ";";
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

} //End Grid5DataSource Class @92-FCB6E20C

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

//Class_Initialize Event @77-BCD02208
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

//Show Method @77-7F182979
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlRETURN_VALUE"] = CCGetFromGet("RETURN_VALUE", NULL);
        $this->DataSource->Parameters["urlmunicipio"] = CCGetFromGet("municipio", NULL);

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
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->siglas->Show();
                $this->logo->Show();
                $this->votos->Show();
                $this->porciento->Show();
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

//GetErrors Method @77-9AABEB02
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->siglas->Errors->ToString());
        $errors = ComposeStrings($errors, $this->logo->Errors->ToString());
        $errors = ComposeStrings($errors, $this->votos->Errors->ToString());
        $errors = ComposeStrings($errors, $this->porciento->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End Grid1 Class @77-FCB6E20C

class clsGrid1DataSource extends clsDBmssql {  //Grid1DataSource Class @77-56F64D58

//DataSource Variables @77-023A1B1D
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
//End DataSource Variables

//DataSourceClass_Initialize Event @77-764EB934
    function clsGrid1DataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid Grid1";
        $this->Initialize();
        $this->siglas = new clsField("siglas", ccsText, "");
        
        $this->logo = new clsField("logo", ccsText, "");
        
        $this->votos = new clsField("votos", ccsInteger, "");
        
        $this->porciento = new clsField("porciento", ccsSingle, "");
        

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

//Open Method @77-BE01656C
    function Open()
    {
        $this->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $this->ErrorBlock);
        $this->cp["municipio"] = new clsSQLParameter("urlmunicipio", ccsInteger, "", "", CCGetFromGet("municipio", NULL), 1, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "EXEC spSindicosDetalle " . $this->ToSQL($this->cp["municipio"]->GetDBValue(), $this->cp["municipio"]->DataType) . ";";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query($this->SQL);
        $this->RecordsCount = "CCS not counted";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        if ($this->Errors->count()) return false;
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @77-F006E64A
    function SetValues()
    {
        $this->siglas->SetDBValue($this->f("siglas"));
        $this->logo->SetDBValue($this->f("logo"));
        $this->votos->SetDBValue(trim($this->f("votos")));
        $this->porciento->SetDBValue(trim($this->f("porciento")));
    }
//End SetValues Method

} //End Grid1DataSource Class @77-FCB6E20C

class clsGridGrid2 { //Grid2 class @93-C37AF6B1

//Variables @93-6E51DF5A

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

//Class_Initialize Event @93-3B550605
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

//Initialize Method @93-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @93-676E9081
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlRETURN_VALUE"] = CCGetFromGet("RETURN_VALUE", NULL);
        $this->DataSource->Parameters["urlmunicipio"] = CCGetFromGet("municipio", NULL);

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

//GetErrors Method @93-97DCB100
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

} //End Grid2 Class @93-FCB6E20C

class clsGrid2DataSource extends clsDBmssql {  //Grid2DataSource Class @93-F3FE2634

//DataSource Variables @93-FF91458C
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

//DataSourceClass_Initialize Event @93-3E6948F8
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

//SetOrder Method @93-BF7F5B01
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            "");
    }
//End SetOrder Method

//Prepare Method @93-14D6CD9D
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
    }
//End Prepare Method

//Open Method @93-F5CFD5A1
    function Open()
    {
        $this->cp["RETURN_VALUE"] = new clsSQLParameter("urlRETURN_VALUE", ccsInteger, "", "", CCGetFromGet("RETURN_VALUE", NULL), "", false, $this->ErrorBlock);
        $this->cp["municipio"] = new clsSQLParameter("urlmunicipio", ccsInteger, array(True, 0, Null, "", False, array("municipio"), "", 1, True, ""), "", CCGetFromGet("municipio", NULL), 1, false, $this->ErrorBlock);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "EXEC spSindicosProyPart " . $this->ToSQL($this->cp["municipio"]->GetDBValue(), $this->cp["municipio"]->DataType) . ";";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query($this->SQL);
        $this->RecordsCount = "CCS not counted";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        if ($this->Errors->count()) return false;
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @93-910235BC
    function SetValues()
    {
        $this->votos->SetDBValue(trim($this->f("votos")));
        $this->inscritos->SetDBValue(trim($this->f("inscritos")));
        $this->participacion->SetDBValue(trim($this->f("participacion")));
        $this->totalinscritos->SetDBValue(trim($this->f("totalinscritos")));
    }
//End SetValues Method

} //End Grid2DataSource Class @93-FCB6E20C









//Initialize Page @1-48B78541
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
$TemplateFileName = "SindicoDetalle.html";
$BlockToParse = "main";
$TemplateEncoding = "UTF-8";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "utf-8";
//End Initialize Page

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-6763AFF9
$DBintranet = new clsDBintranet();
$MainPage->Connections["intranet"] = & $DBintranet;
$DBmssql = new clsDBmssql();
$MainPage->Connections["mssql"] = & $DBmssql;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$Header = new clsHeader("", "Header", $MainPage);
$Header->Initialize();
$Footer = new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
$Grid3 = new clsGridGrid3("", $MainPage);
$Grid5 = new clsGridGrid5("", $MainPage);
$Grid1 = new clsGridGrid1("", $MainPage);
$Grid2 = new clsGridGrid2("", $MainPage);
$MainPage->Header = & $Header;
$MainPage->Footer = & $Footer;
$MainPage->Grid3 = & $Grid3;
$MainPage->Grid5 = & $Grid5;
$MainPage->Grid1 = & $Grid1;
$MainPage->Grid2 = & $Grid2;
$Grid3->Initialize();
$Grid5->Initialize();
$Grid1->Initialize();
$Grid2->Initialize();

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

//Execute Components @1-351F985C
$Header->Operations();
$Footer->Operations();
//End Execute Components

//Go to destination page @1-17DE1C82
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBintranet->close();
    $DBmssql->close();
    header("Location: " . $Redirect);
    $Header->Class_Terminate();
    unset($Header);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($Grid3);
    unset($Grid5);
    unset($Grid1);
    unset($Grid2);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-C91F440F
$Header->Show();
$Footer->Show();
$Grid3->Show();
$Grid5->Show();
$Grid1->Show();
$Grid2->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-53996E5C
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBintranet->close();
$DBmssql->close();
$Header->Class_Terminate();
unset($Header);
$Footer->Class_Terminate();
unset($Footer);
unset($Grid3);
unset($Grid5);
unset($Grid1);
unset($Grid2);
unset($Tpl);
//End Unload Page


?>
