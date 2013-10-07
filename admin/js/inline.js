function RepeatCategories(obj) {
	if($(obj).parent().children('div').length < 5){
		var currentDiv = $(obj).prev("div");
		
		var clone = currentDiv.clone(false);
		clone.find('select.subcategory').children('option:not(:first)').remove();
		
		if(clone.find('.deleteThis').length==0){
			clone.append(' <a href="X" class="deleteThis">X</a>');
		}
		
		clone.insertAfter(currentDiv)
			.find('select.category, select.subcategory').attr('style', '').next('span, a').remove();
		
		clone.find('select.category, select.subcategory').selectmenu({style: 'dropdown',
				transferClasses: true,
				width: null
		});
		$('.modal .category:last').trigger('change');
	}
}

function RepeatInspiration(obj) {
	if($(obj).parent().children('div').length < 10){
		var currentDiv = $(obj).prev("div");
		
		var clone = currentDiv.clone(false);
		
		if(clone.find('.deleteThis').length==0){
			clone.append('<a href="X" class="deleteThis">X</a>');
		}
		clone.insertAfter(currentDiv)
			.find('select.inspiration').attr('style', '').next('span, a').remove();
			
		clone.find('select.inspiration').selectmenu({style: 'dropdown',
				transferClasses: true,
				width: null
		});	
	}
}

function RepeatColor(obj) {
	if($(obj).parent().children('div').length < 10){
		var currentDiv = $(obj).prev("div");
		
		var clone = currentDiv.clone(false);
		
		if(clone.find('.deleteThis').length==0){
			clone.append('<a href="X" class="deleteThis">X</a>');
		}
		clone.insertAfter(currentDiv)
			.find('select.color').attr('style', '').next('span, a').remove();
			
		clone.find('select.color').selectmenu({style: 'dropdown',
				transferClasses: true,
				width: null
		});	
	}
}

$('.deleteThis').live("click", function(e){
	var outerContainer = $(this).parent().parent();
	
	if(outerContainer.children('div').length > 1){
		$(this).parent().fadeOut("normal", function() {
			$(this).remove();
			if(outerContainer.children('div').length==1){
				outerContainer.children('div').find('.deleteThis').remove();
			}
		});
    }
    
    e.preventDefault();
});

function Repeat(obj) {
	var currentDiv = $(obj).prev("div");
	currentDiv.clone().insertAfter(currentDiv).find('label').remove();
}

function Delete(obj) {
	if($(obj).parent().children('div').length > 1){
		var currentDiv = $(obj).prev().prev("div");
		//currentDiv.remove();
		currentDiv.fadeOut("normal", function() {
			$(this).remove();
		});
	}
}

function photoDelete(product_id, image_id){
		return true;
}

function reInit(){
	$(".modal input[type=radio], input[type=checkbox]").each(function() {
        if ($(this).parents("table").length === 0) {
            $(this).customInput();
        }
        //Fix status checkbox not posting
        $('.modal input[type="radio"][name="status"]').change(function() {
     		if(this.checked) {
     			$('#user_status').val((this.value));
     		}
     	});
     	//Fix status checkbox not posting
        $('.modal input[type="radio"][name="gender"]').change(function() {
     		if(this.checked) {
     			$('#user_gender').val((this.value));
     		}
     	});
    });
    
    // BUTTON LINKS
    $(".modal a.button").wrapInner("<span></span>");
    $(".modal a.button, button, .pager img").hover(
		function() {
			$(this).stop().fadeTo(200, 0.7);
		}, function() {
			$(this).stop().fadeTo(200, 1.0);
		}
    );

    // STYLE FILE BUTTON
    $(".modal input[type=file][class=showfile]").wrap("<div style='display : inline-block; overflow : hidden; width : auto; height : 27px;'></div>");
    $(".modal input[type=file][class=showfile]").filestyle({
        imageheight: 27,
        imagewidth: 65,
        width: 166
    });
    
    
    $('.modal select').not("select.multi").selectmenu({
        style: 'dropdown',
        transferClasses: true,
        width: null
    });
    
     // FORMS
    $('.modal .line:odd').css({
        "border-top": "2px solid #f2f4f7",
        "border-bottom": "2px solid #f2f4f7"
    });
    
    $('.modal .line:first-child').css({
        "border-top": "none"
    });
    $('.modal .line:last-child').css({
        "border-bottom": "none"
    });
	
	$('.modal input.datepicker').datepicker({
		dateFormat: 'mm/dd/yy'
	});
	
	$('.modal input.timepicker').datetimepicker({});
	
	// SET UP FORM VALIDATION
	$('.modal form').validate();
    
}

$('.category').live("change", function(){
	var sel_cat = $(this).val();
	var ele = $(this).parent().find('.subcategory');
	
	// refresh is not working, so delete element and recreate to get options to load
	ele.remove();
	var delLink = $(this).parent().find('a.deleteThis');
	var cloneLink = delLink.clone();
	delLink.remove();
	
	$(this).parent().append("<select name='subcategory[]' class='subcategory'><option>(Optional)</option></select>").append(' ').append(cloneLink);
	ele = $(this).parent().find('.subcategory');
	
	ele.children('option:not(:first)').remove();

	$.each (cat, function (index) {
		if( sel_cat == cat[index]['id'] ){
			$.each(cat[index]['sub_categories'], function(item) {
				var id = cat[index]['sub_categories'][item]['id'];
				var text = cat[index]['sub_categories'][item]['name'];
					
				$(ele).append(	
					$('<option></option>').val(id).html(text)
				);
			});
		}
	});

	ele.selectmenu({style: 'dropdown',
		transferClasses: true,
		width: null
	});	
	
	// refresh is not working after adding options
	//ele.selectmenu('refresh', true);
	
});

$('.source').live("change", function(){
	var sel_source = $(this).val();
	var ele = $(this).parent().find('.subsource');
	
	// refresh is not working, so delete element and recreate to get options to load
	ele.remove();
	var delLink = $(this).parent().find('a.deleteThis');
	var cloneLink = delLink.clone();
	delLink.remove();
	
	$(this).parent().append("<select name='subsource[]' class='subsource'><option>(Optional)</option></select>").append(' ').append(cloneLink);
	ele = $(this).parent().find('.subsource');
	
	ele.children('option:not(:first)').remove();

	$.each (source, function (index) {
		if( sel_source == source[index]['id'] ){
			$.each(source[index]['sub_source'], function(item) {
				var id = source[index]['sub_source'][item]['id'];
				var text = source[index]['sub_source'][item]['name'];
					
				$(ele).append(	
					$('<option></option>').val(id).html(text)
				);
			});
		}
	});

	ele.selectmenu({style: 'dropdown',
		transferClasses: true,
		width: null
	});	
	
	// refresh is not working after adding options
	//ele.selectmenu('refresh', true);
	
});

$(document).ready(function() {

	// CUSTOM VALIDATE
	$.validator.addMethod("uniqueEmail", function(value, element) {
		var isSuccess = false;
		
		$.ajax({ url: "ajax-validate-functions.php", 
			data: {'f' : 'checkEmail', 'email' : value, 'user_id' : $('#user_id').val() }, 
			async: false, 
			success: 
				function(msg) { isSuccess = msg === "true" ? true : false }
		  });
	
		return isSuccess;
	}, "Email address already registered.");

	$.validator.addMethod("password-match", function(value, element) {
		var isSuccess = true;
		
		if($('#password1').val() != "" || $('#password2').val() != ""){
			if($('#password1').val() != $('#password2').val()){
				isSuccess = false;
			}
		}
		return isSuccess;
	}, "Passwords must match");

	$.validator.addMethod("phoneUS", function(phone_number, element) {
		phone_number = phone_number.replace(/\s+/g, ""); 
		return this.optional(element) || phone_number.length > 9 &&
			phone_number.match(/^(1-?)?(\([2-9]\d{2}\)|[2-9]\d{2})-?[2-9]\d{2}-?\d{4}$/);
	}, "Please specify a valid phone number");

	$.validator.addMethod("twitter", function(twitter, element) {
		if(twitter=="") return true;
		var regTwit = /@([A-Za-z0-9_]+)/;
		return twitter.match(regTwit);
	}, "Please use the  @yourhandle format.");

	$.validator.addMethod("facebook", function(facebook, element) {
		if(facebook=="") return true;
		var FBurl = /^(http|https)\:\/\/www.facebook.com\/.*/i;
		return facebook.match(FBurl); 
	}, "Please enter a valid facebook URL.");

	$.validator.addMethod("linkedin", function(facebook, element) {
		if(linkedin=="") return true;
		var FBurl = /^(http|https)\:\/\/www.linkedin.com\/.*/i;
		return facebook.match(FBurl); 
	}, "Please enter a valid LinkedIn URL.");

	$.validator.addMethod("upc", function(upc, element) {
		if(upc=="") return true;
		var vmInpUPC = upc.substr(0, upc.length-1);
		var checkDigit = upc.slice(-1);
		var caclCheckDigit = "";
	
		vmLF=unescape("%0a");
		vmInpUPCLen=vmInpUPC.length;
	
		if (vmInpUPC.length<11) {
			return false;
		} else {
			var pattern = /\d/;
			if(!pattern.test(upc)){
				return false;
			}
		
			ymUPC=new Array();
			vmUPCStep1=0;
			vmUPCStep2=0;
			vmUPCStep3=0;
			vmUPCStep4=0;
			vmUPCStep4Len=0;
			vmUPCStr="";
			vmUPCEnd=0;
	
			for (j=0;j<vmInpUPCLen;j++) {
				ymUPC[j]=vmInpUPC.substring(j,j+1);
			}
	
			vmUPCStep1=ymUPC[0]*1+ymUPC[2]*1+ymUPC[4]*1+ymUPC[6]*1+ymUPC[8]*1+ymUPC[10]*1;
			vmUPCStep2=vmUPCStep1*3;
			vmUPCStep3=ymUPC[1]*1+ymUPC[3]*1+ymUPC[5]*1+ymUPC[7]*1+ymUPC[9]*1;
			vmUPCStep4=vmUPCStep2*1+vmUPCStep3*1;
	
			vmUPCStr=vmUPCStep4.toString();
			vmUPCStep4Len=vmUPCStr.length;
			vmUPCEnd=vmUPCStr.substring(vmUPCStep4Len-1,vmUPCStep4Len);
	
			// Our check digit
			if (vmUPCEnd=="0") {caclCheckDigit=0;}
			else if (vmUPCEnd=="1") {caclCheckDigit=9;}
			else if (vmUPCEnd=="2") {caclCheckDigit=8;}
			else if (vmUPCEnd=="3") {caclCheckDigit=7;}
			else if (vmUPCEnd=="4") {caclCheckDigit=6;}
			else if (vmUPCEnd=="5") {caclCheckDigit=5;}
			else if (vmUPCEnd=="6") {caclCheckDigit=4;}
			else if (vmUPCEnd=="7") {caclCheckDigit=3;}
			else if (vmUPCEnd=="8") {caclCheckDigit=2;}
			else if (vmUPCEnd=="9") {caclCheckDigit=1;}
			
			if(checkDigit == caclCheckDigit){
				return true;
			} else {
				return false;
			}	
		}
	}, "Please enter a valid 12 digit UPC");	

    // MENU
    $(".menu-search ul li li:first-child a").css("border-top", "none");
    $(".menu-search ul").supersubs({
        minWidth: 15,
        maxWidth: 40
    }).superfish({
        autoArrows: false,
        dropShadows: false
    });

    var htmlStr = $("code").html();
    $("code").text(htmlStr);

    // BOX HIDE
	/*
    $('span.hide').click(function() {
      $(this).parent().next('.content').fadeOut(100);
		$(this).addClass('show');
		$(this).removeClass('hide');
	});
	$('span.show').click(function() {
      $(this).parent().next('.content').fadeIn(100);
		$(this).addClass('show');
		$(this).removeClass('hide');
	});  */
		
	$('span.hide').click(function() {
		if ($(this).parent().next('.content').is(':visible'))
		{
        $(this).parent().next('.content').fadeOut(100);
		$(this).css('background','url(gfx/box-show.png) no-repeat left top transparent');
		}
		else
		{
        $(this).parent().next('.content').fadeIn(100);
		$(this).css('background','url(gfx/box-hide.png) no-repeat left top transparent');
		}
    });  

    // TITLE SEARCH BOX
    $('.box-search').hide();
    $('span.search').click(function() {
        $('.box-search').fadeTo(800, 1.0).end();
        $('span.search').hide();
    });

    // THUMB OPTIONS
    $("a.zoom").fancybox({
        'speedIn': 600,
        'speedOut': 200,
        'overlayShow': true,
        'overlayColor': '#000',
        'titlePosition': 'over'
    });
    $("img.shadow").wrap("<span class='shadow'></span>");
    $("img.left").wrap("<span class='shadow left'></span>");
    $("img.right").wrap("<span class='shadow right'></span>");
    $(function() {
        $("div.thumb").hover(
        function() {
            $(this).children("img").fadeTo(200, 0.85).end().children("div").show();
        }, function() {
            $(this).children("img").fadeTo(200, 1).end().children("div").hide();
        });
    });

    // SYSTEM MESSAGES
    $(".messages:first-child").css({
        "margin": "0 0 1px"
    });

    // MESSAGE BOX
    $(".content .message:last-child").css({
        "border-bottom": "none",
        "padding": "12px 0 0"
    });

    if ($.browser.msie && $.browser.version.substr(0, 1) < 8) {
        $(".content .message:last-child").css({
            "border-bottom": "none",
            "padding": "11px 0 0"
        });
    }

    // MODAL BOXES
    $(function() {
        $(".modal").dialog({
            autoOpen: false,
            closeText: '',
            resizable: false,
            width: 700,
			height: 500
        });

        $('.modalopen').live('click', function() {
        	if(this.href != "" && this.href != "#" && this.href.substr(-1) != "#"){
        		$(".modal").load(this.href, reInit).dialog('option', 'title', $(this).attr('rel')).dialog('open');
        	} else {
        		$(".modal").dialog('open');
        	}
            return false;
        });
		$('.cancel').click(function() {
            $(".modal").dialog('close');
            
        });
    });

    // TABS, ACCORDIONS, TREEVIEW & TOOLTIPS
    $(".tabs").tabs({
        fx: {
            opacity: 'toggle'
        }
    });

    $(".accordion").accordion({
        autoHeight: false,
        navigation: true
    });

    $(".filetree").treeview({
        persist: "location",
        collapsed: true
    });

    $(".tooltip").tipsy();

    // DATATABLE
    $('table.all').not('.ajax').dataTable({
        "bInfo": false,
        "iDisplayLength": 25,
        "aLengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
        "sPaginationType": "full_numbers",
        "bPaginate": true,
        "aoColumnDefs": [{
            bSortable: true,
            aTargets: [0]}],
        "sDom": 't<plf>'
    });

    $('table.sortsearch').dataTable({
        "bInfo": false,
        "bPaginate": false,
        "aoColumnDefs": [{
            bSortable: false,
            aTargets: [0]}],
        "sDom": 't<plf>'
    });

    $('table.sorting').dataTable({
        "bInfo": false,
        "bPaginate": false,
        "bFilter": false,
        "aoColumnDefs": [{
            bSortable: false,
            aTargets: [0]}],
        "sDom": 't<plf>'
    });

    $(".dataTables_wrapper .dataTables_length select").addClass("entries");

    $(function() {
        $(".dataTables_paginate span.paginate_button").hover(
        function() {
            $(this).fadeTo(200, 0.85).end();
        }, function() {
            $(this).fadeTo(200, 1).end();
        });
    });

    // CHECK ALL PAGES
    $('.checkall').click(function() {
        $(this).parents('table').find(':checkbox').attr('checked', this.checked);
    });

    // BUTTON LINKS
    $("a.button").wrapInner("<span></span>");
    $("a.button, button, .pager img").hover(

    function() {
        $(this).stop().fadeTo(200, 0.7);
    }, function() {
        $(this).stop().fadeTo(200, 1.0);
    });

    // STYLE FILE BUTTON
    $("input[type=file][class=showfile]").wrap("<div style='display : inline-block; overflow : hidden; width : auto; height : 27px;'></div>");
    $("input[type=file][class=showfile]").filestyle({
        imageheight: 27,
        imagewidth: 65,
        width: 166
    });

    // SLIDER
    $(".range-slide div.slide").each(function() {
        values = $(this).attr('value').split(',');
        firstVal = values[0];
        secondVal = values[1];

        rangeInputfirst = $(this).siblings('input.amount-first');
        rangeInputsecond = $(this).siblings('input.amount-second');

        $(this).slider({
            values: [firstVal, secondVal],
            min: parseInt($(this).attr('min'), 0),
            max: parseInt($(this).attr('max'), 0),
            range: true,
            slide: function(event, ui) {
                $(this).siblings('input.amount-first').val("" + ui.values[0]);
                $(this).siblings('input.amount-second').val("" + ui.values[1]);
            }
        });
        rangeInputfirst.val("" + $(this).slider("values", 0));
        rangeInputsecond.val("" + $(this).slider("values", 1));
    });

    $(".signle-slide div.slide").each(function() {
        value = $(this).attr('value').split(',');
        firstVal = value;

        rangeSpan = $(this).siblings('input.amount');

        $(this).slider({
            value: [firstVal],
            min: parseInt($(this).attr('min'), 0),
            max: parseInt($(this).attr('max'), 0),
            slide: function(event, ui) {
                $(this).siblings('input.amount').val("" + ui.value);
            }
        });
        rangeSpan.val("" + $(this).slider("value"));
    });

    // PROGRESSBAR
    $(".progressbar div").progressbar({
        value: 100
    });

    // AUTOCOMPLETE
    $(function() {
        var availableTags = [
            "ActionScript",
            "AppleScript",
            "Asp",
            "BASIC",
            "C",
            "C++",
            "Clojure",
            "COBOL",
            "ColdFusion",
            "Erlang",
            "Fortran",
            "Groovy",
            "Haskell",
            "Java",
            "JavaScript",
            "Lisp",
            "Perl",
            "PHP",
            "Python",
            "Ruby",
            "Scala",
            "Scheme"
            ];
        $(".complete").autocomplete({
            source: availableTags
        });
    });

    // FORMS
    $(".line:odd").css({
        "border-top": "2px solid #f2f4f7",
        "border-bottom": "2px solid #f2f4f7"
    });
    $(".line:first-child").css({
        "border-top": "none"
    });
    $(".line:last-child").css({
        "border-bottom": "none"
    });
	
	$("input.datepicker").datepicker({
		dateFormat: 'mm.dd.yy'
	});
	
	$("input.timepicker").datetimepicker({});

    $(function() {
        $('.dataTables_length input, select').not("select.multi").selectmenu({
            style: 'dropdown',
            transferClasses: true,
            width: null
        });
    });

    $("input[type=radio], input[type=checkbox]").each(function() {
        if ($(this).parents("table").length === 0) {
            $(this).customInput();
        }
    });

    $('.hide-input input, .filter-box, .search input, .box-search input').click(function() {
        if (this.value === this.defaultValue) {
            this.value = '';
        }
    });

    $('.hide-input input, .filter-box, .search input, .box-search input').blur(function() {
        if (this.value === '') {
            this.value = this.defaultValue;
        }
    });

    // Input and textarea IE 7 fix
    if ($.browser.msie && $.browser.version.substr(0, 1) < 8) {
        $("input.tiny").wrap("<div class='input-tiny'></div>");
        $("input.small").wrap("<div class='input-small'></div>");
        $("input.medium").wrap("<div class='input-medium'></div>");
        $("input.big").wrap("<div class='input-big'></div>");
        $("input.xl").wrap("<div class='input-xl'></div>");
        $("textarea.small").wrap("<div class='textarea-small'></div>");
        $("textarea.medium").wrap("<div class='textarea-medium'></div>");
        $("textarea.big").wrap("<div class='textarea-big'></div>");
        $("textarea.xl").wrap("<div class='textarea-xl'></div>");
    }

    // WYSISWYG
    $('.wysiwyg').wysiwyg({
        css: "css/wysiwyg-editor.css",
        plugins: {
            rmFormat: {
                rmMsWordMarkup: true
            }
        }
    });

    // TABLE STATICS        
    $("table.statics").each(function() {
        var colors = [];
        $("table.statics thead th:not(:first)").each(function() {
            colors.push($(this).css("color"));
        });
        $(this).graphTable({
            series: 'columns',
            position: 'replace',
			width : '100%',
            height: '200px',
            colors: colors
        }, {
            xaxis: {
                tickSize: 1
            }
        });
    });

    $("table.statics-date").each(function() {
        var colors = [];
        var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        $("table.statics-date thead th:not(:first)").each(function() {
            colors.push($(this).css("color"));
        });
        $(this).graphTable({
            series: 'columns',
            position: 'replace',
			width : '100%',
            height: '200px',
            colors: colors,
            xaxisTransform: function(month) {
                var i = 0;
                while ((i < 12) && (month != months[i])) {
                    i++;
                }
                return i;
            }
        }, {
            xaxis: {
                tickSize: 1,
                tickFormatter: function(v, a) {
                    return months[v];
                }
            }
        });
    });

    $('.flot-graph').before('<div class="space"></div>');

    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css({
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5
        }).appendTo("body").fadeIn("fast");
    }

    var previousPoint = null;
    $(".flot-graph").bind("plothover", function(event, pos, item) {
        $("#x").text(pos.x);
        $("#y").text(pos.y);

        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                $("#tooltip").remove();
                var x = item.datapoint[0],
                    y = item.datapoint[1];

                showTooltip(item.pageX, item.pageY, "<b>" + item.series.label + "</b>: " + y);
            }
        }
        else {
            $("#tooltip").remove();
            previousPoint = null;
        }
    });

});