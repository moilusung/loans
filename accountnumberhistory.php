<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>
<link rel="stylesheet" href="css/bootstrap.min.css">
<script src="js/bootstrap.min.js"></script>
<style type="text/css">
    body{
        font-family: Arail, sans-serif;
    }
    /* Formatting search box */
    .search-box{
        width: 330px;
        position: relative;
        display: inline-block;
        font-size: 12px;
    }
    .search-box input[type="text"]{
        height: 25px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        font-size: 12px;
    }
    .result{
        position: absolute;
        z-index: 999;
        top: 100%;
        left: 0;
    }
    .search-box input[type="text"], .result{
        width: 100%;
        box-sizing: border-box;
    }
    .result p{
        margin: 0;
        padding: 7px 10px;
        border: 1px solid #CCCCCC;
        border-top: none;
        cursor: pointer;
        background: white;
    }
    .result p:hover{
        background: #f2f2f2;
    }
    .input-xs {
    height: 20px;
    padding: 2px 5px;
    font-size: 12px;
    line-height: 1.5; /* If Placeholder of the input is moved up, rem/modify this. */
    border-radius: 3px;
</style>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("backend-search-history.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });

    $(document).on("click", ".result p", function(){
        $(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        $(this).parent(".result").empty();
        // echo $(this).text;
    });
});
</script>

<div class="col">
<label class="text-right" style='font-size:14px;font-family:calibri;margin:0px'><b>Loan History</b></label>
<div class="row">
<div class="col-0">
<div class="search-box">
<form class="" method="post">
  <input type="text" name="acc1" style="margin:0px" class="form-control input-lg" required autocomplete="off" placeholder="Search Client Name..." value="<?php if (isset($_POST['btnSearch'])){ echo $_POST['acc1']; } ?>"/>
  <div class="result"></div>
  </div>
</div>
<div class="col text-left">
<input class="btn btn-sm btn-primary input-sm " type="submit" name="btnSearch" value="Generate">
</div>
</div>
</div>
</form>
