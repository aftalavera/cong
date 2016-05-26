<?php
//Include Common Files @1-FB95FE08
define("RelativePath", ".");
define("PathToCurrentPage", "/");
define("FileName", "Main.php");
include_once(RelativePath . "/Common.php");
include_once(RelativePath . "/Template.php");
include_once(RelativePath . "/Sorter.php");
include_once(RelativePath . "/Navigator.php");
//End Include Common Files

//Include Page implementation @2-3DD2EFDC
include_once(RelativePath . "/Header.php");
//End Include Page implementation

//Include Page implementation @4-58DBA1E3
include_once(RelativePath . "/Footer.php");
//End Include Page implementation

class clsRecordadvanced_search { //advanced_search Class @6-58DF25DD

//Variables @6-9E315808

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

//Class_Initialize Event @6-5708CEDF
    function clsRecordadvanced_search($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record advanced_search/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "advanced_search";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->assign_to = new clsControl(ccsListBox, "assign_to", "assign_to", ccsInteger, "", CCGetRequestParam("assign_to", $Method, NULL), $this);
            $this->assign_to->DSType = dsTable;
            $this->assign_to->DataSource = new clsDBintranet();
            $this->assign_to->ds = & $this->assign_to->DataSource;
            $this->assign_to->DataSource->SQL = "SELECT * \n" .
"FROM employees {SQL_Where} {SQL_OrderBy}";
            $this->assign_to->DataSource->Order = "emp_name";
            list($this->assign_to->BoundColumn, $this->assign_to->TextColumn, $this->assign_to->DBFormat) = array("emp_id", "emp_name", "");
            $this->assign_to->DataSource->Order = "emp_name";
            $this->priority_id = new clsControl(ccsListBox, "priority_id", "priority_id", ccsInteger, "", CCGetRequestParam("priority_id", $Method, NULL), $this);
            $this->priority_id->DSType = dsTable;
            $this->priority_id->DataSource = new clsDBintranet();
            $this->priority_id->ds = & $this->priority_id->DataSource;
            $this->priority_id->DataSource->SQL = "SELECT * \n" .
"FROM priorities {SQL_Where} {SQL_OrderBy}";
            $this->priority_id->DataSource->Order = "priority_name";
            list($this->priority_id->BoundColumn, $this->priority_id->TextColumn, $this->priority_id->DBFormat) = array("priority_id", "priority_name", "");
            $this->priority_id->DataSource->Order = "priority_name";
            $this->status_id = new clsControl(ccsListBox, "status_id", "status_id", ccsInteger, "", CCGetRequestParam("status_id", $Method, NULL), $this);
            $this->status_id->DSType = dsTable;
            $this->status_id->DataSource = new clsDBintranet();
            $this->status_id->ds = & $this->status_id->DataSource;
            $this->status_id->DataSource->SQL = "SELECT * \n" .
"FROM statuses {SQL_Where} {SQL_OrderBy}";
            $this->status_id->DataSource->Order = "status_name";
            list($this->status_id->BoundColumn, $this->status_id->TextColumn, $this->status_id->DBFormat) = array("status_id", "status_name", "");
            $this->status_id->DataSource->Order = "status_name";
            $this->DoSearch = new clsButton("DoSearch", $Method, $this);
            $this->keywords = new clsControl(ccsTextBox, "keywords", "keywords", ccsText, "", CCGetRequestParam("keywords", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Validate Method @6-0EDFE912
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->assign_to->Validate() && $Validation);
        $Validation = ($this->priority_id->Validate() && $Validation);
        $Validation = ($this->status_id->Validate() && $Validation);
        $Validation = ($this->keywords->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->assign_to->Errors->Count() == 0);
        $Validation =  $Validation && ($this->priority_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->status_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->keywords->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @6-9F0BB406
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->assign_to->Errors->Count());
        $errors = ($errors || $this->priority_id->Errors->Count());
        $errors = ($errors || $this->status_id->Errors->Count());
        $errors = ($errors || $this->keywords->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @6-ED598703
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

//Operation Method @6-F97EFF03
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "DoSearch";
            if($this->DoSearch->Pressed) {
                $this->PressedButton = "DoSearch";
            }
        }
        $Redirect = "Main.php";
        if($this->Validate()) {
            if($this->PressedButton == "DoSearch") {
                $Redirect = "Main.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("DoSearch", "DoSearch_x", "DoSearch_y")));
                if(!CCGetEvent($this->DoSearch->CCSEvents, "OnClick", $this->DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @6-34500667
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

        $this->assign_to->Prepare();
        $this->priority_id->Prepare();
        $this->status_id->Prepare();

        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->assign_to->Errors->ToString());
            $Error = ComposeStrings($Error, $this->priority_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->status_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->keywords->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->assign_to->Show();
        $this->priority_id->Show();
        $this->status_id->Show();
        $this->DoSearch->Show();
        $this->keywords->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End advanced_search Class @6-FCB6E20C

class clsGridtasks { //tasks class @20-BB4EE062

//Variables @20-E9DA9C0D

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
    public $sorter_task_name;
    public $sorter_priority_id;
    public $sorter_status_id;
    public $sorter_user_id_assign_to;
//End Variables

//Class_Initialize Event @20-859AB8E4
    function clsGridtasks($RelativePath, & $Parent)
    {
        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->ComponentName = "tasks";
        $this->Visible = True;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Grid tasks";
        $this->Attributes = new clsAttributes($this->ComponentName . ":");
        $this->DataSource = new clstasksDataSource($this);
        $this->ds = & $this->DataSource;
        $this->PageSize = CCGetParam($this->ComponentName . "PageSize", "");
        if(!is_numeric($this->PageSize) || !strlen($this->PageSize))
            $this->PageSize = 25;
        else
            $this->PageSize = intval($this->PageSize);
        if ($this->PageSize > 100)
            $this->PageSize = 100;
        if($this->PageSize == 0)
            $this->Errors->addError("<p>Form: Grid " . $this->ComponentName . "<br>Error: (CCS06) Invalid page size.</p>");
        $this->PageNumber = intval(CCGetParam($this->ComponentName . "Page", 1));
        if ($this->PageNumber <= 0) $this->PageNumber = 1;
        $this->SorterName = CCGetParam("tasksOrder", "");
        $this->SorterDirection = CCGetParam("tasksDir", "");

        $this->task_name = new clsControl(ccsLink, "task_name", "task_name", ccsText, "", CCGetRequestParam("task_name", ccsGet, NULL), $this);
        $this->task_name->Page = "Main.php";
        $this->priority_id = new clsControl(ccsLabel, "priority_id", "priority_id", ccsText, "", CCGetRequestParam("priority_id", ccsGet, NULL), $this);
        $this->status_id = new clsControl(ccsLabel, "status_id", "status_id", ccsText, "", CCGetRequestParam("status_id", ccsGet, NULL), $this);
        $this->user_id_assign_to = new clsControl(ccsLabel, "user_id_assign_to", "user_id_assign_to", ccsText, "", CCGetRequestParam("user_id_assign_to", ccsGet, NULL), $this);
        $this->sorter_task_name = new clsSorter($this->ComponentName, "sorter_task_name", $FileName, $this);
        $this->sorter_priority_id = new clsSorter($this->ComponentName, "sorter_priority_id", $FileName, $this);
        $this->sorter_status_id = new clsSorter($this->ComponentName, "sorter_status_id", $FileName, $this);
        $this->sorter_user_id_assign_to = new clsSorter($this->ComponentName, "sorter_user_id_assign_to", $FileName, $this);
        $this->Link1 = new clsControl(ccsLink, "Link1", "Link1", ccsText, "", CCGetRequestParam("Link1", ccsGet, NULL), $this);
        $this->Link1->HTML = true;
        $this->Link1->Page = "Main.php";
        $this->task_items_Navigator = new clsNavigator($this->ComponentName, "task_items_Navigator", $FileName, 10, tpSimple, $this);
        $this->task_items_Navigator->PageSizes = array("1", "5", "10", "25", "50");
    }
//End Class_Initialize Event

//Initialize Method @20-90E704C5
    function Initialize()
    {
        if(!$this->Visible) return;

        $this->DataSource->PageSize = & $this->PageSize;
        $this->DataSource->AbsolutePage = & $this->PageNumber;
        $this->DataSource->SetOrder($this->SorterName, $this->SorterDirection);
    }
//End Initialize Method

//Show Method @20-112ED1C3
    function Show()
    {
        global $Tpl;
        global $CCSLocales;
        if(!$this->Visible) return;

        $this->RowNumber = 0;

        $this->DataSource->Parameters["urlkeywords"] = CCGetFromGet("keywords", NULL);
        $this->DataSource->Parameters["urlassign_to"] = CCGetFromGet("assign_to", NULL);
        $this->DataSource->Parameters["urlproject_id"] = CCGetFromGet("project_id", NULL);
        $this->DataSource->Parameters["urlpriority_id"] = CCGetFromGet("priority_id", NULL);
        $this->DataSource->Parameters["urlstatus_id"] = CCGetFromGet("status_id", NULL);
        $this->DataSource->Parameters["urltype_id"] = CCGetFromGet("type_id", NULL);

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
            $this->ControlsVisible["task_name"] = $this->task_name->Visible;
            $this->ControlsVisible["priority_id"] = $this->priority_id->Visible;
            $this->ControlsVisible["status_id"] = $this->status_id->Visible;
            $this->ControlsVisible["user_id_assign_to"] = $this->user_id_assign_to->Visible;
            while ($this->ForceIteration || (($this->RowNumber < $this->PageSize) &&  ($this->HasRecord = $this->DataSource->has_next_record()))) {
                $this->RowNumber++;
                if ($this->HasRecord) {
                    $this->DataSource->next_record();
                    $this->DataSource->SetValues();
                }
                $Tpl->block_path = $ParentPath . "/" . $GridBlock . "/Row";
                $this->task_name->SetValue($this->DataSource->task_name->GetValue());
                $this->task_name->Parameters = "";
                $this->task_name->Parameters = CCAddParam($this->task_name->Parameters, "task_id", $this->DataSource->f("task_id"));
                $this->priority_id->SetValue($this->DataSource->priority_id->GetValue());
                $this->status_id->SetValue($this->DataSource->status_id->GetValue());
                $this->user_id_assign_to->SetValue($this->DataSource->user_id_assign_to->GetValue());
                $this->Attributes->SetValue("rowNumber", $this->RowNumber);
                $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShowRow", $this);
                $this->Attributes->Show();
                $this->task_name->Show();
                $this->priority_id->Show();
                $this->status_id->Show();
                $this->user_id_assign_to->Show();
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
        $this->task_items_Navigator->PageNumber = $this->DataSource->AbsolutePage;
        $this->task_items_Navigator->PageSize = $this->PageSize;
        if ($this->DataSource->RecordsCount == "CCS not counted")
            $this->task_items_Navigator->TotalPages = $this->DataSource->AbsolutePage + ($this->DataSource->next_record() ? 1 : 0);
        else
            $this->task_items_Navigator->TotalPages = $this->DataSource->PageCount();
        if ($this->task_items_Navigator->TotalPages <= 1) {
            $this->task_items_Navigator->Visible = false;
        }
        $this->sorter_task_name->Show();
        $this->sorter_priority_id->Show();
        $this->sorter_status_id->Show();
        $this->sorter_user_id_assign_to->Show();
        $this->Link1->Show();
        $this->task_items_Navigator->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

//GetErrors Method @20-C90F0D68
    function GetErrors()
    {
        $errors = "";
        $errors = ComposeStrings($errors, $this->task_name->Errors->ToString());
        $errors = ComposeStrings($errors, $this->priority_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->status_id->Errors->ToString());
        $errors = ComposeStrings($errors, $this->user_id_assign_to->Errors->ToString());
        $errors = ComposeStrings($errors, $this->Errors->ToString());
        $errors = ComposeStrings($errors, $this->DataSource->Errors->ToString());
        return $errors;
    }
//End GetErrors Method

} //End tasks Class @20-FCB6E20C

class clstasksDataSource extends clsDBintranet {  //tasksDataSource Class @20-427BD7EC

//DataSource Variables @20-39F60877
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $CountSQL;
    public $wp;


    // Datasource fields
    public $task_name;
    public $priority_id;
    public $status_id;
    public $user_id_assign_to;
//End DataSource Variables

//DataSourceClass_Initialize Event @20-57AB0ABF
    function clstasksDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Grid tasks";
        $this->Initialize();
        $this->task_name = new clsField("task_name", ccsText, "");
        
        $this->priority_id = new clsField("priority_id", ccsText, "");
        
        $this->status_id = new clsField("status_id", ccsText, "");
        
        $this->user_id_assign_to = new clsField("user_id_assign_to", ccsText, "");
        

    }
//End DataSourceClass_Initialize Event

//SetOrder Method @20-7B96B344
    function SetOrder($SorterName, $SorterDirection)
    {
        $this->Order = "";
        $this->Order = CCGetOrder($this->Order, $SorterName, $SorterDirection, 
            array("sorter_task_name" => array("task_name, task_id", "type_name desc, task_id"), 
            "sorter_priority_id" => array("priority_name, task_id", "priority_name desc, task_id"), 
            "sorter_status_id" => array("status_name, task_id", "status_name desc, task_id"), 
            "sorter_user_id_assign_to" => array("emp_name, task_id", "emp_name desc, task_id")));
    }
//End SetOrder Method

//Prepare Method @20-54AD809E
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urlkeywords", ccsText, "", "", $this->Parameters["urlkeywords"], "", false);
        $this->wp->AddParameter("2", "urlassign_to", ccsInteger, "", "", $this->Parameters["urlassign_to"], "", false);
        $this->wp->AddParameter("3", "urlproject_id", ccsInteger, "", "", $this->Parameters["urlproject_id"], "", false);
        $this->wp->AddParameter("4", "urlpriority_id", ccsInteger, "", "", $this->Parameters["urlpriority_id"], "", false);
        $this->wp->AddParameter("5", "urlstatus_id", ccsInteger, "", "", $this->Parameters["urlstatus_id"], "", false);
        $this->wp->AddParameter("6", "urltype_id", ccsInteger, "", "", $this->Parameters["urltype_id"], "", false);
        $this->wp->Criterion[1] = $this->wp->Operation(opContains, "tasks.task_name", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsText),false);
        $this->wp->Criterion[2] = $this->wp->Operation(opEqual, "user_id_assign_to", $this->wp->GetDBValue("2"), $this->ToSQL($this->wp->GetDBValue("2"), ccsInteger),false);
        $this->wp->Criterion[3] = $this->wp->Operation(opEqual, "tasks.project_id", $this->wp->GetDBValue("3"), $this->ToSQL($this->wp->GetDBValue("3"), ccsInteger),false);
        $this->wp->Criterion[4] = $this->wp->Operation(opEqual, "tasks.priority_id", $this->wp->GetDBValue("4"), $this->ToSQL($this->wp->GetDBValue("4"), ccsInteger),false);
        $this->wp->Criterion[5] = $this->wp->Operation(opEqual, "tasks.status_id", $this->wp->GetDBValue("5"), $this->ToSQL($this->wp->GetDBValue("5"), ccsInteger),false);
        $this->wp->Criterion[6] = $this->wp->Operation(opEqual, "types.type_id", $this->wp->GetDBValue("6"), $this->ToSQL($this->wp->GetDBValue("6"), ccsInteger),false);
        $this->Where = $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, $this->wp->opAND(
             false, 
             $this->wp->Criterion[1], 
             $this->wp->Criterion[2]), 
             $this->wp->Criterion[3]), 
             $this->wp->Criterion[4]), 
             $this->wp->Criterion[5]), 
             $this->wp->Criterion[6]);
    }
//End Prepare Method

//Open Method @20-02835D44
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->CountSQL = "SELECT COUNT(*)\n\n" .
        "FROM ((((tasks LEFT JOIN statuses ON\n\n" .
        "tasks.status_id = statuses.status_id) LEFT JOIN projects ON\n\n" .
        "tasks.project_id = projects.project_id) LEFT JOIN priorities ON\n\n" .
        "tasks.priority_id = priorities.priority_id) LEFT JOIN employees ON\n\n" .
        "tasks.user_id_assign_to = employees.emp_id) INNER JOIN types ON\n\n" .
        "tasks.type_id = types.type_id";
        $this->SQL = "SELECT TOP {SqlParam_endRecord} task_id, task_name, task_start_date, task_finish_date, status_name, project_name, priority_name, emp_name, type_name \n\n" .
        "FROM ((((tasks LEFT JOIN statuses ON\n\n" .
        "tasks.status_id = statuses.status_id) LEFT JOIN projects ON\n\n" .
        "tasks.project_id = projects.project_id) LEFT JOIN priorities ON\n\n" .
        "tasks.priority_id = priorities.priority_id) LEFT JOIN employees ON\n\n" .
        "tasks.user_id_assign_to = employees.emp_id) INNER JOIN types ON\n\n" .
        "tasks.type_id = types.type_id {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        if ($this->CountSQL) 
            $this->RecordsCount = CCGetDBValue(CCBuildSQL($this->CountSQL, $this->Where, ""), $this);
        else
            $this->RecordsCount = "CCS not counted";
        $this->query($this->OptimizeSQL(CCBuildSQL($this->SQL, $this->Where, $this->Order)));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
        $this->MoveToPage($this->AbsolutePage);
    }
//End Open Method

//SetValues Method @20-C534C112
    function SetValues()
    {
        $this->task_name->SetDBValue($this->f("task_name"));
        $this->priority_id->SetDBValue($this->f("priority_name"));
        $this->status_id->SetDBValue($this->f("status_name"));
        $this->user_id_assign_to->SetDBValue($this->f("emp_name"));
    }
//End SetValues Method

} //End tasksDataSource Class @20-FCB6E20C

class clsRecordtask { //task Class @96-C007E7D1

//Variables @96-9E315808

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

//Class_Initialize Event @96-AE8DBADA
    function clsRecordtask($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record task/Error";
        $this->DataSource = new clstaskDataSource($this);
        $this->ds = & $this->DataSource;
        $this->InsertAllowed = true;
        $this->UpdateAllowed = true;
        $this->DeleteAllowed = true;
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "task";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->EditMode = ($FormMethod == "Edit");
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->task_name = new clsControl(ccsTextBox, "task_name", "Task", ccsText, "", CCGetRequestParam("task_name", $Method, NULL), $this);
            $this->task_name->Required = true;
            $this->task_desc = new clsControl(ccsTextArea, "task_desc", "task_desc", ccsMemo, "", CCGetRequestParam("task_desc", $Method, NULL), $this);
            $this->project_id = new clsControl(ccsListBox, "project_id", "Project", ccsInteger, "", CCGetRequestParam("project_id", $Method, NULL), $this);
            $this->project_id->DSType = dsTable;
            $this->project_id->DataSource = new clsDBintranet();
            $this->project_id->ds = & $this->project_id->DataSource;
            $this->project_id->DataSource->SQL = "SELECT * \n" .
"FROM projects {SQL_Where} {SQL_OrderBy}";
            $this->project_id->DataSource->Order = "project_name";
            list($this->project_id->BoundColumn, $this->project_id->TextColumn, $this->project_id->DBFormat) = array("project_id", "project_name", "");
            $this->project_id->DataSource->Order = "project_name";
            $this->project_id->Required = true;
            $this->user_assign_by = new clsControl(ccsHidden, "user_assign_by", "user_assign_by", ccsText, "", CCGetRequestParam("user_assign_by", $Method, NULL), $this);
            $this->priority_id = new clsControl(ccsListBox, "priority_id", "Priority", ccsInteger, "", CCGetRequestParam("priority_id", $Method, NULL), $this);
            $this->priority_id->DSType = dsTable;
            $this->priority_id->DataSource = new clsDBintranet();
            $this->priority_id->ds = & $this->priority_id->DataSource;
            $this->priority_id->DataSource->SQL = "SELECT * \n" .
"FROM priorities {SQL_Where} {SQL_OrderBy}";
            $this->priority_id->DataSource->Order = "priority_name";
            list($this->priority_id->BoundColumn, $this->priority_id->TextColumn, $this->priority_id->DBFormat) = array("priority_id", "priority_name", "");
            $this->priority_id->DataSource->Order = "priority_name";
            $this->priority_id->Required = true;
            $this->status_id = new clsControl(ccsListBox, "status_id", "Status", ccsInteger, "", CCGetRequestParam("status_id", $Method, NULL), $this);
            $this->status_id->DSType = dsTable;
            $this->status_id->DataSource = new clsDBintranet();
            $this->status_id->ds = & $this->status_id->DataSource;
            $this->status_id->DataSource->SQL = "SELECT * \n" .
"FROM statuses {SQL_Where} {SQL_OrderBy}";
            $this->status_id->DataSource->Order = "status_name";
            list($this->status_id->BoundColumn, $this->status_id->TextColumn, $this->status_id->DBFormat) = array("status_id", "status_name", "");
            $this->status_id->DataSource->Order = "status_name";
            $this->status_id->Required = true;
            $this->type_id = new clsControl(ccsListBox, "type_id", "Type", ccsInteger, "", CCGetRequestParam("type_id", $Method, NULL), $this);
            $this->type_id->DSType = dsTable;
            $this->type_id->DataSource = new clsDBintranet();
            $this->type_id->ds = & $this->type_id->DataSource;
            $this->type_id->DataSource->SQL = "SELECT * \n" .
"FROM types {SQL_Where} {SQL_OrderBy}";
            $this->type_id->DataSource->Order = "type_name";
            list($this->type_id->BoundColumn, $this->type_id->TextColumn, $this->type_id->DBFormat) = array("type_id", "type_name", "");
            $this->type_id->DataSource->Order = "type_name";
            $this->type_id->Required = true;
            $this->user_id_assign_to = new clsControl(ccsListBox, "user_id_assign_to", "Assigned To", ccsInteger, "", CCGetRequestParam("user_id_assign_to", $Method, NULL), $this);
            $this->user_id_assign_to->DSType = dsTable;
            $this->user_id_assign_to->DataSource = new clsDBintranet();
            $this->user_id_assign_to->ds = & $this->user_id_assign_to->DataSource;
            $this->user_id_assign_to->DataSource->SQL = "SELECT * \n" .
"FROM employees {SQL_Where} {SQL_OrderBy}";
            $this->user_id_assign_to->DataSource->Order = "emp_name";
            list($this->user_id_assign_to->BoundColumn, $this->user_id_assign_to->TextColumn, $this->user_id_assign_to->DBFormat) = array("emp_id", "emp_name", "");
            $this->user_id_assign_to->DataSource->Order = "emp_name";
            $this->user_id_assign_to->Required = true;
            $this->task_start_date = new clsControl(ccsTextBox, "task_start_date", "Start Date", ccsDate, array("mm", "/", "dd", "/", "yy"), CCGetRequestParam("task_start_date", $Method, NULL), $this);
            $this->task_finish_date = new clsControl(ccsTextBox, "task_finish_date", "Finish Date", ccsDate, array("mm", "/", "dd", "/", "yy"), CCGetRequestParam("task_finish_date", $Method, NULL), $this);
            $this->Insert = new clsButton("Insert", $Method, $this);
            $this->Update = new clsButton("Update", $Method, $this);
            $this->Delete = new clsButton("Delete", $Method, $this);
            $this->Cancel = new clsButton("Cancel", $Method, $this);
            $this->DatePicker_task_start_date1 = new clsDatePicker("DatePicker_task_start_date1", "task", "task_start_date", $this);
            $this->DatePicker_task_finish_date1 = new clsDatePicker("DatePicker_task_finish_date1", "task", "task_finish_date", $this);
        }
    }
//End Class_Initialize Event

//Initialize Method @96-AD25022A
    function Initialize()
    {

        if(!$this->Visible)
            return;

        $this->DataSource->Parameters["urltask_id"] = CCGetFromGet("task_id", NULL);
    }
//End Initialize Method

//Validate Method @96-6332D628
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->task_name->Validate() && $Validation);
        $Validation = ($this->task_desc->Validate() && $Validation);
        $Validation = ($this->project_id->Validate() && $Validation);
        $Validation = ($this->user_assign_by->Validate() && $Validation);
        $Validation = ($this->priority_id->Validate() && $Validation);
        $Validation = ($this->status_id->Validate() && $Validation);
        $Validation = ($this->type_id->Validate() && $Validation);
        $Validation = ($this->user_id_assign_to->Validate() && $Validation);
        $Validation = ($this->task_start_date->Validate() && $Validation);
        $Validation = ($this->task_finish_date->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->task_name->Errors->Count() == 0);
        $Validation =  $Validation && ($this->task_desc->Errors->Count() == 0);
        $Validation =  $Validation && ($this->project_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->user_assign_by->Errors->Count() == 0);
        $Validation =  $Validation && ($this->priority_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->status_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->type_id->Errors->Count() == 0);
        $Validation =  $Validation && ($this->user_id_assign_to->Errors->Count() == 0);
        $Validation =  $Validation && ($this->task_start_date->Errors->Count() == 0);
        $Validation =  $Validation && ($this->task_finish_date->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @96-C39BC47C
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->task_name->Errors->Count());
        $errors = ($errors || $this->task_desc->Errors->Count());
        $errors = ($errors || $this->project_id->Errors->Count());
        $errors = ($errors || $this->user_assign_by->Errors->Count());
        $errors = ($errors || $this->priority_id->Errors->Count());
        $errors = ($errors || $this->status_id->Errors->Count());
        $errors = ($errors || $this->type_id->Errors->Count());
        $errors = ($errors || $this->user_id_assign_to->Errors->Count());
        $errors = ($errors || $this->task_start_date->Errors->Count());
        $errors = ($errors || $this->task_finish_date->Errors->Count());
        $errors = ($errors || $this->DatePicker_task_start_date1->Errors->Count());
        $errors = ($errors || $this->DatePicker_task_finish_date1->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        $errors = ($errors || $this->DataSource->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @96-ED598703
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

//Operation Method @96-2E0501FF
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        $this->DataSource->Prepare();
        if(!$this->FormSubmitted) {
            $this->EditMode = $this->DataSource->AllParametersSet;
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = $this->EditMode ? "Update" : "Insert";
            if($this->Insert->Pressed) {
                $this->PressedButton = "Insert";
            } else if($this->Update->Pressed) {
                $this->PressedButton = "Update";
            } else if($this->Delete->Pressed) {
                $this->PressedButton = "Delete";
            } else if($this->Cancel->Pressed) {
                $this->PressedButton = "Cancel";
            }
        }
        $Redirect = "Main.php" . "?" . CCGetQueryString("QueryString", array("ccsForm"));
        if($this->Validate()) {
            if($this->PressedButton == "Insert") {
                if(!CCGetEvent($this->Insert->CCSEvents, "OnClick", $this->Insert) || !$this->InsertRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Update") {
                if(!CCGetEvent($this->Update->CCSEvents, "OnClick", $this->Update) || !$this->UpdateRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Delete") {
                if(!CCGetEvent($this->Delete->CCSEvents, "OnClick", $this->Delete) || !$this->DeleteRow()) {
                    $Redirect = "";
                }
            } else if($this->PressedButton == "Cancel") {
                if(!CCGetEvent($this->Cancel->CCSEvents, "OnClick", $this->Cancel)) {
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

//InsertRow Method @96-322C021F
    function InsertRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeInsert", $this);
        if(!$this->InsertAllowed) return false;
        $this->DataSource->task_name->SetValue($this->task_name->GetValue(true));
        $this->DataSource->task_desc->SetValue($this->task_desc->GetValue(true));
        $this->DataSource->project_id->SetValue($this->project_id->GetValue(true));
        $this->DataSource->user_assign_by->SetValue($this->user_assign_by->GetValue(true));
        $this->DataSource->priority_id->SetValue($this->priority_id->GetValue(true));
        $this->DataSource->status_id->SetValue($this->status_id->GetValue(true));
        $this->DataSource->type_id->SetValue($this->type_id->GetValue(true));
        $this->DataSource->user_id_assign_to->SetValue($this->user_id_assign_to->GetValue(true));
        $this->DataSource->task_start_date->SetValue($this->task_start_date->GetValue(true));
        $this->DataSource->task_finish_date->SetValue($this->task_finish_date->GetValue(true));
        $this->DataSource->Insert();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterInsert", $this);
        return (!$this->CheckErrors());
    }
//End InsertRow Method

//UpdateRow Method @96-C9000E19
    function UpdateRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeUpdate", $this);
        if(!$this->UpdateAllowed) return false;
        $this->DataSource->task_name->SetValue($this->task_name->GetValue(true));
        $this->DataSource->task_desc->SetValue($this->task_desc->GetValue(true));
        $this->DataSource->project_id->SetValue($this->project_id->GetValue(true));
        $this->DataSource->user_assign_by->SetValue($this->user_assign_by->GetValue(true));
        $this->DataSource->priority_id->SetValue($this->priority_id->GetValue(true));
        $this->DataSource->status_id->SetValue($this->status_id->GetValue(true));
        $this->DataSource->type_id->SetValue($this->type_id->GetValue(true));
        $this->DataSource->user_id_assign_to->SetValue($this->user_id_assign_to->GetValue(true));
        $this->DataSource->task_start_date->SetValue($this->task_start_date->GetValue(true));
        $this->DataSource->task_finish_date->SetValue($this->task_finish_date->GetValue(true));
        $this->DataSource->Update();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterUpdate", $this);
        return (!$this->CheckErrors());
    }
//End UpdateRow Method

//DeleteRow Method @96-299D98C3
    function DeleteRow()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeDelete", $this);
        if(!$this->DeleteAllowed) return false;
        $this->DataSource->Delete();
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterDelete", $this);
        return (!$this->CheckErrors());
    }
//End DeleteRow Method

//Show Method @96-B014A6FD
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

        $this->project_id->Prepare();
        $this->priority_id->Prepare();
        $this->status_id->Prepare();
        $this->type_id->Prepare();
        $this->user_id_assign_to->Prepare();

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
                if(!$this->FormSubmitted){
                    $this->task_name->SetValue($this->DataSource->task_name->GetValue());
                    $this->task_desc->SetValue($this->DataSource->task_desc->GetValue());
                    $this->project_id->SetValue($this->DataSource->project_id->GetValue());
                    $this->user_assign_by->SetValue($this->DataSource->user_assign_by->GetValue());
                    $this->priority_id->SetValue($this->DataSource->priority_id->GetValue());
                    $this->status_id->SetValue($this->DataSource->status_id->GetValue());
                    $this->type_id->SetValue($this->DataSource->type_id->GetValue());
                    $this->user_id_assign_to->SetValue($this->DataSource->user_id_assign_to->GetValue());
                    $this->task_start_date->SetValue($this->DataSource->task_start_date->GetValue());
                    $this->task_finish_date->SetValue($this->DataSource->task_finish_date->GetValue());
                }
            } else {
                $this->EditMode = false;
            }
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->task_name->Errors->ToString());
            $Error = ComposeStrings($Error, $this->task_desc->Errors->ToString());
            $Error = ComposeStrings($Error, $this->project_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->user_assign_by->Errors->ToString());
            $Error = ComposeStrings($Error, $this->priority_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->status_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->type_id->Errors->ToString());
            $Error = ComposeStrings($Error, $this->user_id_assign_to->Errors->ToString());
            $Error = ComposeStrings($Error, $this->task_start_date->Errors->ToString());
            $Error = ComposeStrings($Error, $this->task_finish_date->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DatePicker_task_start_date1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DatePicker_task_finish_date1->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Error = ComposeStrings($Error, $this->DataSource->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);
        $this->Insert->Visible = !$this->EditMode && $this->InsertAllowed;
        $this->Update->Visible = $this->EditMode && $this->UpdateAllowed;
        $this->Delete->Visible = $this->EditMode && $this->DeleteAllowed;

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->task_name->Show();
        $this->task_desc->Show();
        $this->project_id->Show();
        $this->user_assign_by->Show();
        $this->priority_id->Show();
        $this->status_id->Show();
        $this->type_id->Show();
        $this->user_id_assign_to->Show();
        $this->task_start_date->Show();
        $this->task_finish_date->Show();
        $this->Insert->Show();
        $this->Update->Show();
        $this->Delete->Show();
        $this->Cancel->Show();
        $this->DatePicker_task_start_date1->Show();
        $this->DatePicker_task_finish_date1->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
        $this->DataSource->close();
    }
//End Show Method

} //End task Class @96-FCB6E20C

class clstaskDataSource extends clsDBintranet {  //taskDataSource Class @96-A898CF8D

//DataSource Variables @96-02533A0F
    public $Parent = "";
    public $CCSEvents = "";
    public $CCSEventResult;
    public $ErrorBlock;
    public $CmdExecution;

    public $InsertParameters;
    public $UpdateParameters;
    public $DeleteParameters;
    public $wp;
    public $AllParametersSet;

    public $InsertFields = array();
    public $UpdateFields = array();

    // Datasource fields
    public $task_name;
    public $task_desc;
    public $project_id;
    public $user_assign_by;
    public $priority_id;
    public $status_id;
    public $type_id;
    public $user_id_assign_to;
    public $task_start_date;
    public $task_finish_date;
//End DataSource Variables

//DataSourceClass_Initialize Event @96-B9FB8309
    function clstaskDataSource(& $Parent)
    {
        $this->Parent = & $Parent;
        $this->ErrorBlock = "Record task/Error";
        $this->Initialize();
        $this->task_name = new clsField("task_name", ccsText, "");
        
        $this->task_desc = new clsField("task_desc", ccsMemo, "");
        
        $this->project_id = new clsField("project_id", ccsInteger, "");
        
        $this->user_assign_by = new clsField("user_assign_by", ccsText, "");
        
        $this->priority_id = new clsField("priority_id", ccsInteger, "");
        
        $this->status_id = new clsField("status_id", ccsInteger, "");
        
        $this->type_id = new clsField("type_id", ccsInteger, "");
        
        $this->user_id_assign_to = new clsField("user_id_assign_to", ccsInteger, "");
        
        $this->task_start_date = new clsField("task_start_date", ccsDate, $this->DateFormat);
        
        $this->task_finish_date = new clsField("task_finish_date", ccsDate, $this->DateFormat);
        

        $this->InsertFields["task_name"] = array("Name" => "task_name", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["task_desc"] = array("Name" => "task_desc", "Value" => "", "DataType" => ccsMemo, "OmitIfEmpty" => 1);
        $this->InsertFields["project_id"] = array("Name" => "project_id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["user_id_assign_by"] = array("Name" => "user_id_assign_by", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->InsertFields["priority_id"] = array("Name" => "priority_id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["status_id"] = array("Name" => "status_id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["type_id"] = array("Name" => "type_id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["user_id_assign_to"] = array("Name" => "user_id_assign_to", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->InsertFields["task_start_date"] = array("Name" => "task_start_date", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->InsertFields["task_finish_date"] = array("Name" => "task_finish_date", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["task_name"] = array("Name" => "task_name", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["task_desc"] = array("Name" => "task_desc", "Value" => "", "DataType" => ccsMemo, "OmitIfEmpty" => 1);
        $this->UpdateFields["project_id"] = array("Name" => "project_id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["user_id_assign_by"] = array("Name" => "user_id_assign_by", "Value" => "", "DataType" => ccsText, "OmitIfEmpty" => 1);
        $this->UpdateFields["priority_id"] = array("Name" => "priority_id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["status_id"] = array("Name" => "status_id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["type_id"] = array("Name" => "type_id", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["user_id_assign_to"] = array("Name" => "user_id_assign_to", "Value" => "", "DataType" => ccsInteger, "OmitIfEmpty" => 1);
        $this->UpdateFields["task_start_date"] = array("Name" => "task_start_date", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
        $this->UpdateFields["task_finish_date"] = array("Name" => "task_finish_date", "Value" => "", "DataType" => ccsDate, "OmitIfEmpty" => 1);
    }
//End DataSourceClass_Initialize Event

//Prepare Method @96-FE0A1536
    function Prepare()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->wp = new clsSQLParameters($this->ErrorBlock);
        $this->wp->AddParameter("1", "urltask_id", ccsInteger, "", "", $this->Parameters["urltask_id"], "", false);
        $this->AllParametersSet = $this->wp->AllParamsSet();
        $this->wp->Criterion[1] = $this->wp->Operation(opEqual, "task_id", $this->wp->GetDBValue("1"), $this->ToSQL($this->wp->GetDBValue("1"), ccsInteger),false);
        $this->Where = 
             $this->wp->Criterion[1];
    }
//End Prepare Method

//Open Method @96-7CD7478D
    function Open()
    {
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildSelect", $this->Parent);
        $this->SQL = "SELECT * \n\n" .
        "FROM tasks {SQL_Where} {SQL_OrderBy}";
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteSelect", $this->Parent);
        $this->query(CCBuildSQL($this->SQL, $this->Where, $this->Order));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteSelect", $this->Parent);
    }
//End Open Method

//SetValues Method @96-310CCAD3
    function SetValues()
    {
        $this->task_name->SetDBValue($this->f("task_name"));
        $this->task_desc->SetDBValue($this->f("task_desc"));
        $this->project_id->SetDBValue(trim($this->f("project_id")));
        $this->user_assign_by->SetDBValue($this->f("user_id_assign_by"));
        $this->priority_id->SetDBValue(trim($this->f("priority_id")));
        $this->status_id->SetDBValue(trim($this->f("status_id")));
        $this->type_id->SetDBValue(trim($this->f("type_id")));
        $this->user_id_assign_to->SetDBValue(trim($this->f("user_id_assign_to")));
        $this->task_start_date->SetDBValue(trim($this->f("task_start_date")));
        $this->task_finish_date->SetDBValue(trim($this->f("task_finish_date")));
    }
//End SetValues Method

//Insert Method @96-8C369471
    function Insert()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildInsert", $this->Parent);
        $this->InsertFields["task_name"]["Value"] = $this->task_name->GetDBValue(true);
        $this->InsertFields["task_desc"]["Value"] = $this->task_desc->GetDBValue(true);
        $this->InsertFields["project_id"]["Value"] = $this->project_id->GetDBValue(true);
        $this->InsertFields["user_id_assign_by"]["Value"] = $this->user_assign_by->GetDBValue(true);
        $this->InsertFields["priority_id"]["Value"] = $this->priority_id->GetDBValue(true);
        $this->InsertFields["status_id"]["Value"] = $this->status_id->GetDBValue(true);
        $this->InsertFields["type_id"]["Value"] = $this->type_id->GetDBValue(true);
        $this->InsertFields["user_id_assign_to"]["Value"] = $this->user_id_assign_to->GetDBValue(true);
        $this->InsertFields["task_start_date"]["Value"] = $this->task_start_date->GetDBValue(true);
        $this->InsertFields["task_finish_date"]["Value"] = $this->task_finish_date->GetDBValue(true);
        $this->SQL = CCBuildInsert("tasks", $this->InsertFields, $this);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteInsert", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteInsert", $this->Parent);
        }
    }
//End Insert Method

//Update Method @96-482111C5
    function Update()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildUpdate", $this->Parent);
        $this->UpdateFields["task_name"]["Value"] = $this->task_name->GetDBValue(true);
        $this->UpdateFields["task_desc"]["Value"] = $this->task_desc->GetDBValue(true);
        $this->UpdateFields["project_id"]["Value"] = $this->project_id->GetDBValue(true);
        $this->UpdateFields["user_id_assign_by"]["Value"] = $this->user_assign_by->GetDBValue(true);
        $this->UpdateFields["priority_id"]["Value"] = $this->priority_id->GetDBValue(true);
        $this->UpdateFields["status_id"]["Value"] = $this->status_id->GetDBValue(true);
        $this->UpdateFields["type_id"]["Value"] = $this->type_id->GetDBValue(true);
        $this->UpdateFields["user_id_assign_to"]["Value"] = $this->user_id_assign_to->GetDBValue(true);
        $this->UpdateFields["task_start_date"]["Value"] = $this->task_start_date->GetDBValue(true);
        $this->UpdateFields["task_finish_date"]["Value"] = $this->task_finish_date->GetDBValue(true);
        $this->SQL = CCBuildUpdate("tasks", $this->UpdateFields, $this);
        $this->SQL .= strlen($this->Where) ? " WHERE " . $this->Where : $this->Where;
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteUpdate", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteUpdate", $this->Parent);
        }
    }
//End Update Method

//Delete Method @96-85D07A3A
    function Delete()
    {
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->CmdExecution = true;
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeBuildDelete", $this->Parent);
        $this->SQL = "DELETE FROM tasks";
        $this->SQL = CCBuildSQL($this->SQL, $this->Where, "");
        if (!strlen($this->Where) && $this->Errors->Count() == 0) 
            $this->Errors->addError($CCSLocales->GetText("CCS_CustomOperationError_MissingParameters"));
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeExecuteDelete", $this->Parent);
        if($this->Errors->Count() == 0 && $this->CmdExecution) {
            $this->query($this->SQL);
            $this->CCSEventResult = CCGetEvent($this->CCSEvents, "AfterExecuteDelete", $this->Parent);
        }
    }
//End Delete Method

} //End taskDataSource Class @96-FCB6E20C

//Include Page implementation @134-E87ED294
include_once(RelativePath . "/VerticalMenu.php");
//End Include Page implementation

class clsRecordsearch { //search Class @136-F6554AC1

//Variables @136-9E315808

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

//Class_Initialize Event @136-BA70D26E
    function clsRecordsearch($RelativePath, & $Parent)
    {

        global $FileName;
        global $CCSLocales;
        global $DefaultDateFormat;
        $this->Visible = true;
        $this->Parent = & $Parent;
        $this->RelativePath = $RelativePath;
        $this->Errors = new clsErrors();
        $this->ErrorBlock = "Record search/Error";
        $this->ReadAllowed = true;
        if($this->Visible)
        {
            $this->ComponentName = "search";
            $this->Attributes = new clsAttributes($this->ComponentName . ":");
            $CCSForm = explode(":", CCGetFromGet("ccsForm", ""), 2);
            if(sizeof($CCSForm) == 1)
                $CCSForm[1] = "";
            list($FormName, $FormMethod) = $CCSForm;
            $this->FormEnctype = "application/x-www-form-urlencoded";
            $this->FormSubmitted = ($FormName == $this->ComponentName);
            $Method = $this->FormSubmitted ? ccsPost : ccsGet;
            $this->DoSearch = new clsButton("DoSearch", $Method, $this);
            $this->keywords = new clsControl(ccsTextBox, "keywords", "keywords", ccsText, "", CCGetRequestParam("keywords", $Method, NULL), $this);
        }
    }
//End Class_Initialize Event

//Validate Method @136-8B78DFE5
    function Validate()
    {
        global $CCSLocales;
        $Validation = true;
        $Where = "";
        $Validation = ($this->keywords->Validate() && $Validation);
        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "OnValidate", $this);
        $Validation =  $Validation && ($this->keywords->Errors->Count() == 0);
        return (($this->Errors->Count() == 0) && $Validation);
    }
//End Validate Method

//CheckErrors Method @136-4125F47D
    function CheckErrors()
    {
        $errors = false;
        $errors = ($errors || $this->keywords->Errors->Count());
        $errors = ($errors || $this->Errors->Count());
        return $errors;
    }
//End CheckErrors Method

//MasterDetail @136-ED598703
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

//Operation Method @136-F97EFF03
    function Operation()
    {
        if(!$this->Visible)
            return;

        global $Redirect;
        global $FileName;

        if(!$this->FormSubmitted) {
            return;
        }

        if($this->FormSubmitted) {
            $this->PressedButton = "DoSearch";
            if($this->DoSearch->Pressed) {
                $this->PressedButton = "DoSearch";
            }
        }
        $Redirect = "Main.php";
        if($this->Validate()) {
            if($this->PressedButton == "DoSearch") {
                $Redirect = "Main.php" . "?" . CCMergeQueryStrings(CCGetQueryString("Form", array("DoSearch", "DoSearch_x", "DoSearch_y")));
                if(!CCGetEvent($this->DoSearch->CCSEvents, "OnClick", $this->DoSearch)) {
                    $Redirect = "";
                }
            }
        } else {
            $Redirect = "";
        }
    }
//End Operation Method

//Show Method @136-095A041D
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


        $RecordBlock = "Record " . $this->ComponentName;
        $ParentPath = $Tpl->block_path;
        $Tpl->block_path = $ParentPath . "/" . $RecordBlock;
        $this->EditMode = $this->EditMode && $this->ReadAllowed;
        if (!$this->FormSubmitted) {
        }

        if($this->FormSubmitted || $this->CheckErrors()) {
            $Error = "";
            $Error = ComposeStrings($Error, $this->keywords->Errors->ToString());
            $Error = ComposeStrings($Error, $this->Errors->ToString());
            $Tpl->SetVar("Error", $Error);
            $Tpl->Parse("Error", false);
        }
        $CCSForm = $this->EditMode ? $this->ComponentName . ":" . "Edit" : $this->ComponentName;
        $this->HTMLFormAction = $FileName . "?" . CCAddParam(CCGetQueryString("QueryString", ""), "ccsForm", $CCSForm);
        $Tpl->SetVar("Action", !$CCSUseAmp ? $this->HTMLFormAction : str_replace("&", "&amp;", $this->HTMLFormAction));
        $Tpl->SetVar("HTMLFormName", $this->ComponentName);
        $Tpl->SetVar("HTMLFormEnctype", $this->FormEnctype);

        $this->CCSEventResult = CCGetEvent($this->CCSEvents, "BeforeShow", $this);
        $this->Attributes->Show();
        if(!$this->Visible) {
            $Tpl->block_path = $ParentPath;
            return;
        }

        $this->DoSearch->Show();
        $this->keywords->Show();
        $Tpl->parse();
        $Tpl->block_path = $ParentPath;
    }
//End Show Method

} //End search Class @136-FCB6E20C







//Initialize Page @1-918BD567
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
$TemplateFileName = "Main.html";
$BlockToParse = "main";
$TemplateEncoding = "UTF-8";
$ContentType = "text/html";
$PathToRoot = "./";
$Charset = $Charset ? $Charset : "utf-8";
//End Initialize Page

//Before Initialize @1-E870CEBC
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeInitialize", $MainPage);
//End Before Initialize

//Initialize Objects @1-392A1CEB
$DBintranet = new clsDBintranet();
$MainPage->Connections["intranet"] = & $DBintranet;
$Attributes = new clsAttributes("page:");
$MainPage->Attributes = & $Attributes;

// Controls
$Header = new clsHeader("", "Header", $MainPage);
$Header->Initialize();
$Footer = new clsFooter("", "Footer", $MainPage);
$Footer->Initialize();
$advanced_search = new clsRecordadvanced_search("", $MainPage);
$tasks = new clsGridtasks("", $MainPage);
$task = new clsRecordtask("", $MainPage);
$VerticalMenu = new clsVerticalMenu("", "VerticalMenu", $MainPage);
$VerticalMenu->Initialize();
$search = new clsRecordsearch("", $MainPage);
$MainPage->Header = & $Header;
$MainPage->Footer = & $Footer;
$MainPage->advanced_search = & $advanced_search;
$MainPage->tasks = & $tasks;
$MainPage->task = & $task;
$MainPage->VerticalMenu = & $VerticalMenu;
$MainPage->search = & $search;
$tasks->Initialize();
$task->Initialize();

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

//Execute Components @1-611FF482
$Header->Operations();
$Footer->Operations();
$advanced_search->Operation();
$task->Operation();
$VerticalMenu->Operations();
$search->Operation();
//End Execute Components

//Go to destination page @1-10903870
if($Redirect)
{
    $CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
    $DBintranet->close();
    header("Location: " . $Redirect);
    $Header->Class_Terminate();
    unset($Header);
    $Footer->Class_Terminate();
    unset($Footer);
    unset($advanced_search);
    unset($tasks);
    unset($task);
    $VerticalMenu->Class_Terminate();
    unset($VerticalMenu);
    unset($search);
    unset($Tpl);
    exit;
}
//End Go to destination page

//Show Page @1-F2A05C95
$Header->Show();
$Footer->Show();
$advanced_search->Show();
$tasks->Show();
$task->Show();
$VerticalMenu->Show();
$search->Show();
$Tpl->block_path = "";
$Tpl->Parse($BlockToParse, false);
if (!isset($main_block)) $main_block = $Tpl->GetVar($BlockToParse);
$main_block = CCConvertEncoding($main_block, $FileEncoding, $TemplateEncoding);
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeOutput", $MainPage);
if ($CCSEventResult) echo $main_block;
//End Show Page

//Unload Page @1-68C333E2
$CCSEventResult = CCGetEvent($CCSEvents, "BeforeUnload", $MainPage);
$DBintranet->close();
$Header->Class_Terminate();
unset($Header);
$Footer->Class_Terminate();
unset($Footer);
unset($advanced_search);
unset($tasks);
unset($task);
$VerticalMenu->Class_Terminate();
unset($VerticalMenu);
unset($search);
unset($Tpl);
//End Unload Page


?>
