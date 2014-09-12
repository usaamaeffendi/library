<!DOCTYPE html>
<html>
<head>
<!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8" />
<title>Services <?php echo APP_NAME; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- Font CSS  -->
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400,600,700" />

<!-- Core CSS  -->
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/fonts/glyphicons_pro/glyphicons.min.css" />

<!-- Plugin CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendor/plugins/chosen/chosen.min.css" />

<!-- Theme CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/theme.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/pages.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/plugins.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/responsive.css" />
<style>
pre {
   background-color: ghostwhite;
   border: 1px solid silver;
   padding: 10px 20px;
   margin: 20px; 
   }
.json-key {
   color: brown;
   }
.json-value {
   color: navy;
   }
.json-string {
   color: olive;
   }
</style>
</head>
<body class="forms-page">
<!-- Start: Main -->
<div id="main"> 
  <!-- Start: Content -->
  <section>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-12">
              <div class="panel">
                <div class="panel-heading">
                  <div class="panel-title"> <i class="fa fa-pencil"></i> Fields </div>
				  <div class="panel-btns pull-right">
                    <div class="btn-group toggle-pager-style">
					  <button id="copy" type="button" class="btn btn-sm btn-default ">Copy Service Url</button>
					</div>
                  </div>
                </div>
                <div class="panel-body">
                  <form class="form-horizontal" role="form" >
                    <div class="form-group">
                      <label for="services-list" class="col-md-3 control-label">Select Service</label>
                      <div class="col-md-9">
                        <select class="form-control" id="services-list"></select>
                      </div>
                    </div>
					<h4 class="panel-body-title">Parameters</h4>
                  </form>
                </div>
				<div class="panel-footer" >
				  <div class="form-group">
					<input class="submit btn btn-blue" type="submit" value="Submit">
				  </div>
				</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row">
            <div class="col-md-12">
              <div class="panel">
                <div class="panel-heading">
                  <div class="panel-title"> <i class="fa fa-pencil"></i> Response </div>
                </div>
                <div class="panel-body">
					<pre id="json"></pre>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </section>
  <!-- End: Content --> 
</div>
<!-- End: Main --> 

<!-- Core Javascript - via CDN --> 
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script> 
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script> 

<script type="text/javascript" src="<?php echo base_url(); ?>assets/vendor/plugins/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="//www.steamdev.com/zclip/js/jquery.zclip.min.js"></script>
<script type="text/javascript">
 jQuery(document).ready(function() {

	//Init jquery spinner init - default
	$("#services-list").chosen();
	
	$.ajax({
		type: "POST",
		url: "<?php echo base_url("services"); ?>"
	}).done(function(response) {
		var $el = '';
		$(response.data).each(function(a,b){
			$el += '<option value="' + b + '" >' + b + '</option>';
		});
		$("#services-list").html($el);
		$("#services-list").trigger("chosen:updated");
		updateFields();
	});

	$("#services-list").change(updateFields);
	function updateFields(){
		$(".panel-body-title").nextAll().remove();
		$.ajax({
			type: "POST",
			url: "<?php echo base_url("services"); ?>",
			data: {method: $("#services-list").val()}
		}).done(function(response) {
			$(response.data).each(function(a,b){
				$el =	$('<div class="form-group">\
							  <label for="' + b + '" class="col-lg-2 control-label">' + b + '</label>\
							  <div class="col-md-10">\
								<div class="input-group">\
								  <input type="text" id="' + b + '" name="' + b + '" class="form-control fields" placeholder="' + b + '" />\
								</div>\
							  </div>\
							</div>');
				$("form").append($el);
			});
		});
	}

	$(".submit").click(function(){
		$.ajax({
			type: "POST",
			url: "<?php echo base_url("services"); ?>/" + $("#services-list").val(),
			data: $("form").serialize()
		}).done(function(response) {
			$('#json').html(library.json.prettyPrint(response));
		});
	});

if (!library)
  var library = {};
  library.json = {
   replacer: function(match, pIndent, pKey, pVal, pEnd) {
      var key = '<span class=json-key>';
      var val = '<span class=json-value>';
      var str = '<span class=json-string>';
      var r = pIndent || '';
      if (pKey)
         r = r + key + pKey.replace(/[": ]/g, '') + '</span>: ';
      if (pVal)
         r = r + (pVal[0] == '"' ? str : val) + pVal + '</span>';
      return r + (pEnd || '');
      },
   prettyPrint: function(obj) {
      var jsonLine = /^( *)("[\w]+": )?("[^"]*"|[\w.+-]*)?([,[{])?$/mg;
      return JSON.stringify(obj, null, 3)
         .replace(/&/g, '&amp;').replace(/\\"/g, '&quot;')
         .replace(/</g, '&lt;').replace(/>/g, '&gt;')
         .replace(jsonLine, library.json.replacer);
      }
   };

	$("#copy").zclip({
		path: 'http://www.steamdev.com/zclip/js/ZeroClipboard.swf',
		copy: function() {
			return "<?php echo base_url("services"); ?>/" + $("#services-list").val() + "?" + $("form").serialize();//$(this).data('copy');
		}
	});
});
</script>
</body>
</html>