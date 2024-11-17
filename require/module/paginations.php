<?php
define("DEFAULT_RECORD_LISTING", 15);

//pagination Headers
function listingstartfrom($Return = null)
{
    $RecordLimit = DEFAULT_RECORD_LISTING;

    // Get current page number
    if (isset($_GET["view_page"])) {
        $page = $_GET["view_page"];
    } else {
        $page = 1;
    }
    $start = ($page - 1) * $RecordLimit;

    if ($Return == null) {
        return null;
    } else {
        if ($Return == "start") {
            return $start;
        } elseif ($Return == "end") {
            return $RecordLimit;
        }
    }
}

//sql array count
function CountRecords($RecordsSQLArray)
{
    if (empty($RecordsSQLArray)) {
        $Count = 0;
    } else {
        $Count = count($RecordsSQLArray);
    }

    return $Count;
}

//pagination footers
function PaginationFooter(int $TotalItems = 0, $RedirectForAll = "index.php")
{

    $RecordLimit = DEFAULT_RECORD_LISTING;

    // Get current page number
    if (isset($_GET["view_page"])) {
        $page = $_GET["view_page"];
    } else {
        $page = 1;
    }

    $next_page = ($page + 1);
    $previous_page = ($page - 1);
    $NetPages = round(($TotalItems / $RecordLimit) + 0.5);
    if ($NetPages == 1) {
        $next_page = 1;
    }
?>
    <div class="col-md-12 flex-s-b mt-2 mb-1">
        <div class="">
            <h6 class="mb-0" style="font-size:0.75rem;color:grey;">Page <b><?php echo IfRequested("GET", "view_page", $page, false); ?></b> from <b><?php echo $NetPages; ?> </b> pages <br>Total <b><?php echo $TotalItems; ?></b> entries</h6>
        </div>
        <div class="flex">
            <span class="mr-1">
                <a href="?view_page=<?php echo $previous_page; ?>" class="btn btn-sm btn-default"><i class="fa fa-angle-double-left"></i></a>
            </span>
            <form>
                <input type="number" name="view_page" onchange="form.submit()" class="form-control  mb-0" min="1" max="<?php echo $NetPages; ?>" value="<?php echo IfRequested("GET", "view_page", 1, false); ?>">
            </form>
            <span class="ml-1">
                <a href="?view_page=<?php echo $next_page; ?>" class="btn btn-sm btn-default"><i class="fa fa-angle-double-right"></i></a>
            </span>
            <?php if (isset($_GET['view_page'])) { ?>
                <span class="ml-1">
                    <a href="<?php echo $RedirectForAll; ?>" class="btn btn-sm btn-danger mb-0"><i class="fa fa-times m-1"></i></a>
                </span>
            <?php } ?>
        </div>
    </div>
<?php
}

//function get serial nos
function SerialNo()
{
    $SerialNo = 0;
    global $listcounts;
    if (isset($_GET['view_page'])) {
        $view_page = $_GET['view_page'];
        if ($view_page == 1) {
            $SerialNo = 0;
        } else {
            $SerialNo = $listcounts * ($view_page - 1);
        }
    } else {
        $SerialNo = $SerialNo;
    }

    return $SerialNo;
}

//define constants
define("SERIAL_NO", SerialNo());
define("START_FROM", listingstartfrom("start"));
