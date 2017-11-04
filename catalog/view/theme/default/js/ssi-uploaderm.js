var design_id = 0;
var design_index = 0;
var design_pindex = 0;
var design_product = '';
var productImg = [];
var designData = [];
var baseDesiignUrl = '';
var app = {
	admin:{
		ini: function(){
			jQuery('#designer-products .tab-content a.modal-link').click(function(){
				var link = jQuery(this).attr('href');
				if(jQuery(this).hasClass('add-link')) {
					app.admin.add(this);
				} else {
					app.admin.load(link);
				}
				return false;
			});
		},
		product: function(e, index){
			if (document.getElementById('designer-products') == null) {
				var div = '<div class="modal fade" id="designer-products" tabindex="-1" role="dialog" style="z-index:10520;" aria-labelledby="myModalLabel" aria-hidden="true">'
						+ '<div class="modal-dialog modal-lg" style="width: 95%;">'
						+ 	'<div class="modal-content">'
						+		'<div class="modal-header">'
						+			'<button type="button" data-dismiss="modal" class="close close-list-design">'
						+				'<span aria-hidden="true">×</span>'
						+				'<span class="sr-only">Close</span>'
						+			'</button>'
						+		'</div>'
						+ 		'<div class="modal-body">'
						+		'&#65279;<center><h3>Please wait some time. loading...</h3></center>'
						+		'</div>'
						+	'</div>'
						+ '</div></div>';
				jQuery('body').append(div);
			}
			if(index != 4) {
				jQuery('#designer-products').modal('show');			
			}
			var key = e.getAttribute('key');			
			var data = {};
			data.key = key;
			data.action = 'designer_action';
			var link = ajaxurl.split('wp-admin');
			
			if (index == 0) { // show list product design 
				var url = tshirtURL+'admin-blank.php?/'+design_id;
			} else if (index == 2) { // show list product design template 
				var url = tshirtURL+'admin-users.php';
			} else if (index == 3) { // show list product design template 
				var url = tshirtURL+'admin-create.php';
			} else if (index == 4) { // create new design
				var url = tshirtURL+'admin/index.php?/product/viewmodal&session_id='+session_id;				
				jQuery('#add_designer_product').html('<span class="button-resize button-resize-full" onclick="resizePageDesign(this)"></span><iframe id="tshirtecommerce-designer" frameborder="0" noresize="noresize" width="100%" height="800px" src="'+url+'"></iframe>');
				return;
			} else {
				var url = tshirtURL+'admin.php';
			}
			jQuery.post(url, data, function(response) {
				jQuery('#designer-products .modal-body').html(response);
				app.admin.ini();
			});
			return false; 
		},		
		load: function(link)
		{
			var data = {};
			data.key = '1';
			data.action = 'designer_action';
			data.link = link;
			var link = ajaxurl.split('wp-admin');
			var url = link[0]+'tshirtecommerce/admin.php';
			jQuery('#designer-products .modal-body').html('&#65279;<center><h3>Please wait some time. loading...</h3></center>');
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#designer-products .modal-body').html(response);				
				app.admin.ini();
			});
			return false; 
		},
		add: function(e) {
			var id = jQuery(e).data('id');
			id = String(id);
			if (jQuery(e).hasClass('design-idea') == true) {
				var url = tshirtURL+'admin-template.php?product='+id+'&lightbox=1&session_id='+session_id;
			} else {
				if (id.indexOf(':') == -1) {
					var title = jQuery(e).data('title');
					var img = jQuery(e).children('img').attr('src');
					document.getElementById('_product_id').value = id;
					document.getElementById('_product_title_img').value = title+'::'+img;
					var url = tshirtURL + 'admin/index.php?/product/viewmodal/'+id+'&session_id='+session_id;
				} else {
					var params = id.split(':');
					var url = tshirtURL+'admin-template.php?user='+params[0]+'&id='+params[1]+'&product='+params[2]+'&color='+params[3]+'&lightbox=1&session_id='+session_id;
				}
			}
			var html = '<span class="button-resize button-resize-full" onclick="resizePageDesign(this)"></span><iframe id="tshirtecommerce-designer" frameborder="0" noresize="noresize" width="100%" height="800px" src="'+url+'"></iframe>';
			jQuery('#add_designer_product').html(html);
			jQuery('#designer-products').modal('hide');
		},
		product_detail: function(){
			var product = {};
			if (jQuery('#input-name'+ts_lang_id).length) {
				product.title = jQuery('#input-name'+ts_lang_id).val();
			}

			if (jQuery('#input-description'+ts_lang_id).length) {
				product.description = (jQuery('#input-description'+ts_lang_id).val()).replace('"', '\"');
			} else {
				if (jQuery('#description'+ts_lang_id).length) {
					product.description = (jQuery('#description'+ts_lang_id).val()).replace('"', '\"');
				} else {
					product.description = '';
				}
			}
			product.shortdescription = '';
			var img = jQuery('#input-image');
			if (img.length > 0) {
				product.thumb = siteUrl+'/image/'+jQuery('#input-image').val();
			} else {
				var img_src = '';
				if (typeof jQuery('#image') !== 'undefined') {
					img_src = siteUrl + '/image/' + jQuery('#image').val();
				}
				product.thumb = img_src;
			}
			product.sku = jQuery('#input-sku').val();
			product.price = jQuery('#input-price').val();
			product.sale_price = '';
			product.prices = {};

			product.min_order = jQuery('#input-minimum').val();
			product.max_order = jQuery('#input-quantity').val();

			return product;
		},
		clear: function(){
			if (jQuery('#tshirtecommerce-designer').length > 0) {
				var check = confirm('You sure want clear data design of this product?');
				if (check == true) {
					document.getElementById('_product_id').value = '';
					document.getElementById('_product_title_img').value = '';
					jQuery('#add_designer_product').html('');
				}
			}
		},
		save: function(data, type){
			jQuery('#tshirtecommerce-wapper').hide();
			if (type == 'product') {
				document.getElementById('_product_id').value = data;
			} else if(type == 'idea') {
				var ids = data.designer_id+':'+data.design_id+':'+ data.product_id+':'+ data.productColor;
				document.getElementById('_product_id').value = ids;
			}
			if (jQuery('#form-product').length) {
				jQuery('#form-product').submit();
			}
			if (jQuery('#form').length) {
				jQuery('#form').submit();
			}
		}
	},
	cart: function(content){
		var data = {			
			product_id: content.product_id,
			quantity: content.quantity,
			design: content,
			//option_oc: option_oc,
		};
		designData[design_index] = data;
		var ocedit = '0';
		
		if(ocedit !== 'undefined' && ocedit != '0' && parseInt(ocedit) != 'NaN' && parseInt(ocedit) > 0)
		{
			jQuery.ajax({
				url: 'index.php?route=checkout/cart/remove',
				type: 'post',
				data: 'key=' + ocedit,
				dataType: 'json',
				success: function(json) {
					jQuery.ajax({
						url: 'index.php?route=checkout/cart/add',
						type: 'post',
						data: data,
						dataType: 'json',
						success: function (json) {
							window.location.href = 'index.php?route=checkout/cart';
						}
					});
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
		else
		{
			var imgPath = baseDesiignUrl+JSON.parse(content.images).front,design_index;
			console.log(imgPath);
			darwImage(imgPath,design_product,0,design_pindex);
			/*jQuery.ajax({
				url: 'index.php?route=checkout/cart/save',
				type: 'post',
				data: data,
				dataType: 'json',
				success: function (json) {
					window.location.href = 'index.php?route=checkout/cart';
				}
			});*/
		}
		
	}
}


 var darwImage = function (uploadImage,design,index,pindex) {//return a message label
	 	console.log(design);
		console.log(index);
        var c=document.createElement('canvas');
		ctx=c.getContext('2d');
		c.width=design.front.width;
		c.height=design.front.height;
		ctx.rect(0,0,c.width,c.height);
		ctx.fillStyle='#fff';
		ctx.fill();
		function drawing(design, data,ctx,pindex,index){
				var img=new Image;
				img.src=data;	
				img.onload=function(){
					ctx.drawImage(img,design.front.width,design.front.height);			
					if(productImg[pindex]){
						var pimg = productImg[pindex];
						ctx.drawImage(pimg,design.front.width,design.front.height);
						$('#ssi-imgToUpload'+index).attr('src',c.toDataURL("image/jpeg"));
						$('#designer-body').attr ("style","display:none;");
						//ctx.drawImage(pimg,0,0,pimg.width,pimg.height,design.front.left,design.front.top,design.front.width,design.front.height);
						//callback(element,c.toDataURL("image/jpeg"),index*len+pindex,pindex);
					}
				}			
		}
		drawing(design, uploadImage,ctx,pindex,index);
		
}

var designer = {
	show: function(elm)
	{
		var div = jQuery(elm);
		
		var check = div.data('active');
		if (typeof check != 'undefined' && check == 1) {
			div.hide('slow');
			div.data('active', 0);
		} else {
			div.show('slow');
			div.data('active', 1);
		}
	}
}

// save product of woocommerce
function wooSave(data, type)
{
	app.admin.save(data, type);
}

function setHeigh(height){
	height = height + 10;
	document.getElementById('tshirtecommerce-designer').setAttribute('height', height + 'px');
	
	height = height + 20;
	jQuery('#modal-designer').parents('body').css({'height':height+'px', 'max-height':height+'px'});
}

function getWidth()
{
	var width = jQuery(window).width();
	var sizeZoom = width/500;
	if (sizeZoom < 1) {
		jQuery('meta[name*="viewport"]').attr('content', 'width=device-width, initial-scale='+sizeZoom+', maximum-scale=1');
	}
}

// active link color
function loadProductDesign(e)
{
	if (typeof jQuery(e).data('color') != 'undefined') {
		var color = jQuery(e).data('color');
		var href = jQuery(e).attr('href');
		href = href + '&color='+color;
		window.location.href = href;
		return false;
	}
	return true;
}

// click change color in page product detail
function e_productColor(e)
{
	var parent = jQuery(e).parent();
	parent.children('.bg-colors').removeClass('active');
	
	// add data
	var elm = jQuery(e);
	jQuery('.designer_color_index').attr('name', 'colors['+elm.data('index')+']').val(elm.data('color'));
	jQuery('.designer_color_hex').val(elm.data('color'));
	jQuery('.designer_color_title').val(elm.attr('title'));
	
	jQuery('.e-custom-product').data('color', elm.data('color'));
	elm.addClass('active');
	jQuery(document).triggerHandler( "product.color.images", e);
}

function tshirt_attributes(e, index)
{
	var elm = jQuery(e);
	var type = elm.attr('type');
	var obj = elm.parent().children('.attribute_'+index);
	if (typeof type == 'undefined') {
		var value = elm.find('option:selected').data('id');
		obj.val(value);
	} else if (type == 'checkbox' || type == 'radio') {
		if (elm.is(':checked') == true) {
			obj.prop('checked', true);
		} else {
			obj.prop('checked', false);
		}
	} else {
		obj.val(elm.val());
	}
}

function viewBoxdesign(){
	var width = jQuery(document).width();
	var height = jQuery(document).height();
	if (width < 510 || height < 510) {
		var url = urlDesignload.replace('index.php', 'mobile.php');
			jQuery('body').append('<div id="modal-design-bg"></div><div id="modal-designer"><a href="'+urlBack+'" class="btn btn-dange btn-xs">Close</a><iframe id="tshirtecommerce-designer" scrolling="no" frameborder="0" width="100%" height="100%" src="'+url+'"></iframe></div>');
			jQuery('body').addClass('tshirt-mobile');
	} else {
		jQuery('.row-designer').html('<iframe id="tshirtecommerce-designer" scrolling="no" frameborder="0" noresize="noresize" width="100%" height="100%" src="'+urlDesignload+'"></iframe>');
	}
	
	var url_option = urlDesignload.split('tshirtecommerce/');
	var mainURL = url_option[0];
	
	if (logo_loading.indexOf('http') == - 1) {
		logo_loading = mainURL + logo_loading;
	}
	
	jQuery('.row-designer').append('<div class="mask-loading">'
		+ '<div class="mask-main-loading">'
		+	'<img class="mask-icon-loading" src="'+mainURL+'tshirtecommerce/assets/images/logo-loading.gif" alt="">'
		+	'<img class="mask-logo-loading" src="'+logo_loading+'" alt="">'
		+ '</div>'
		+ '<p>'+text_loading+'</p>'
		+ '</div>'
	);
	
	jQuery("#tshirtecommerce-designer").load( function() {
		setTimeout(function(){
			jQuery('.row-designer .mask-loading').remove();
		}, 1000);
	});
}

function tshirt_close(){
	var href = jQuery('#modal-designer a').attr('href');
	window.location.href = href;
}
jQuery(document).ready(function(){
	design_id = jQuery('#_product_id').val();
	if (design_id == '') {
		design_id = 0;
	}
	var product_type = jQuery('#product-type').val();
	if (product_type == 'variable') {
		jQuery('.variations_options').children('a').trigger('click');
		jQuery('#tshirtecommerce_product a').trigger('click');
	} else {
		jQuery('#tshirtecommerce_product a').trigger('click');
	}
	
	if (jQuery('.row-designer').length > 0) {
		viewBoxdesign();
	}
	
	// active product color
	if (jQuery('.designer-attributes .list-colors .bg-colors').length > 0) {
		if (jQuery('.designer-attributes .list-colors .bg-colors.active').length == 0) {
			var a = jQuery('.designer-attributes .list-colors .bg-colors');
			e_productColor(a[0]);
		} else {
			var a = jQuery('.designer-attributes .list-colors .bg-colors.active');
			e_productColor(a[0]);
		}
	}
	
	// product size
	if (typeof min_order != 'undefined' && jQuery('.quantity .input-text.qty').length > 0) {		
		// check add to cart
		jQuery( document ).on( 'click', '.single_add_to_cart_button', function() {
			var value = jQuery('.quantity .input-text.qty').val();
			if (value < min_order) {
				alert(txt_min_order+' '+min_order);
				return false;
			}
		});
	}
	
	// change size
	jQuery('.p-color-sizes .size-number').on('change', function(){
		var value = jQuery(this).val();
		filter = /^[0-9]+$/;
		if (filter.test(value)) {
			if (value.indexOf('0') == 0) {
				jQuery(this).val(0);
			}
		} else {
			jQuery(this).val(0);
		}
		
		var quantity = 0;
		jQuery('.p-color-sizes .size-number').each(function(){
			quantity = quantity + Math.round(jQuery(this).val());
		});
		jQuery('.quantity .input-text.qty').val(quantity);
	});
	
	// save product in wooommerce
	jQuery('input#publish').click(function(){
		if (jQuery('#tshirtecommerce-designer').length > 0) {
			var iframe = document.getElementById("tshirtecommerce-designer");
			if (typeof iframe.contentWindow.productCategory !== 'undefined' && jQuery.isFunction(iframe.contentWindow.productCategory)) {
				// add product categories.
				function categories(evt, category, cate_id, parent_id, i, j){
					evt.children('li').each(function(){
						var text = jQuery(this).children('.selectit').text();
						var checked = jQuery(this).children('.selectit').children('input').is(':checked');
						var val = jQuery(this).children('.selectit').children('input').val();
						
						category[i] = {id:val, parent_id: parent_id, title:text};
						if (checked) {
							cate_id[j] = val;
							j++;
						}
						i++;
						
						if (jQuery(this).children('ul').hasClass('children')) {
							categories(jQuery(this).children('.children'), category, cate_id, val, i, j);
						}
					});
				};
				
				var cate_id = [],
				category = [];
				categories(jQuery('#product_catchecklist'), category, cate_id, 0, 0, 0);
				iframe.contentWindow.productCategory(cate_id, category);
			};
			
			// save products.
			var name = jQuery('#publish').attr('name');
			var value = jQuery('#publish').val();
			jQuery('#publishing-action').append('<input type="hidden" value="'+value+'" name="'+name+'"/>');
			
			var product_type = jQuery('#product-type').val();
			if (jQuery('#_regular_price').length > 0) {
				var price = jQuery('#_regular_price').val();
				if (price == '' && product_type == 'simple') {
					alert('Please add price of product');
					jQuery('.general_options').children('a').trigger('click');
					return false;
				} else if (product_type == 'variable') {
					if (jQuery('.variable_pricing .wc_input_price').length == 0) {
						alert('Please add variations of this product');
						jQuery('.variations_options').children('a').trigger('click');
						return false;
					} else {
						var input = jQuery('.variable_pricing .wc_input_price');
						var price = jQuery(input[0]).val();
						jQuery('#_regular_price').val(price);
						var prices = '';
						jQuery('.variable_pricing').each(function(){
							var input = jQuery(this).find('.wc_input_price');
							var price = 0;
							if (typeof input[0] != 'undefined') {
								price = jQuery(input[0]).val();
							}
							if (typeof input[1] != 'undefined' && jQuery(input[1]).val() != '') {
								price = jQuery(input[1]).val();
							}
							if (price != 0) {
								var index = jQuery(this).parents('.woocommerce_variation').find("input[name^='variable_post_id']").val();
								if (prices == '') {
									prices = '"'+index +'":"'+ price +'"';
								} else {
									prices = prices +','+ '"'+ index +'":"'+ price +'"';								
								}
							}							
						});
					}
				}
			}
			if (jQuery('#title').length > 0) {
				var title = jQuery('#title').val();
				if (title == '') {
					alert('Please add product name.');
					return false;
				}
			}
			var product = app.admin.product_detail();
			if (typeof prices != 'undefined' && prices != '') {
				product.prices	= '{'+prices+'}';
			}
			var check_validate = iframe.contentWindow.productInfo(product);
			if (check_validate) {
				jQuery('#tshirtecommerce_product a').trigger('click');
				jQuery(this).addClass('disabled');
				jQuery(this).parent().find('.spinner').addClass('is-active');
				jQuery(this).val('Saving...');
			} else {
				jQuery('#tshirtecommerce_product').children('a').trigger('click');
			}
			return false;
		}
	});
	
	// add box of product design
	if (jQuery('#_product_id').length > 0 && jQuery('#_disabled_product_design').val() != 1) {
		var id = jQuery('#_product_id').val();
		if (id != '') {
			var url = tshirtURL + 'admin/index.php?/product/viewmodal';
			if (id.indexOf(':') == -1) {
				url = url + '/' + id +'&session_id='+session_id;
			} else {
				var params = id.split(':');
				var url = tshirtURL+'admin-template.php?user='+params[0]+'&id='+params[1]+'&product='+params[2]+'&color='+params[3]+'&lightbox=1&session_id='+session_id;
			}
			
			jQuery('#add_designer_product').html('<span class="button-resize button-resize-full" onclick="resizePageDesign(this)"></span><iframe id="tshirtecommerce-designer" frameborder="0" noresize="noresize" width="100%" height="800px" src="'+url+'"></iframe>');
		}
	}
});

function resizePageDesign(e){
	var check = jQuery(e).hasClass('button-resize-full');
	if (check) {
		jQuery(e).removeClass('button-resize-full');
		jQuery(e).addClass('button-resize-small');
		jQuery(e).parent('#add_designer_product').addClass('e-full-screen');
		var height = jQuery('#add_designer_product').height();
		jQuery(e).parent('#add_designer_product').find('#tshirtecommerce-designer').attr('height', height+'px');
		jQuery('body').css('overflow', 'hidden');
	} else {
		jQuery('body').css('overflow', 'auto');
		jQuery(e).removeClass('button-resize-small');
		jQuery(e).addClass('button-resize-full');
		jQuery(e).parent('#add_designer_product').removeClass('e-full-screen');
		jQuery(e).parent('#add_designer_product').attr('height', height);
		jQuery(e).parent('#add_designer_product').find('#tshirtecommerce-designer').attr('height', '800px');
	}
};

function getfullWidth() {
	if (jQuery('#modal-designer').length > 0) {
		var width = jQuery('#modal-designer').width();
	} else {
		var width = jQuery('#tshirtecommerce-designer').width();
	}	
	
	return width;
}

function dg_full_screen()
{
	if (jQuery('body').hasClass('dg_screen')) {
		jQuery('body').removeClass('dg_screen');
		return 0;
	} else {
		jQuery('body').addClass('dg_screen');
		return 1;
	}
}

jQuery(document).ready(function(){
	if (typeof e_remove_cart_item != 'undefined' && e_remove_cart_item == true) {
		jQuery('.woocommerce-message').hide();
	}
	
	jQuery('.e_tshirt_add').click(function(){
		jQuery(this).children('.dropdown-menu').toggle();
	});
});

(function (root, factory) {
    //@author http://ifandelse.com/its-not-hard-making-your-library-support-amd-and-commonjs/
    if (typeof module === "object" && module.exports) {
        module.exports = factory(require("jquery"));
    } else {
        factory(root.jQuery);
    }
}(this, function ($) {
    var Ssi_upload = function (element, options) {
        this.options = options;
        this.$element = '';
        this.language = locale[this.options.locale];
        this.uploadList = [];
		this.uploadRespone = [];
		this.design = [];
        this.totalProgress = [];
        this.toUpload = [];
        this.imgNames = [];
        this.totalFilesLength = 0;
        this.successfulUpload = 0;
        this.aborted = 0;
        this.abortedWithError = 0;
        this.pending = 0;
        this.inProgress = 0;
        this.currentListLength = 0;
		
		this.uploadImage =[];
		this.index = 0;
        this.init(element);
    };
    Ssi_upload.prototype.init = function (element) {

        $(element).addClass('ssi-uploadInput')
         .after(this.$element = $('<div class="ssi-uploader">'));
        var $chooseBtn = $('' +
         '<span class="ssi-InputLabel">' +
         '<button class="ssi-button success " id="chooseBtn">' + this.language.chooseFiles + '</button>' +
         '</span>').append(element);
        var $uploadBtn = $('<button id="ssi-uploadBtn" class="ssi-button error ssi-hidden " >' +
         '<span class="ssi-btnIn" id="ssi-upload_text" style="text-align:center;">' + this.language.upload + '&nbsp;</span>' +
         '<div id="ssi-up_loading" class="ssi-btnIn"></div></button>');
		 var $saveBtn = $('<button id="ssi-saveBtn" class="ssi-hidden ssi-button error" style="margin-left:5px;" >' + this.language.buynew +
         '</button>');
        var $clearBtn = $('<button id="ssi-clearBtn" class="ssi-hidden ssi-button error" style="margin-left:5px" >' + this.language.clear +
         '</button>');
        var $abortBtn = $('<button id="ssi-abortBtn" class="ssi-button error ssi-cancelAll ssi-hidden" ><span class="inBtn">' + this.language.abort + ' </span></button>');
		var $tips = $('<div style="float: right;text-align:center;margin-left:20px;margin-bottom:-5px;">' + this.language.tips + ' </div>');
		var pview ='';
		for(i in this.options.products){
			var design = this.options.products[i];
			console.log(design);
			productImg[i] = new Image();
			productImg[i].src= design.front.img;	
			productImg[i].onload=function(){
			};
			pview += createView(i,i,design.front.img,'display:none');
		}
		$productview = $(pview);

        this.$element.append($('<div class="ssi-buttonWrapper" >').append($chooseBtn,$uploadBtn));
		//this.$element.append($saveBtn);
        var $uploadBox;
        if (!this.options.preview) {
            this.$element.addClass('ssi-uploaderNP');
            var $fileList = $('<table id="ssi-fileList" class="ssi-fileList"></table>');
            var $namePreview = $('<span class="ssi-namePreview"></span>');
            var $mainBox = $('<div id="ssi-uploadFiles" class="ssi-tooltip ssi-uploadFiles ' + (this.options.dropZone ? 'ssi-dropZone' : '') + '"><div id="ssi-uploadProgressNoPreview" class="ssi-uploadProgressNoPreview"></div></div>')
             .append($namePreview);
            var $uploadDetails = $('<div class="ssi-uploadDetails"></div>').append($fileList);
            $uploadBox = $('<div class="ssi-uploadBoxWrapper ssi-uploadBox"></div>').append($mainBox, $uploadDetail);
            this.$element.prepend($uploadBox);
        } else {
            $uploadBox = $('<div id="ssi-previewBox" class="ssi-uploadBox ssi-previewBox ' + (this.options.dropZone ? 'ssi-dropZonePreview ssi-dropZone"><div id="ssi-DropZoneBack"></div>' : '">') + '</div>');
			$uploadBox.append($productview);
            this.$element.append($uploadBox);
        }
        var thisS = this;
        var $input = $chooseBtn.find(".ssi-uploadInput");
        $chooseBtn.find('button').click(function () {
            $input.trigger('click');
        });
		
		var chooseImage = function(){
			$input.trigger('click');
		}

        $input.on('change', function () { //choose files
            thisS.toUploadFiles(this.files);
            $input.val('');
			if($(window).width()<=500){
					   $uploadBtn.attr('style','float:right;border-radius:0px;width:50%;margin:0px;text-align:center; padding-left:22%');
					   $('#chooseBtn').attr('style','border-radius:0px;width:50%');
				    }
        });
        //drag n drop
        if (thisS.options.dropZone) {
            $uploadBox.on("drop", function (e) {
                e.preventDefault();
                $uploadBox.removeClass("ssi-dragOver");
                var files = e.originalEvent.dataTransfer.files;
                thisS.toUploadFiles(files);
            });
            $uploadBox.on("dragover", function (e) {
                e.preventDefault();
                $uploadBox.addClass("ssi-dragOver");
                return false;
            });
            $uploadBox.on("dragleave", function (e) {
                e.preventDefault();
                $uploadBox.removeClass("ssi-dragOver");
                return false;
            });
        }

        if (!thisS.options.preview) {
            $mainBox.click(function () {
                if (thisS.currentListLength > 1)
                    $uploadDetails.toggleClass('ssi-uploadBoxOpened');
            })
        }

        $clearBtn.click(function () { //choose files completed and pending files
            thisS.clear();
			$uploadBox.append($productview);
        });

        var $tooltip;

        $uploadBox.on('mouseenter', '.ssi-statusLabel', function (e) { //the tooltip
            var $eventTarget = $(e.currentTarget);
            var title = $eventTarget.attr('data-status');
            if (!title || title === '') {
                return;
            }
            var tooltipHeight = '35';
            if ($eventTarget.hasClass('ssi-noPreviewSubMessage')) {
                tooltipHeight = 0;
            }
            $tooltip = $('<div class="ssi-infoTooltip">'
             + title +
             '</div>')
             .appendTo(thisS.$element)
             .css({top: $eventTarget.position().top - tooltipHeight, left: $eventTarget.position().left - 5})
             .fadeIn('slow');

        });

        $uploadBox.on('mouseleave', '.ssi-statusLabel', function () {
            if ($tooltip)
                $tooltip.remove();
        });

        $uploadBox.on('click', '.ssi-removeBtn', function (e) { //remove the file from list
            var $currentTarget = $(e.currentTarget);
            var index = $currentTarget.data('delete'); //get file's index
            thisS.pending--; //reduce pending number by 1
            thisS.currentListLength--; //reduce current list length by 1
            if (thisS.pending === 0) {
                $uploadBtn.prop('disabled', true); //if there is no more files disable upload button
            }
            if (thisS.options.preview) { //if preview is true
                $currentTarget.parents('table.ssi-imgToUploadTable').remove(); //remove table
            } else {
                var target = $currentTarget.parents('tr.ssi-toUploadTr'); //find the tr of file
                $namePreview.html((thisS.currentListLength) + ' files'); //set the main name to the remaining files
                target.prev().remove();// remove empty tr (using id for margin between rows)
                target.remove();// remove the file
                if (thisS.currentListLength === 1) { //if only one file left in the list
                    setLastElementName(thisS); //set main preview to display the name
                }
            }
            thisS.toUpload[index] = null; //set the file's obj to null (we don't splice it because we need to keep the same indexes)
            thisS.imgNames[index] = null; //set the file's name to null

            if (thisS.currentListLength === 0) { // if no more files in the list
                if (!thisS.options.dropZone) { // if drag and drop is disabled
                    $uploadBox.removeClass('ssi-uploadNoDropZone');
                }
                $clearBtn.addClass('ssi-hidden');
                $uploadBtn.addClass('ssi-hidden');
				$uploadBox.append($productview);
				$('#ssi-uploadBtn').attr('style','display:none;');
				$('#chooseBtn').attr('style','border-radius:0px;width:100%');
				$('#chooseBtn').text(thisS.language.chooseFiles);
				
            }
        });
        $uploadBox.on('click', '.ssi-abortUpload', function (e) {//abort one element
            var $eventTarget = $(e.currentTarget);
            var index = $eventTarget.data('delete');// get the element id
            thisS.abort(index); // abort request
        });
		 $saveBtn.on('click',function (e) {//abort one element
		 	//console.log(123);
            var selected = $('input[name^=\'selected\']:checked');
			if(selected.length<=0){
				 alert('Please select a design product!');
				 return;
			}
		   ajaxLoopRequestAddCart(thisS,selected,0);
		   
		   
        });
		function ajaxLoopRequestAddCart(thisS,selected,selectIndex) {
			
			if(selectIndex>=selected.length){				
				window.location.href="index.php?route=checkout/cart";
				return;
			}
			if (!$(selected[selectIndex]).val()) {
				return ajaxLoopRequestAddCart(thisS,selected,selectIndex+1);
			}
			var index = $(selected[selectIndex]).val();
			//console.log("index="+index);
			var pindex = thisS.$element.find('#ssi-editBtn'+index).get(0).getAttribute("data-product");
			//console.log("pindex="+pindex);
			var datas = getDesignInfo(thisS,index,pindex);
			//console.log(datas);
			jQuery.ajax({
				type: "POST",
				processData: false,
				data: datas,
				dataType: "json",
				contentType: "application/json; charset=utf-8",	
				url: thisS.options.siteURL + "ajax.php?type=addCart&edit=true"					
			}).done(function( responseData ){
				console.log(responseData);
				try {
                    data = $.parseJSON(responseData);
                } catch (err) {
                    data = responseData;
                }
				if(data.error==0){
					var option = data.product;
					var design = thisS.options.products[pindex];
					var formData =  new FormData();
					formData.append("quantity",design.quantity);
					formData.append("product_id",design.product_id);
					//formData.append('option[tshirtecommerce][image]',thisS.$element.find('#ssi-imgToUpload'+index).get(0).getAttribute('src'));
					formData.append('option[tshirtecommerce][t_product_id]',design.design_product_id);
					formData.append('option[tshirtecommerce][options][t_product_id]',design.design_product_id);
			   		formData.append('option[tshirtecommerce][option_oc]',"");
					formData.append('option[tshirtecommerce][refer]',"designer");
				 	formData.append('option[tshirtecommerce][colors]',["FFFFFF"]); 
				 	formData.append('option[tshirtecommerce][type]',"cart");
				 	formData.append('option[tshirtecommerce][price_of_print]',""+option.options.price+"");
					formData.append('option[tshirtecommerce][tattributes][quantity]',option.quantity);
					
					//console.log(option.options);
					for(var k in option) {
						 var item = option[k];
						 if (typeof item === 'object'){
							for(var i in item) {
								if (typeof item[i] === 'object'){
									for(var key in item[i]) {
										formData.append('option[tshirtecommerce][options]['+k+']['+i+']['+key+']',item[i][key]);
									}
								}else{
									formData.append('option[tshirtecommerce][options]['+k+']['+i+']',item[i]);
								}
							}
						 }else{
							 formData.append('option[tshirtecommerce][options]['+k+']',item);
						 }
					}
					
				   $.ajax({
							url: 'index.php?route=checkout/cart/add',
							type: 'post',
							data: formData,
							dataType: 'json',
							cache: false,
							contentType: false,
							processData: false,
							success: function(responseData) {
								  console.log(responseData);
								  if (responseData['success']) {
								  		ajaxLoopRequestAddCart(thisS,selected,selectIndex+1);
								  }
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
				}
            	//console.log(data);

			});			
		}
		
		 $uploadBox.on('click', '.ssi-imgToUpload', function (e) { 
		 	var $currentTarget = $(e.currentTarget);
			var pindex = $currentTarget.data('product'); 
			var product = thisS.options.products[pindex];
			var url = 'index.php?route=product/designer&design_product_id='+product.design_product_id+'&product_id='+product.product_id;
		 	 window.location.href=url;
		 });
		
		 $uploadBox.on('click', '.image-link', function (e) { //remove the file from list
		 
		 	
            var $currentTarget = $(e.currentTarget);
            var index = $currentTarget.data('edit'); //get file's index
			var pindex = $currentTarget.data('product'); //get file's index
			
			if(!thisS.design[index]){
				design(index,pindex,thisS,'');
				return;
			}
			
			var datas = getDesignInfo(thisS,index,pindex);
			
            console.log(datas);

			jQuery.ajax({
				type: "POST",
				processData: false,
				data: datas,
				dataType: "json",
				contentType: "application/json; charset=utf-8",	
				url: thisS.options.siteURL + "ajax.php?type=addCart&edit=true"					
			}).done(function( responseData ){
				try {
                    data = $.parseJSON(responseData);
                } catch (err) {
                    data = responseData;
                }
				if(data.error==0){
					design(index,pindex,thisS,data.product.rowid);
					
				}
				
            	console.log(data);

			});			

        });
		
		var design = function(index,pindex,thisS,cart_id){
			    baseDesiignUrl = thisS.options.siteURL;
				design_index = index;
				design_pindex = pindex;
				var product = thisS.options.products[pindex];
				design_product = product;
				var url = thisS.options.siteURL+'index.php?product='+product.design_product_id+'&parent='+product.product_id;
				if(cart_id){
					url +='&cart_id='+cart_id;
				}
				$('#input-text-body').html('<iframe src="'+url+'" frameborder="0" border="0" cellspacing="0" style="border-style: none;width: 100%; height: 600px;"></iframe>');
				$('#designer-body').attr("style",'display:block;');
		}
		
//----------------------------UPLOADFILES------------------------------------
        $uploadBtn.click(function () {// upload the files
            //thisS.uploadFiles();
			 var selected = $('input[name^=\'selected\']:checked');
			if(selected.length<=0){
				 alert('Please select a design product!');
				 return;
			}
		   ajaxLoopRequestAddCart(thisS,selected,0);
        });
        $abortBtn.click(function () { // abort all requests
            thisS.abortAll();
        });

    };
    Ssi_upload.prototype.abortAll = function () {
        for (var i = 0; i < this.uploadList.length; i++) { //all element in the list
            if (typeof this.uploadList[i] === 'object') {// check if not deleted
                this.abort(i)
            }
        }
    };
    Ssi_upload.prototype.toUploadFiles = function (files) {
        if (typeof this.options.maxNumberOfFiles === 'number') {
            if ((this.inProgress + this.pending) >= this.options.maxNumberOfFiles) {// if in progress files + pending files are more than the number that we have define as max number of files pre download
                return;//don't do anything
            }
        }
        var thisS = this,
         j = 0,
         length,
         imgContent = '',
         $uploadBtn = this.$element.find('#ssi-uploadBtn'),
         $clearBtn = this.$element.find('#ssi-clearBtn'),
         $fileList = this.$element.find('#ssi-fileList'),
         $uploadBox = this.$element.find('.ssi-uploadBox'),
         imgs = [];
        if ((this.inProgress === 0 && this.pending === 0)) { //if no file are pending or are in progress
            //this.clear(); //clear the list
        }
        var extErrors = [], sizeErrors = [], errorMessage = '';
        var toUploadLength, filesLength = length = toUploadLength = files.length;
        if (typeof this.options.maxNumberOfFiles === 'number') {//check if requested files agree with our arguments
            if (filesLength > this.options.maxNumberOfFiles - (this.inProgress + this.pending)) { //if requested files is more than we need
                filesLength = toUploadLength = this.options.maxNumberOfFiles - (this.inProgress + this.pending); // set variable to the number of files we need
            }
        }
        //
        for (var i = 0; i < filesLength; i++) {
            var file = files[i],
             ext = file.name.getExtension();// get file's extension
            if ($.inArray(ext, this.options.allowed) === -1) { // if requested file not allowed
                if (length > filesLength) {//there are more file we dont pick
                    filesLength++;//the add 1 more loop
                } else {
                    toUploadLength--;
                }
                if ($.inArray(ext, extErrors) === -1) {//if we see first time this extension
                    extErrors.push(ext); //push it to extErrors variable
                }
            } else if ((file.size * Math.pow(10, -6)).toFixed(2) > this.options.maxFileSize) {//if file size is more than we ask
                if (length > filesLength) {
                    filesLength++;
                } else {
                    toUploadLength--;
                }
                sizeErrors.push(cutFileName(file.name, ext, 15));//register a size error
            } else if ($.inArray(file.name, this.imgNames) === -1) {// if the file is not already in the list
                $uploadBtn.prop("disabled", false);
                setupReader(file);
                this.pending++; // we have one more file that is pending to be uploaded
                this.currentListLength++;// we have one more file in the list
            } else {
                if (length > filesLength) {
                    filesLength++;
                } else {
                    toUploadLength--;
                }
            }
        }
        var extErrorsLength = extErrors.length, sizeErrorsLength = sizeErrors.length;
        if (extErrorsLength + sizeErrorsLength > 0) { // in the end expose all errors
            if (extErrorsLength > 0) {
                errorMessage = this.language.extError.replaceText(extErrors.toString().replace(/,/g, ', '));
            }
            if (sizeErrorsLength > 0) {
                errorMessage += this.language.sizeError.replaceText(sizeErrors.toString().replace(/,/g, ', '), this.options.maxFileSize + 'mb');
            }
            this.options.errorHandler.method(errorMessage, this.options.errorHandler.error);
        }
        function setupReader() {
            var index = thisS.totalFilesLength + thisS.pending;
            if (index === 0) {//do it only the first time
                if (thisS.options.preview) {
                    if (!thisS.options.dropZone) {
                        $uploadBox.addClass('ssi-uploadNoDropZone')
                    }
                }
                $uploadBtn.removeClass('ssi-hidden');
                $clearBtn.removeClass('ssi-hidden');
            }
            $clearBtn.prop('disabled', false);
            thisS.toUpload[index] = file;
            var filename = file.name;
            var ext = filename.getExtension(); //get file's extension
            thisS.imgNames[index] = filename; //register file's name
            if (thisS.options.preview) {
                
                var fileType = file.type.split('/');
                if (fileType[0] == 'image') {
                    $uploadBtn.prop("disabled", true);
                    $clearBtn.prop("disabled", true);
                    var fileReader = new FileReader();
                    fileReader.onload = function () {
						/*if(index>0){
							 imgContent += createView(index,0,'','display:none'); // set the files element without the img
						}*/
                       
                        imgs[index] = fileReader.result;
                        j++;
                        if (toUploadLength === j) {// if all elements are in place lets load images
                           /* if(index>0){
							 $uploadBox.append(imgContent);
						}*/
                            $uploadBtn.prop("disabled", false);
                            $clearBtn.prop("disabled", false);
                            setTimeout(function () {
                                setImg();//and load the images
								thisS.uploadFiles();
                            }, 10);
                            imgContent = '';
                            toUploadLength = [];
                        }
                    };
                    fileReader.readAsDataURL(file);
                } else {
                    imgs[index] = null;
                    $uploadBox.append(getTemplate('<div class="document-item" href="test.mov" filetype="' + ext + '"><span class = "fileCorner"></span></div>'));
                    j++;
                }
            } else {
                thisS.$element.find('.ssi-namePreview').html((index === 0 ? cutFileName(filename, ext, 13) : (thisS.currentListLength + 1) + ' ' + thisS.language.files));//set name preview
                $fileList.append('<tr class="ssi-space"><td></td></tr>' +//append files element to dom
                 '<tr class="ssi-toUploadTr ssi-pending"><td><div id="ssi-uploadProgress' + index + '" class="ssi-hidden ssi-uploadProgress ssi-uploadProgressNoPre"></div>' +
                 '<span>' + cutFileName(filename, ext, 20) + '</span></td>' +
                 '<td><a data-delete="' + index + '" class="ssi-button ssi-removeBtn  ssi-removeBtnNP"><span class="trash7 trash"></span></a></td></tr>');
            }

            var setImg = function () {//load the images
                for (var i = 0; i < imgs.length; i++) {
                    if (imgs[i] !== null) {
						 thisS.uploadImage[i]= imgs[i];
                        /*$uploadBox.find("#ssi-uploadProgress" + i).parents('table.ssi-imgToUploadTable')
                         .find('.ssi-imgToUpload')
                         .attr('src-data', imgs[i]) //set src of the image
                         .next().remove();//remove the spinner*/
						
                        imgs[i] = null;
                    }
                }
                imgs = [];
            };
        }
    };
	
	var getDesignInfo = function(thisS,index,pindex){
		console.log(index);
		console.log(pindex);
		var len = thisS.options.products.length;
		var design = thisS.options.products[pindex];
		//console.log(design);
		var json = design.design_info.design.params.front ;
		//console.log(json);
		json.replace("'","\"");
		var params = eval ("(" + json+ ")");
		//console.log(params.width);
		var datas = '{"product_id": "'+design.design_product_id+'", "colors": {"0":"'+design.design_info.design.color_hex+'"},"print": {"sizes":'+JSON.stringify('{\"front\":{\"width\":'+params.width+',\"height\":'+params.height+',\"size\":3}}')+', "colors": '+JSON.stringify('{\"front\":[\"ffffff\"],\"back\":\"\",\"left\":\"\",\"right\":\"\"}')+',"elements": {"front": [{"width": '+design.area.width+', "height": '+design.area.height+', "type": "upload"}], "back": [], "left":[], "right":[]}}, "quantity": "'+design.quantity+'", "cliparts": {"front": []}, "refer": "designer", "product_id_oc": "'+design.product_id+'", "print_type": "'+design.design_info.print_type+'", "design":'+thisS.design[index]+', "teams": { },"fonts": ""}';
		return datas;
	}
	
    var clearCompleted = function (thisS) {//clear all completed files
        var $completed = thisS.$element.find('.ssi-completed');
        thisS.successfulUpload = 0;
        thisS.aborted = 0;
        thisS.abortedWithError = 0;
        if (!thisS.options.preview)$completed.prev('tr').remove();
        $completed.remove();
		
    };
    var clearPending = function (thisS) {//clear all pending files
        var $pending = thisS.$element.find('.ssi-pending');
        var length = thisS.imgNames.length;
        for (var i = 0; i < length; i++) {
            if (thisS.imgNames[i] === null) {
                thisS.toUpload.splice(i, 1);
                thisS.imgNames.splice(i, 1);
            }
        }
        thisS.toUpload.splice(-thisS.pending, thisS.pending);
        thisS.imgNames.splice(-thisS.pending, thisS.pending);
        thisS.pending = 0;
        if (!thisS.options.preview)$pending.prev('tr').remove();
        $pending.remove();
    };

    Ssi_upload.prototype.clear = function (action) {//clean the list of all non in progress files
        switch (action) {
            case 'pending':
                clearPending(this);
                break;
            case 'completed':
                clearCompleted(this);
                break;
            default:
                clearPending(this);
                clearCompleted(this);
				
        }
        var $uploadBtn = this.$element.find('#ssi-uploadBtn'),
         $clearBtn = this.$element.find('#ssi-clearBtn');
        this.currentListLength = getCurrentListLength(this);
        if (this.inProgress === 0) { //if no file are uploading right now
            this.totalProgress = [];
        }
        if ((this.currentListLength === 0)) { // if no items in the list
            $clearBtn.addClass('ssi-hidden');
            $uploadBtn.addClass('ssi-hidden');
            this.totalFilesLength = 0;
			/*var $productview = $('<table class="ssi-imgToUploadTable ssi-pending"><tr><td class="ssi-upImgTd"><img class="ssi-imgToUpload" id="ssi-imgToUpload0" src="'+this.options.thumb+'"></td></tr></table>');
			this.$element.find('.ssi-uploadBox').append($productview);*/
			
            if (!this.options.dropZone) {
                this.$element.find('.ssi-uploadBox').removeClass('ssi-uploadNoDropZone')
            }
        }
        $clearBtn.prop('disabled', true);
        $uploadBtn.prop('disabled', true);

        if (!this.options.preview) {
          
		    setNamePreview(this);
        }
		
    };

    var setNamePreview = function (thisS) {//set the name preview according to the remaining elements in the list
        if (thisS.currentListLength > 1) {//if more than one element left
            thisS.$element.find('.ssi-namePreview').html(thisS.currentListLength + ' files'); // set the name preview to the length of the remaining items
        } else if (thisS.currentListLength === 1) {//if one left
            setLastElementName(thisS); // set the name of the element
        } else { //if no elements left
            thisS.$element.find('.ssi-uploadDetails').removeClass('ssi-uploadBoxOpened');
            thisS.$element.find('#ssi-fileList').empty();//empty list
            thisS.$element.find('.ssi-namePreview').empty();//empty the name preview
        }

    };
    Ssi_upload.prototype.uploadFiles = function () {// upload the pending files
        if (this.pending > 0) {
            this.$element.find('#ssi-abortBtn').removeClass('ssi-hidden');
            this.$element.find('.ssi-removeBtn')
             .addClass('ssi-abortUpload')
             .removeClass('ssi-removeBtn')
             .children('span').removeClass('trash7 trash10 trash')
             .addClass((this.options.preview ? 'ban7w' : 'ban7'));//transform remove button to abort button
            var $uploadBtn = this.$element.find('#ssi-uploadBtn'),
             $clearBtn = this.$element.find('#ssi-clearBtn');
            $uploadBtn.prop("disabled", true);
            var thisS = this,
             formData = new FormData(),//set the form data
             i = this.totalFilesLength;
            if (this.totalFilesLength !== 0 && !this.options.preview) {
                setNamePreview(this);
            }
            this.inProgress += this.pending;// add pending to in progress
            this.totalFilesLength += this.pending;// this variable is to prevent id duplication during uploads
            this.pending = 0;
            if (this.inProgress === this.currentListLength) {// disable the clear button if no items in list we can be remove
                $clearBtn.prop("disabled", true);
            }
            while (this.toUpload[i] === null) { // do it until you find a file
                i++;
            }
            formData.append('myfile', thisS.toUpload[i]);//append the first file to the form data
            $.each(this.options.data, function (key, value) {// append all extra data
                formData.append(key, value);
            });
            if (typeof this.options.beforeUpload === 'function') {
                try {
                    this.options.beforeUpload();// execute the beforeUpload callback
                } catch (err) {
                    console.log('There is an error in beforeUpload callback');
                    console.log(err);
                    thisS.abortAll();
                    return;
                }
            }
            thisS.$element.find('input.ssi-uploadInput').trigger('beforeUpload.ssi-uploader');
            ajaxLoopRequest(formData, i);// make the request
        }

        //--------------start of ajax request-----------------------
        function ajaxLoopRequest(formData, ii) {
            var selector = 'table.ssi-imgToUploadTable';
            if (!thisS.options.preview) {
                selector = 'tr.ssi-toUploadTr'
            }
            var uploadBar = thisS.$element.find('#ssi-uploadProgress' + ii);//get the file's  progress bar
            uploadBar.removeClass('ssi-hidden') //make it visible
             .parents(selector).removeClass('ssi-pending');
            var ajaxOptions = $.extend({}, {//store the request to the uploadList variable
                xhr: function () {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (e) {// add event listener to progress
                        if (e.lengthComputable) {
                            var percentComplete = (e.loaded / e.total) * 100;// calculate the progress
                            if (uploadBar) {
                                uploadBar.css({
                                    width: percentComplete + '%'//update the progress bar width according to the progress
                                });
                            }
                            thisS.totalProgress[ii] = percentComplete;//store the progress to the array
                            var sum = arraySum(thisS.totalProgress) / (thisS.inProgress + thisS.successfulUpload);//and calculate the overall progress
                            if (!thisS.options.preview) {
                                thisS.$element.find('#ssi-uploadProgressNoPreview')
                                 .removeClass('ssi-hidden')
                                 .css({
                                     width: sum + '%'
                                 });
                            }
                            $uploadBtn.find('#ssi-up_loading').html(Math.ceil(sum) + '%');// add to upload button the current overall progress percent number
                        }
                    }, false);
                    return xhr;
                },
                async: true,
                beforeSend: function (xhr) {
                    thisS.uploadList[ii] = xhr;
					
					$uploadBtn.find('#ssi-upload_text').html(thisS.language.upload+'&nbsp;');
                    $uploadBtn.find('#ssi-up_loading') //add spiner to uploadbutton
                     .html('<i class="fa fa-spinner fa-pulse"></i>');
                    if (typeof thisS.options.beforeEachUpload === 'function') {
                        try {
                            var msg = thisS.options.beforeEachUpload({// execute the beforeEachUpload callback and save the returned value
                                name: thisS.toUpload[ii].name,//send some info of the file
                                type: thisS.toUpload[ii].type,
                                size: (thisS.toUpload[ii].size / 1024).toFixed(2)

                            }, xhr);
                        } catch (err) {
                            console.log('There is an error in beforeEachUpload callback');
                            console.log(err);
                            thisS.abortAll();
                            return;
                        }
                    }
                    thisS.$element.find('input.ssi-uploadInput').trigger('beforeEachUpload.ssi-uploader');
                    if (xhr.status === 0) {
                        if (xhr.statusText === 'canceled') {//if user used beforeEachUpload to abort the request
                            if (typeof msg === 'undefined') {//if no message
                                msg = false; //because user have already aborted the request set to false or anything else except undefined to prevent to abort it again
                            }
                            thisS.abortedWithError++;// we have one error more
                            thisS.abort(ii, msg);//call the abort function
                        }
                    }
                },
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                url: thisS.options.url,
                error: function (request, error) {
                    if (error !== 'abort') {
                        uploadBar.addClass('ssi-canceledProgressBar');
                        var msg = thisS.language.error;
                        thisS.abortedWithError++;//add one more error
                        thisS.totalProgress.splice(ii, 1); //remove from progress array
                        if (!thisS.options.preview) {
                            msg = '<span class="exclamation7"></span>';
                        }
                        setElementMessage(thisS, ii, 'error', msg, thisS.language.serverError);
                        thisS.totalProgress[ii] = '';
                        thisS.inProgress--;
                        $clearBtn.prop("disabled", false);
                        if (typeof thisS.options.onEachUpload === 'function') {//execute the onEachUpload callback
                            try {
                                thisS.options.onEachUpload({//and return some info
                                    uploadStatus: 'error',
                                    name: thisS.toUpload[ii].name,
                                    size: (thisS.toUpload[ii].size / 1024).toFixed(2),
                                    type: thisS.toUpload[ii].type
                                });
                            } catch (err) {
                                console.log('There is an error in onEachUpload callback');
                                console.log(err);
                            }
                        }
                        if (getCompleteStatus(thisS)) {//if no more elements in progress
                            finishUpload(thisS);
                        }
                        console.log(arguments);//log the error
                        console.log(" Ajax error: " + error);
                    }
                }
            }, thisS.options.ajaxOptions);
            $.ajax(ajaxOptions).done(function (responseData, textStatus, jqXHR) {
                var msg, title = '', dataType = 'error', spanClass = 'exclamation', data;
                try {
                    data = $.parseJSON(responseData);
                } catch (err) {
                    data = responseData;
                }
                if (thisS.options.responseValidation) {
                    var valData = thisS.options.responseValidation;
                    if (typeof valData.validationKey === 'object' && valData.resultKey == 'validationKey') {
                        if (data.hasOwnProperty(valData.validationKey.success)) {
                            cb(true);
                        } else {
                            cb(false, data[valData.validationKey.error]);
                        }
                    } else {
                        if (data[valData.validationKey] == valData.success) {
                            cb(true);
                        } else {
                            cb(false, data[valData.resultKey]);
                        }
                    }
                } else {
                    if (jqXHR.status == 200) {
                        cb(true, data);
                    } else {
                        cb(false, data);
                    }
                }
                function cb(result, data) {
                    if (result) {//if response type is success
					console.log(data);
						if (data.status == 1)
							{
								
								thisS.uploadRespone[thisS.index] = data;
								dataType = 'success';
                        		msg = thisS.language.success;
                        		spanClass = 'check';
                        		thisS.successfulUpload++;// one more successful upload
								
								//console.log('123:'+ii);
								/*element = thisS.$element.find('#ssi-imgToUpload'+ ii);
								//thisS.uploadImage[ii] = element.get(0).getAttribute("src-data");
								var design = thisS.options.products[0];
								var len = thisS.options.products.length;
								getResultImage(thisS,thisS.uploadImage[ii],ii,design,0,len,element,function(element,dataURL,index,pindex){
									element.attr('src',dataURL);
								}); 
								var index = len*ii;
								var checkbox = thisS.$element.find('#checkbox'+ ii);
								checkbox.val(index);
								checkbox.attr('id','checkbox'+ index);
								
								var editBtn = thisS.$element.find('#ssi-editBtn'+ ii);
								editBtn.attr('id','ssi-editBtn'+ index);
								editBtn.attr('data-edit',index);
								editBtn.attr('data-product',0);*/
								
								
								//thisS.$element.find('#ssi-imgToUploadTable'+ii).remove();
								setProductView(thisS,ii);
								thisS.index++;
							}
							else
							{
								uploadBar.addClass('ssi-canceledProgressBar');
                        		if (thisS.options.preview) {
                           		 	msg = data.msg;
                        		}
                        		title = 'Error';
                        		thisS.abortedWithError++;
							}
                        
                    } else {
                        uploadBar.addClass('ssi-canceledProgressBar');
                        if (thisS.options.preview) {
                            msg = thisS.language.error;
                        }
                        title = data;
                        thisS.abortedWithError++;
                    }
                }

                if (!thisS.options.preview) {
                    msg = '<span class="' + spanClass + '7"></span>';
                }
                setElementMessage(thisS, ii, dataType, msg, title);

                if (typeof thisS.options.onEachUpload === 'function') {//execute the onEachUpload callback
                    try {
                        thisS.options.onEachUpload({//and return some info
                            uploadStatus: dataType,
                            name: thisS.toUpload[ii].name,
                            size: (thisS.toUpload[ii].size / 1024).toFixed(2),
                            type: thisS.toUpload[ii].type
                        });
                    } catch (err) {
                        console.log('There is an error in onEachUpload callback');
                        console.log(err);
                    }
                }
                thisS.$element.find('input.ssi-uploadInput').trigger('onEachUpload.ssi-uploader');
                thisS.inProgress--;//one less in progress upload
                $clearBtn.prop("disabled", false);
                if (getCompleteStatus(thisS)) {//if no more files in progress
                    finishUpload(thisS);
					
                }
                // thisS.totalProgress[ii]='';
                thisS.uploadList[ii] = '';
                thisS.toUpload[ii] = '';
                thisS.imgNames[ii] = '';
            });
            //--------------end of ajax request-----------------------

            i = ii;
            i++;//go to the next element
            while (thisS.toUpload[i] === null) {// do it until you find a file
                i++;
            }
            if (i < thisS.toUpload.length) {// if more files exist start the next request
                formData = new FormData();
                $.each(thisS.options.data, function (key, value) {
                    formData.append(key, value);
                });
                formData.append('myfile', thisS.toUpload[i]);
                ajaxLoopRequest(formData, i);
            }
        }
    };
	
	var setProductView = function(thisS,ii){
		var len = thisS.options.products.length;
		for(var i=0;i<len;i++){
			//for(ii in thisS.uploadImage){
				var design = thisS.options.products[i];
			  	getResultImage(thisS,thisS.uploadImage[ii],thisS.index,design,i,len,thisS,function(thisS,dataURL,index,pindex){
				  if(index==0){
					  thisS.$element.find('#ssi-previewBox').html(createView(index,pindex,dataURL,'display: inline-block;position: absolute;'));
				  }else{
					   thisS.$element.find('#ssi-previewBox').append(createView(index,pindex,dataURL,'display: inline-block;position: absolute;'));
				  }
				  
				  //element.attr('src',dataURL);
			  	}); 
			//}
		}
	}
	
    var setElementMessage = function (thisS, index, msgType, msg, title) {
        var className = '', elementSelector = 'table.ssi-imgToUploadTable', element;
        if (!thisS.options.preview) {
            className = 'ssi-noPreviewSubMessage';
            elementSelector = 'tr.ssi-toUploadTr';
            if (thisS.currentListLength === 1) {
                thisS.errors = title;
            }
        }

        element = thisS.$element.find(".ssi-abortUpload[data-delete='" + index + "']");
        element.parents(elementSelector).addClass('ssi-completed');
        //element.after(getResultMessage(msgType, msg, title, className))
         element.remove();
		 element = thisS.$element.find("#ssi-uploadProgress" + index + "");
         element.remove();
		 $('#ssi-success'+ index).attr('style','display:block');
		 if($(window).width()<=500){
			 $('#ssi-uploadBtn').attr('style','display:block;float:right;border-radius:0px;width:50%;margin:0px;text-align:center;padding-left:22%');
			 //$('#chooseBtn').attr('style','border-radius:0px;width:50%');
		 }else{
			 $('#ssi-uploadBtn').attr('style','display:block;float:right;margin-left:8px;');
		 }
		 $('#chooseBtn').text(thisS.language.chooseMoreFiles);
		 
    };

    var getCompleteStatus = function (thisS) {//check if file are in progress
        return (thisS.inProgress === 0);
    };

    var getResultMessage = function (type, msg, title, classes) {//return a message label
        return '<span class="ssi-statusLabel ' + classes + ' ' + type + '" data-status="' + title + '">' + msg + '</span>';
    };
	
	var createView =  function(index,pindex,dataURL,style){
		/*return '<div class="image-select"><div class="product-img"><div class="product-img-action"><img src="https://img.oberlo.com/?url=https://supply-cdn.oberlo.com/products/61/965c415c82820f2571d5f1069c111fb7.jpg" class="border-img"> <div class="icon-selected-box align-center"><svg class="icon-selected"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/img/app-icons.svg#icon-selected"></use></svg></div></div> <div class="image-link"><a href="https://img.oberlo.com/?url=https://supply-cdn.oberlo.com/products/61/965c415c82820f2571d5f1069c111fb7.jpg">View Original Size</a></div></div></div>';*/
		return '<table class="ssi-imgToUploadTable ssi-completed"><tbody>'+
		'<tr id="editbtntr'+index+'" style="'+style+'"><td style="margin-left:-1px;margin-top:-1px;"><input  type="checkbox" checked id="checkbox[]"  name="selected[]" value="'+index+'" style="margin: 5px 0 5px 5px;padding: 0;width:25px;height:25px;" class="css-checkbox" /><label for="checkbox[]" class="css-label"></label></td></tr>'+
'<tr><td class="ssi-upImgTd"><img class="ssi-imgToUpload" id="ssi-imgToUpload'+index+'" data-product="'+pindex+'" src="'+dataURL+'"></td></tr>' +
'<tr><td class="ssi-buttonDesign"><button id = "ssi-editBtn'+index+'" data-edit="' + index + '" data-product="'+pindex+'"  class="image-link" style="width: 342px;" ><span class="fa fa-pencil"></span>&nbsp;&nbsp;Custom Design</button></td></tr>'+
		'</tbody></table>';
	}
	
	 var getResultImage = function (thisS, uploadImage,index,design,pindex,len,element,callback) {//return a message label
	 	
        var c=document.createElement('canvas');
		ctx=c.getContext('2d');
		//console.log(design.front);
		//console.log(design.area);
		c.width=design.front.width;
		c.height=design.front.height;
		ctx.rect(0,0,c.width,c.height);
		ctx.fillStyle='#fff';
		ctx.fill();
		function drawing(thisS, design, data,n,ctx,c,uploadImage,index,pindex,len,element,callback){
			    //console.log(data);
				var img=new Image;
				img.src=data;	
				img.onload=function(){
					if(n==1){
						//console.log(0,0,img.width,img.height,design.front.left,design.front.top,design.front.width,design.front.height);
						ctx.drawImage(img,0,0,img.width,productImg.height,design.front.left,design.front.top,design.front.width,design.front.height);
						callback(element,c.toDataURL("image/jpeg"),index*len+pindex,pindex);
					}else{
						var sx = 0;
						var sy = 0;
						var scale =1;
						if(design.area.width<design.area.height){
							scale = design.area.height/img.height;
							if(img.width*scale<design.area.width){
								scale = design.area.width/img.width;
								sy = ((scale)*img.height-design.area.height)/scale;
								sy /=2;
							}else{
								sx = ((scale)*img.width-design.area.width)/scale;
								sx /=2;
							}
							
						}else{
							scale = design.area.width/img.width;
							if(img.height*scale<design.area.height){
								scale = design.area.height/img.height;
								sx = ((scale)*img.width-design.area.width)/scale;
								sx /=2;
							}else{
								sy = ((scale)*img.height-design.area.height)/scale;
								sy /=2;

							}
							
						}
						var top = sy*scale;
						var left = sx*scale;
						var width = img.width*scale;
						var height = img.height*scale;
						//console.log(sx,sy,img.width-sx*2,img.height-sy*2,design.area.left,design.area.top,design.area.width,design.area.height);
						
						ctx.drawImage(img,sx,sy,img.width-sx*2,img.height-sy*2,design.area.left,design.area.top,design.area.width,design.area.height);			var siteURL = thisS.options.siteURL;
						var items = thisS.uploadRespone[index].item;
						//console.log( items);
						var colors = design.design_info.design.color_hex;
						console.log('pindex='+pindex+'index*len+pindex='+(index*len+pindex));
						
						//console.log( design.front.img);
						//drawing(thisS, design, design.front.img,1,ctx,c,element,index);
						if(productImg[pindex]){
							var pimg = productImg[pindex];
							//console.log(0,0,productImg.width,productImg.height,design.front.left,design.front.top,design.front.width,design.front.height);
							ctx.drawImage(pimg,0,0,pimg.width,pimg.height,design.front.left,design.front.top,design.front.width,design.front.height);
							callback(element,c.toDataURL("image/jpeg"),index*len+pindex,pindex);
							thisS.design[index*len+pindex]='{"vectors":'+JSON.stringify('{\"front\":{\"0\":{\"type\":\"clipart\",\"upload\":1,\"title\":\"'+items.title+'\",\"url\":\"'+siteURL+items.url+'\",\"file_name\":\"'+items.file_name+'\",\"thumb\":\"'+siteURL+items.thumb+'\",\"confirmColor\":true,\"remove\":true,\"edit\":false,\"rotate\":0,\"file\":{\"type\":\"image\"},\"width\":\"'+width+'px\",\"height\":\"'+height+'px\",\"change_color\":0,\"svg\":\"<svg xmlns=\\\"http://www.w3.org/2000/svg\\\" xml:space=\\\"preserve\\\" width=\\\"'+design.area.width+'\\\" height=\\\"'+design.area.height+'\\\" preserveAspectRatio=\\\"none\\\" xmlns:xlink=\\\"http://www.w3.org/1999/xlink\\\"><g><image x=\\\"0\\\" y=\\\"0\\\" width=\\\"'+img.width*scale+'\\\" height=\\\"'+img.height*scale+'\\\" preserveAspectRatio=\\\"none\\\" xlink:href=\\\"'+siteURL+items.url+'\\\"></image></g></svg>\",\"id\":0,\"lockedProportion\":0,\"colors\":[\"'+colors+'\"],\"top\":\"-'+top+'px\",\"left\":\"-'+left+'px\",\"zIndex\":\"6\"}}}')+',"images": {"front":"'+c.toDataURL("image/jpeg")+'"},"isIE": false,"file_name": "'+items.file_name+'"}';
						}
					}
				}
			
		}
		drawing(thisS, design, uploadImage,0,ctx,c,element,index,pindex,len,element,callback);
		
    };
	


    var getCurrentListLength = function (thisS) { //get the list length
        return (thisS.inProgress + thisS.successfulUpload + thisS.aborted + thisS.abortedWithError + thisS.pending);
    };
    var setLastElementName = function (thisS) { //if one file in list get the last file's name and put it to the name preview
        var fileName = thisS.$element.find('#ssi-fileList').find('span').html();//find the only span left
        var ext = fileName.getExtension();//get the extension
        thisS.$element.find('.ssi-uploadDetails').removeClass('ssi-uploadBoxOpened');
        thisS.$element.find('.ssi-namePreview').html(cutFileName(fileName, ext, 15));//short the name and put it to the name preview
    };
    Ssi_upload.prototype.abort = function (index, title) {//abort a request
        if (typeof title === 'undefined') {//if no title
            this.uploadList[index].abort();// abort the element
            this.totalProgress[index] = '';
            title = 'Aborted';
            this.aborted++;// one more aborted file
        } else if (typeof title !== 'string') {//if not string that means that the request aborted with the beforeUpload callback and no message returned
            title = '';
        }
        //nothing of the above happened that means the user aborted the request with the beforeUpload callback and returned a message
        var msg = this.language.aborted;
        if (!this.options.preview) {
            msg = '<span class="ban7w"></span>';
        }
        setElementMessage(this, index, 'error', msg, title);
        this.$element.find('#ssi-uploadProgress' + index).removeClass('ssi-hidden').addClass('ssi-canceledProgressBar');
        this.toUpload[index] = undefined;
        this.uploadList[index] = undefined;
        this.imgNames[index] = undefined;
        this.$element.find('#ssi-clearBtn').prop("disabled", false);
        this.inProgress--;//one less in progress file
        if (getCompleteStatus(this)) {//if no more file in progress
            finishUpload(this);
        }

    };

    var finishUpload = function (thisS) {//when every uplaod ends

        thisS.$element.find('#ssi-abortBtn').addClass('ssi-hidden');
        if (!thisS.options.preview) {//display tha main message in the name preview
            var type = 'error', title = '', msg = '';
            if (thisS.abortedWithError > 0) { //if no errors
                if (thisS.totalFilesLength === 1) {// if only one file in the list
                    title = thisS.errors; //display the error
                } else {//else display something more general message
                    title = thisS.language.someErrorsOccurred
                }
                msg = '<span class="exclamation23"></span>';
            } else if (thisS.aborted > 0 && thisS.successfulUpload === 0) {//if all request aborted
                msg = '<span class="ban23"></span>';
                title = thisS.language.aborted;
            } else if (thisS.successfulUpload > 0) {// all request were successfull
                type = 'success';
                msg = '<span class="check23"></span>';
                title = thisS.language.sucUpload;
            }
            thisS.$element.find('.ssi-namePreview').append(getResultMessage(type, msg, title, 'ssi-noPreviewMessage'));//show the message in the name preview
            thisS.$element.find('#ssi-uploadProgressNoPreview') //remove main overall progress bar
             .removeAttr('styles')
             .addClass('ssi-hidden');
        }
        if (typeof thisS.options.onUpload === 'function') {
            try {
                thisS.options.onUpload();//execute the on Upload callback
            } catch (err) {
                console.log('There is an error in onUpload callback');
                console.log(err);
            }
        }
        thisS.$element.find('input.ssi-uploadInput').trigger('onUpload.ssi-uploader');
        var $uploadBtn = thisS.$element.find('#ssi-uploadBtn');
        thisS.$element.find('#ssi-clearBtn').prop("disabled", false);
        $uploadBtn.prop("disabled", false)
         .find('#ssi-up_loading')
         .empty();
		 $uploadBtn.find('#ssi-upload_text').html(thisS.language.buynew);
        if (thisS.pending === 0) {
            $uploadBtn.addClass('ssi-hidden');
            thisS.toUpload = [];
            thisS.imgNames = [];
            thisS.totalFilesLength = 0;
			
			//$uploadBox.append($productview);
        }
        thisS.uploadList = [];
        thisS.totalProgress = [];
        thisS.currentListLength = getCurrentListLength(thisS);
        thisS.successfulUpload = 0;
        thisS.aborted = 0;
        thisS.abortedWithError = 0;
        thisS.inProgress = 0;
    };

    $.fn.ssi_uploader = function (opts) {
        var defaults = {
            url: '',
            data: {},
            locale: 'en',
            preview: true,
            dropZone: false,
            maxNumberOfFiles: '',
            responseValidation: false,
            maxFileSize: 2,
            ajaxOptions: {},
            onUpload: function () {
            },
            onEachUpload: function () {
            },
            beforeUpload: function () {
            },
            beforeEachUpload: function () {
            },
            allowed: ['jpg', 'jpeg', 'png', 'bmp', 'gif'],
            errorHandler: {
                method: function (msg) {
                    alert(msg);
                },
                success: 'success',
                error: 'error'
            }
        };
        var options = $.extend(true, defaults, opts);
        return this.each(function () {
            var $element = $(this);
            if ($element.is('input[type="file"]')) {
                if ($element.data('ssi_upload')) return;
                var ssi_upload = new Ssi_upload(this, options);
                $element.data('ssi_upload', ssi_upload);
            } else {
                console.log('The targeted element is not file input.')
            }

        });
    };
    //functions
    String.prototype.replaceText = function () {//replace $ with variables
        var args = Array.apply(null, arguments);
        var text = this;
        for (var i = 0; i < args.length; i++) {
            text = text.replace('$' + (i + 1), args[i])
        }
        return text;
    };
    String.prototype.getExtension = function () {//returns the extension of a path or file
        return this.split('.').pop().toLowerCase();
    };
    var cutFileName = function (word, ext, maxLength) {//shorten the name
        if (typeof ext === 'undefined')ext = '';
        if (typeof maxLength === 'undefined')maxLength = 10;
        var min = 4;
        if (maxLength < min)return;
        var extLength = ext.length;
        var wordLength = word.length;
        if ((wordLength - 2) > maxLength) {
            word = word.substring(0, maxLength);
            var wl = word.length - extLength;
            word = word.substring(0, wl);
            return word + '...' + ext;
        } else return word;
    };

    var arraySum = function (arr) {//return the sum of an array
        var sum = 0;
        for (var i = 0; i < arr.length; i++) {
            if (typeof arr[i] === 'number')
                sum += arr[i];
        }
        return sum;
    };

    var locale = {
        en: {
            success: 'Success',
            sucUpload: 'Successful upload',
            chooseFiles: 'Upload (multi - supported)',
			chooseMoreFiles: 'Upload more',
            uploadFailed: 'Upload failed',
			tips:'<p>(We accept the following file types: <strong>png, jpg, gif</strong>)</p>',
            serverError: 'Internal server error',
            error: 'Error',
            abort: 'Abort',
			buynew: 'Buy',
            aborted: 'Aborted',
            files: 'files',
            upload: 'Uploading',
            clear: 'Clear',
            drag: 'Drag n Drop',
            sizeError: '$1 exceed the size limit of $2',// $1=file name ,$2=max ie( example.jpg has has exceed the size limit of 2mb)
            extError: '$1 file types are not supported',//$1=file extension ie(exe files are not supported)
            someErrorsOccurred: 'Some errors occurred!'
        },
        gr: {
            success: 'Επιτυχία',
            sucUpload: 'Επιτυχής μεταφόρτωση',
            chooseFiles: 'Επιλέξτε αρχεία',
            uploadFailed: 'Η μεταφόρτωση απέτυχε!',
            serverError: 'Εσωτερικό σφάλμα διακομιστή!',
            error: 'Σφάλμα',
            abort: 'Διακοπή',
            aborted: 'Διακόπηκε',
            files: 'αρχεία',
            upload: 'Μεταφόρτωση',
            clear: 'Εκκαθάριση',
            drag: 'Συρετε εδώ...',
            sizeError: '$1 έχει ξεπεράσει το όριο των $2.',// $1=file name ,$2=max file size ie( example.jpg has has exceed the size limit of 2mb)
            extError: '$1 αρχεία δεν υποστηρίζονται.',// $1=file extension ie(exe files are not supported)
            someErrorsOccurred: 'Σημειώθηκαν ορισμένα λάθη!'
        }
    };

}));
