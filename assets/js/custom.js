


function codechecker_re(codeChecker)
{
    var xhr;
     if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE 8 and older
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    var data = "usercode=" + codeChecker;
    xhr.open("POST", "codechecker.php", true); 
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
    xhr.send(data);
    xhr.onreadystatechange = display_data;

    function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                if(xhr.responseText == 'true'){
                    jQuery('#codeChecker').hide(0);
                    jQuery('#catListing').fadeIn('slow');
                }else if(xhr.responseText == 'already'){
                    alert('Enter a valid code to start the quiz, seems you already attempt this test.');
                }else{
                    alert('Enter a valid code to start the quiz.');    
                }
            }
        }
    }

}


function submitFormData_re(formData, userCode, showNext)
{
    var xhr;
     if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE 8 and older
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    console.log(formData);
    var data = "userCode=" + userCode+ "&data="+formData;
    xhr.open("POST", "submitData.php", true); 
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
    xhr.send(data);
    xhr.onreadystatechange = display_data;

    function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                console.log(xhr.responseText);
                if(xhr.responseText == 'true'){
                    $('.testSect').hide(0);
                   $('#'+showNext).fadeIn('slow');
                }else{
                    alert('Something went wrong!');
                }
            }
        }
    }

}


function generate_mail(userCode)
{
    var xhr;
     if (window.XMLHttpRequest) { // Mozilla, Safari, ...
        xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) { // IE 8 and older
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
    }

    var data = "userCode="+userCode;
    xhr.open("POST", "sendMail.php", true); 
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");                  
    xhr.send(data);
    xhr.onreadystatechange = display_data;

    function display_data() {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                if(xhr.responseText == 'true'){
                    alert('Email has been sent.')
                }
            }
        }
    }

}

// Menu Toggle
//========================================

jQuery(document).ready(function(){

    jQuery("#messageFirst").on('keyup', function() {
       var words = this.value.match(/\S+/g).length;
       console.log(words);
       if (words > 300) {
           // Split the string on first 200 words and rejoin on spaces
           var trimmed = $(this).val().split(/\s+/, 300).join(" ");
           // Add a space at the end to keep new typing making new words
           jQuery(this).val(trimmed + " ");
           alert('Words limit ends.');
       }

   });

    var dataArr = [];

    /*TestForms Listng Script from catListing*/
    jQuery(".sendCode").on("click", function(event){
        event.preventDefault();
        var userCode = $(this).attr('data-attr_userCode');
        generate_mail(userCode);
    });

    /*Dynamic height for Image Script*/
    var wisdom_home_height = jQuery(window).height();
    jQuery('.hc-inner-wrp').css('height', wisdom_home_height);
    jQuery( window ).resize(function() {
        var wisdom_home_height = jQuery(window).height();
        jQuery('.hc-inner-wrp').css('height', wisdom_home_height);
    });

    /*CodeChecker Script*/
    jQuery("#codeCheckSubmit").submit(function(event){
        event.preventDefault();
        var $inputs = jQuery('#codeCheckSubmit :input');
        var values = {};
        $inputs.each(function() {
            if($(this).val() != ''){
                var codeChecker = $(this).val();
                codechecker_re(codeChecker);
                $('#codeCheck').attr('data-attr_code', codeChecker);
            }
        });
    });

    /*TestForms Listng Script from catListing*/
    jQuery(".wisdom-form-categories .wfc-items button").on("click", function(event){
        event.preventDefault();
        var quesCat = jQuery(this).attr('data-attr_topic');
        jQuery('#catListing').hide(0);
        jQuery('#'+quesCat+'Sect').fadeIn('slow');
    });

    
    /*Prev action Script*/
    jQuery(".prevButton").on("click", function(event){
        event.preventDefault();
        var showPrev = $(this).attr('data-attr_prev');
        $('.testSect').hide(0);
        $('#'+showPrev).fadeIn('slow');
    });

    /*Submit Test Script*/

    jQuery("#submitQuiz").on("click", function(event){
        event.preventDefault();
        var showNext = 'thankuSect';
        var userCode = $('#codeCheck').attr('data-attr_code');
        
        var dataChecker = 'true';
        $('body').find('.wisdom-form-categories .wfc-items').each(function() {
            if (!$(this).hasClass("testComplete")) {
              dataChecker = 'false';
            }
        });

        if(dataChecker == 'true'){
            var bundledDataJson = JSON.stringify(dataArr);
            console.log(bundledDataJson);
            submitFormData_re(bundledDataJson, userCode, showNext);    
        }else{
            alert('All Questions are Mandatory to Answer');
        }
        
    });
     

    jQuery(".wisdom-form").submit(function(event){
        event.preventDefault();

        var showNext = $(this).attr('data-attr_next');
        var curattr = $(this).attr('data-attr_cur');

        if(showNext == 'catListing'){
            var temp_bundled = {};
            
            var questionType  = $(this).find("input[name=question_type]").val();
            var commentsFirst  = $(this).find("textarea#messageFirst").val();
            var userCode  = $('#codeCheck').attr('data-attr_code');
           

            function htmlEscape(str) {
                return str
                    .replace(/&/g, 'aswqaswa')
            }

            var scholrtxt = htmlEscape(commentsFirst)
            var myStr = scholrtxt.replace(/"/g, '');

            console.log(commentsFirst);
            console.log(htmlEscape(myStr) );


            temp_bundled.type  = questionType;
            temp_bundled.commentsFirst = htmlEscape(myStr);
            

            for(var i = 0; i < dataArr.length; i++) {
                if(dataArr[i].type == questionType) {
                    dataArr.splice(i, 1);
                    break;
                }
            }
            dataArr.push(temp_bundled);

            $('.testSect').hide(0);
            $('#'+showNext).fadeIn('slow');
            $('.wisdom-form-categories').find('.'+curattr+'Cat').addClass('testComplete');

            

        }else{
            var temp_bundled = {};
            var questionType  = $(this).find("input[name=question_type]").val();
            var questionFirst = $(this).find("input[name=questionFirst]:checked").val();
            var questionSec   = $(this).find("input[name=questionSec]:checked").val();

            console.log('Val: '+questionFirst+': '+questionSec);

            temp_bundled.type  = questionType;
            temp_bundled.questionFirst = questionFirst;
            temp_bundled.questionSec   = questionSec;
            for(var i = 0; i < dataArr.length; i++) {
                if(dataArr[i].type == questionType) {
                    dataArr.splice(i, 1);
                    break;
                }
            }

            dataArr.push(temp_bundled);

            $('.testSect').hide(0);
            $('#'+showNext).fadeIn('slow');
            $('.wisdom-form-categories').find('.'+curattr+'Cat').addClass('testComplete');

        }

        
    });


    
}); //ready