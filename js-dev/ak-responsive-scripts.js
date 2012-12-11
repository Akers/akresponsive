/*Ie6 ?! just foget it!*/
if (jQuery.browser.msie && jQuery.browser.version <= 6)
    jQuery('<div class="msie-box">' + '<strong><a href="http://browsehappy.com/" title="点击升级浏览器" target="_blank">对不起，您正在使用的浏览器因版本过旧，本网站无法兼容，请点击更新您的浏览器，享受更优越的浏览体验！！</a></strong><br /><a href="http://browsehappy.com/" title="Click here to update" target="_blank">  Your browser is no longer supported. Click here to update...</a> </div>').appendTo('#container');

/**
 *@Author Akers
 * Banner animates
 */
(function($){
	//Banner box on hover animate
	$.fn.akBannerHover = function(){
		$(this).hover(function(){
			$(this).toggleClass("banner-hover", 150, "easeOutSine", function(){
				$("#featured .slid").toggleClass("slider-show", 300, "easeOutSine");
			});
		});
	};
	
	//Banner Slider Buttons actions
	$.fn.akBannerSlider = function(){
	//on hover
		$(this).hover(
			function(){
				$("#featured").hasClass("banner-hover") &&
					$(this).addClass(($(this).hasClass("pre") ? "pre" : "next")+"-hover", 300, "easeOutSine");
			},
			function(){
				$("#featured").hasClass("banner-hover") &&
					$(this).removeClass(($(this).hasClass("pre") ? "pre" : "next")+"-hover", 300, "easeOutSine");
			}
		)
	//on click
		.click(bannerSlid);
	};
	
	//Banner Slid animate, direction = "next"|"pre"
	function bannerSlid(){
		var direction = $(this).hasClass("pre") ? "pre" : "next";
		var bannerObjs = $("#featured .banner-content");
		var preWidth = parseInt(preWidth || bannerObjs.width());
		var maxLen = parseInt(preWidth * (bannerObjs.size() || 0));
		var curPos = parseInt($("#featured > div").css("margin-left").replace('px', ''));
//		alert('curPos:'+curPos+'  preWidth:'+preWidth+'   maxLen:'+maxLen);
		if(maxLen <= 0)
			showBannerMessager("God!! How Can You Do That !!!!!");
		else if(maxLen === preWidth)
			showBannerMessager("莫要按了，就这一个了，亲~");
		else{
			if(direction === 'next'){
				if(Math.abs(curPos) >= (maxLen-preWidth)){
					$("#featured > div").stop().css('margin-left', -(maxLen-preWidth));
					showBannerMessager("已经是最后一个了啊，亲~");
				}				
				else
					$("#featured > div").animate({'margin-left':curPos-preWidth+'px'}, 300);		
			}
			else if(direction === 'pre'){
				if(curPos >= 0){
					$("#featured > div").stop().css('margin-left', 0);
					showBannerMessager("已经是第一个了啊，亲~");
				}
				else
					$("#featured > div").stop().animate({'margin-left':curPos+preWidth+'px'}, 300);
			}
		}
	};
	
	/*bannerMessager use to display messages of banner
	 * params:
	 *	msg: message to display
	 *	delay: delay before hide default 200 (mirco sec)
	 *	autoHide: autoHide or not default true*/
	function showBannerMessager(msg){
		var delay = arguments[1] || 200;
		var autoHide = arguments[2] || true;
		
		var msgObj = msgObj || $("<div id='banner-messager'></div>");
		//create the messager element
		$("#banner-messager").length === 0 ? $("#featured").append(msgObj.text(msg)) : $("#banner-messager").text(msg);
		$("#banner-messager").stop().css({"opacity":0}).animate({"opacity":0.7}, 250, function(){
			setTimeout(function(){$("#banner-messager").animate({"opacity":0}, 250);},1000);
		});
		
	}
		
	
	$.fn.loadAkBanner = function(){
		return $(this);
	};
})(jQuery);


/**
 *@Author Akers
 * Menu Bar animates
 */
(function($){
	//fade the main navigation menu
    $.fn.fadeNav = function(obj){
        return this.each(function(){
            var o_h = $(obj).css("height");
            $(this).hover(
                function(){
                    $(this).navIn(function(){
                        if(obj.length > 0)
                            $(obj).css("height",0).css('visibility','visible').stop().animate({'height':o_h,'opacity':0.95}, 300);
                    });
                },
                function(){
                    $(this).navOut(function(){
                        if(obj.length > 0){
                            $(obj).children(".sub-menu").children(".sub-menu").each(function(){
                                 $(this).css('visibility','hidden');
                            });
                            $(obj).stop().animate({'height':0,'opacity':0}, 250, function(){
                                $(obj).css('visibility','hidden').css("height",o_h);
                            });
                        }
                        
                    });
                }
            );
        });
    }
	
	//fade the sub navigation menu
    $.fn.fadeSubNav = function(obj){
        return this.each(function(){
            $(this).hover(
                function(){
                    // $(this).stop().animate({'box-shadow':"0 0 30px"}, 200, function(){
                    $(this).navIn(function(){
                        $(obj).stop().css('visibility','visible').animate({'opacity':0.95,'left':'100%'}, 160);
                    });
                },
                function(){
                    $(this).navOut(function(){
                        $(obj).stop().animate({'opacity':0}, 100, function(){$(this).css({'visibility':'hidden','left':'80%'})});
                    });
                }
            );
        });
    }

	//Animate of navigation menu when mouse moved in
    $.fn.navIn = function(_callBack){
// alert("oh u touch me!");
        $(this).stop().animate({'box-shadow':"0 0 30px"}, 200, _callBack);
    }
    //Animate of navigation menu when mouse moved out
	$.fn.navOut = function(_callBack){
        $(this).stop().animate({'box-shadow':"0 0 0"}, 200, _callBack);
    }

})(jQuery);

//when document ready, oh that just like a main interface
jQuery(document).ready(function ($){
    
	$("#featured .slid").akBannerSlider();
	$("#featured").akBannerHover();
	
	//add nav arrow element Start
    $("#menu-catalog").children("li").each(function(){
        // $(this).fadeNav($(this).children("ul.sub-menu"));
        if($(this).children("ul.sub-menu").length > 0){
            $(this).children("a").append($("<span class='nav_arrow down'></span>"));
        }
    });
	
	//display arrows on menu items 
    $("#menu-catalog ul.sub-menu li").each(function(){
        // $(this).fadeSubNav($(this).children("ul.sub-menu"));
        if($(this).children("ul.sub-menu").length > 0){
            $(this).children("a").append($("<span class='nav_arrow right'>&#9658</span>"));
        }
    });
	
});

/**
 * @Author Akers
 * post singal page
 */
(function($){
    /**
     * @Author Akers
     * format links
     */
    $.fn.formatLinks2html = function(){
        var links = $(this).text();
        if(links != '' && links.length > 0){
            if(links = eval(links)){
                var tmp = $(this).children("a");
                $(this).empty();
                // $(this).append(tmp);
                // $(this).append('<ul></ul>');
                for(var i=0;i<links.length;i++)
                    $(this).append("<li><a target='_blank' href='"+links[i]['href']+"'>"+links[i]['text']+'</a></li>');
            }
        }
    }

    //hide links
    $.fn.akHideLinks = function(width, pos, time){
        var obj = this;
        $("#ak_links_box").animate({'opacity':0}, time/2, function(){
            $(this).hide();
            $(obj).animate({'width':width+'px'}, time);
        });
        
    }

    //show links
    $.fn.akShowLinks = function(width, pos, time){
        $(this).animate({'width':width+'px'}, time, function(){
            $("#ak_links_box").show().animate({'opacity':1}, time/2);
        });
        // $("#ak_links_box").animate({'right':0}, time);
    }

    //ak_intro_box
    $(document).ready(function(){
        //format the links from json to html
        $("#ak_links_box > ul").formatLinks2html();

        //this links box effects
        var maxW = $("#work_infoes").width();
        var lnkW = $("#ak_links_box").width();
        $("#ak_links_box").css('opacity', 0).hide();
        var minW = $("#work_infoes").width();
        $("#work_infoes").css('width', minW+'px');
        // download button on click
        $("#ak_download_btn").click(function(){
            if($("#work_infoes").width()>minW)
                $("#work_infoes").akHideLinks(minW, lnkW, 500);
            else
                $("#work_infoes").akShowLinks(maxW, 0, 500);
        });

        //hide links button
        $("#ak_links_back_btn").click(function(){
            $("#work_infoes").akHideLinks(minW, lnkW, 500);
        });        
    });
})(jQuery);