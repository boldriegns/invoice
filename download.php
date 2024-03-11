<?php
include('header.php');
?>

<h1>Download Invoice Report</h1>
<hr>

<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Specify Date Range</h4>
            </div>
            <div class="panel-body form-group form-group-sm">
                <form method="post" action="download_report.php">
                    <div class="row">
                    <div class="col-xs-4">
                            <select class="form-control" name="status">
                                <option value="open">Open</option>
                                <option value="paid">Paid</option>
                                <option value="all">all</option>
                            </select>
                        </div>
                        <div class="col-xs-4">
                            <input type="date" class="form-control" name="start_date" placeholder="Start Date" required>
                        </div>
                        <div class="col-xs-4">
                            <input type="date" class="form-control" name="end_date" placeholder="End Date">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 text-right margin-top"> <!-- Aligning button to the right -->
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>
