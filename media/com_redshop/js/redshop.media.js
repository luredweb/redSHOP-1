!function(e,i,t,n){function a(i,t){this._name=o,this._defaults=d,this.element=i,this.options=e.extend({},d,t),this.token=null,this.dropzoneInstance=null,this.$container=e(i),this.$dropzonePreview=this.$container.find(".rs-media-dropzone-preview"),this.$dropzoneForm=this.$container.find(".rs-media-dropzone-form"),this.$alertModal=this.$container.find(".rs-media-alert-modal"),this.$cropperModal=this.$container.find(".rs-media-cropper-modal"),this.$cropperButton=this.$container.find(".rs-media-cropper-btn"),this.$removeButton=this.$container.find(".rs-media-remove-btn"),this.$target=this.$container.find(".redshop-media-img-select"),this.$mediaFileButton=null,this.$mediaFileInsertButton=null,this.$mediaFileModal=null,this.$mediaFilePreview=null,this.$mediaFileDelModal=null,this.init()}var o="redshopMedia",d={uploadUrl:"index.php?option=com_redshop&view=media&task=ajaxUpload",deleteUrl:"index.php?option=com_redshop&view=media&task=ajaxDelete",allowedMime:"image/jpeg,image/jpg,image/png,image/gif",maxFileSize:2048,initFile:null,showMediaFiles:!1};a.prototype={init:function(){var i=this;if(this._initAttributes(),Dropzone.autoDiscover=!1,i.$dropzoneForm.length&&e("body").append(e(i.$dropzoneForm[0]).html()),e("#"+i.$container.data("id")+"-dropzone")&&(i.dropzoneInstance=new Dropzone("#"+i.$container.data("id")+"-dropzone",{url:i.options.uploadUrl,autoProcessQueue:!0,thumbnailWidth:null,thumbnailHeight:null,previewTemplate:e(i.$dropzonePreview[0]).html()})),i.$alertModal.length&&(i.$alertModal=e(i.$alertModal[0])),i.$cropperButton.length&&(i.$cropperButton=e(i.$cropperButton[0])),i.$removeButton.length&&(i.$removeButton=e(i.$removeButton[0])),i.$target.length&&(i.$target=e(i.$target[0])),1==i.options.showMediaFiles&&(i.$container.find(".rs-media-gallery-modal").length&&(i.$mediaFileModal=e(i.$container.find(".rs-media-gallery-modal")[0])),i.$container.find(".rs-media-gallery-preview").length&&(i.$mediaFilePreview=e(i.$container.find(".rs-media-gallery-preview")[0])),i.$container.find(".rs-media-gallery-delete-modal").length&&(i.$mediaFileDelModal=e(i.$container.find(".rs-media-gallery-delete-modal")[0])),i.$container.find(".rs-media-gallery-btn").length&&(i.$mediaFileButton=e(i.$container.find(".rs-media-gallery-btn")[0])),i.$container.find(".rs-media-gallery-insert-btn").length&&(i.$mediaFileInsertButton=e(i.$container.find(".rs-media-gallery-insert-btn")[0])),this._initMediaFileEvents()),this._initEvents(),null!=i.options.initFile){var t=i.dataURItoBlob(i.options.initFile.blob);t.name=i.options.initFile.name,i.dropzoneInstance.emit("thumbnail",i.options.initFile,i.options.initFile.url),i.dropzoneInstance.addFile(t)}},_initAttributes:function(){this.token=this.$container.attr("data-token")},_initEvents:function(){var i=this;i.dropzoneInstance.on("addedfile",function(e){return i.validateFile(e)?(this.files.length>1&&this.removeFile(this.files[0]),i.$cropperButton.removeClass("disabled").prop("disabled",!1),i.$removeButton.removeClass("disabled").prop("disabled",!1),void(i.$container.find("#rs-media-img-delete").length&&i.$container.find("#rs-media-img-delete").remove())):void this.removeFile(e)}),i.dropzoneInstance.on("success",function(e,t){t=JSON.parse(t),t.success&&i.$target.val(t.data.file.url)}),i.$removeButton.on("click",function(t){t.preventDefault(),i.dropzoneInstance.removeAllFiles(),i.$target.val(""),i.$cropperButton.addClass("disabled").prop("disabled",!0),i.$removeButton.addClass("disabled").prop("disabled",!0);var n=null;i.$container.find("#rs-media-img-delete").length<=0&&(n=e("<input/>"),n.attr("id","rs-media-img-delete").attr("name",i.$container.data("id")+"_delete").attr("type","text"),e("#adminForm").append(n)),n.val(!0)}),i.$cropperButton.on("click",function(e){e.preventDefault();var t=i.dropzoneInstance.files[0];return t?void(t.width<100||i.$cropperModal.modal("show")):void showAlert("Please insert an image!!!")}),i.$cropperModal.on("shown.bs.modal",function(e){var t=i.dropzoneInstance.files[0],n=t.name,a=i.$cropperModal.find(".crop-upload"),o=i.$cropperModal.find(".image-container img").first(),d=new FileReader;d.onloadend=function(){o.attr("src",d.result),o.cropper("destroy").cropper({dragMode:"move",autoCropArea:.5,movable:!1,cropBoxResizable:!0,viewMode:3,zoomable:!0})},t.preload?d.readAsDataURL(i.dataURItoBlob(t.blob)):d.readAsDataURL(t),a.off("click"),a.on("click",function(){var e=o.cropper("getCroppedCanvas").toDataURL(),a=i.dataURItoBlob(e);a.cropped=!0,a.name=n,i.dropzoneInstance.removeFile(t),i.dropzoneInstance.addFile(a),i.$cropperModal.modal("hide")})})},_initMediaFileEvents:function(){var i=this;i.$mediaFileButton.click(function(e){e.preventDefault(),i.$mediaFileModal.modal("show")}),i.$mediaFileModal.find(".img-obj").click(function(t){t.preventDefault(),e(this).hasClass("selected")?e(this).removeClass("selected"):(i.$mediaFileModal.find(".img-obj").removeClass("selected"),e(this).addClass("selected"),i.mediaFileShowInfor(this)),i.mediaFileResetPreview(),i.mediaFileToggleInsert()}),i.$mediaFileInsertButton.click(function(e){e.preventDefault();var t=i.$mediaFileModal.find(".img-obj.selected").find("img").first(),n=t.attr("src");i.$target.val(n);var a=new XMLHttpRequest;a.open("GET",n),a.responseType="blob",a.send(),a.addEventListener("load",function(){var e=new FileReader;e.readAsDataURL(this.response),e.addEventListener("loadend",function(){var n=i.dataURItoBlob(e.result);n.name=t.attr("alt"),i.dropzoneInstance.addFile(n),i.$mediaFileModal.modal("hide")})})}),i.$mediaFileModal.find(".btn-del-g").on("click",function(t){t.preventDefault(),i.$mediaFileDelModal.find(".btn-confirm-del-g").data("id",e(this).data("id")),i.$mediaFileDelModal.modal("show")}),i.$mediaFileDelModal.find(".btn-confirm-del-g").on("click",function(t){t.preventDefault();var n=e(this).data("id");n&&e.ajax({url:i.options.deleteUrl,method:"post",data:{id:n}}).done(function(e){i.$mediaFileModal.find(".img-obj.selected").parent().remove(),i.$mediaFileModal.find(".pv-wrapper").addClass("hidden")}).always(function(e){i.$mediaFileDelModal.modal("hide")})})},validateFile:function(e){var i=this,t=e.size/1024;return t>i.options.maxFileSize?(i.showAlert(Joomla.JText._("COM_REDITEM_UPLOAD_FILE_TOO_BIG")),!1):i.options.allowedMime.indexOf(e.type)!=-1||(i.showAlert(Joomla.JText._("COM_REDSHOP_MEDIA_ERROR_FILE_UPLOAD_INVALID")),!1)},showAlert:function(e){var i=this;i.$alertModal.find(".alert-text").text(e),i.$alertModal.modal("show")},dataURItoBlob:function(e){for(var i=atob(e.split(",")[1]),t=new ArrayBuffer(i.length),n=new Uint8Array(t),a=0;a<i.length;a++)n[a]=i.charCodeAt(a);return new Blob([t],{type:"image/jpg"})},mediaFileShowInfor:function(i){var t=this;if(!(t.$mediaFileModal.find(".preview-pane").length<=0)){var n=e(t.$mediaFileModal.find(".preview-pane")[0]),a=e(i).find(".img-type"),o={id:a.data("id"),url:a.attr("src"),name:a.attr("alt"),size:a.data("size"),dimension:a.data("dimension")},d=a.clone();n.find(".pv-img .img-type").remove(),n.find(".pv-img").append(d),n.find(".pv-zoom").attr("href",o.url),n.find(".pv-zoom").attr("data-title",o.name),n.find(".pv-link").attr("href",o.url),n.find(".pv-name").text(o.name),n.find(".pv-size").text(o.size),n.find(".pv-dimension").text(o.dimension),n.find(".pv-url").html('<input type="text" value="'+o.url+'" class="form-control" readonly="true">'),n.find(".pv-remove > a").data("id",o.id),n.find(".pv-wrapper").removeClass("hidden")}},mediaFileResetPreview:function(){var i=this;if(!(i.$mediaFileModal.find(".preview-pane").length<=0)){var t=e(i.$mediaFileModal.find(".preview-pane")[0]);i.$mediaFileModal.find(".img-obj.selected").length<=0&&t.find(".pv-wrapper").addClass("hidden")}},mediaFileToggleInsert:function(){var e=this;null!=e.$mediaFileModal&&(e.$mediaFileModal.find(".img-obj.selected").length>0?e.$mediaFileInsertButton.removeAttr("disabled"):e.$mediaFileInsertButton.attr("disabled","true"))}},e.fn[o]=function(i){return this.each(function(){e.data(this,"plugin_"+o)||e.data(this,"plugin_"+o,new a(this,i))})}}(jQuery,window,document);